<?php

namespace App\Filament\Widgets;

use App\Models\SiteLog;
use App\Models\TestDriveBooking;
use App\Models\Gallery;
use App\Models\Setting;
use App\Services\AiService;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AiInsightsWidget extends Widget
{
    protected static string $view = 'filament.widgets.ai-insights-widget';
    protected static ?int $sort = 0;
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        // Hanya untuk paket PRO
        return Setting::isPro();
    }

    protected function getViewData(): array
    {
        $thirtyDaysAgo = now()->subDays(30);

        // 1. DATA ANALITIK DASAR
        $totalVisits = SiteLog::where('log_type', 'visit')->where('created_at', '>=', $thirtyDaysAgo)->count();
        $totalBookings = TestDriveBooking::where('created_at', '>=', $thirtyDaysAgo)->count();

        // Waktu Terramai Kunjungan (WIB)
        if (DB::connection()->getDriverName() === 'sqlite') {
            $peakHour = SiteLog::where('log_type', 'visit')
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->select(DB::raw("strftime('%H', datetime(created_at, '+7 hours')) as hour"), DB::raw('count(*) as total'))
                ->groupBy('hour')
                ->orderBy('total', 'desc')
                ->first();
        } else {
            $peakHour = SiteLog::where('log_type', 'visit')
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->select(DB::raw('HOUR(DATE_ADD(created_at, INTERVAL 7 HOUR)) as hour'), DB::raw('count(*) as total'))
                ->groupBy('hour')
                ->orderBy('total', 'desc')
                ->first();
        }

        $peakHourRange = 'Tidak ada data';
        if ($peakHour) {
            $formattedHour = (int) $peakHour->hour;
            $peakHourRange = sprintf("%02d:00 - %02d:00", $formattedHour, ($formattedHour + 1) % 24);
        }

        // Mobil Paling Populer (Minggu ini)
        $trendingCar = SiteLog::where('log_type', 'visit')
            ->whereNotNull('car_id')
            ->where('created_at', '>=', now()->subDays(7))
            ->select('car_id', DB::raw('count(*) as total'))
            ->groupBy('car_id')
            ->orderBy('total', 'desc')
            ->first();

        $trendingCarName = 'Tidak ada data';
        if ($trendingCar) {
            $car = \App\Models\Car::find($trendingCar->car_id);
            if ($car) {
                $trendingCarName = $car->name;
            }
        }

        // Keaktifan Galeri (Terakhir update)
        $lastGallery = Gallery::orderBy('updated_at', 'desc')->first();
        $daysSinceGallery = $lastGallery ? $lastGallery->updated_at->diffInDays(now()) : null;

        // Top 3 Wilayah Kunjungan
        $topRegions = SiteLog::where('log_type', 'visit')
            ->whereNotNull('region')
            ->select('region', DB::raw('count(*) as total'))
            ->groupBy('region')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->pluck('region')
            ->toArray();

        $topRegionsStr = !empty($topRegions) ? implode(', ', $topRegions) : 'Tidak ada data';

        // Susun payload untuk AI
        $analyticsData = [
            'total_visits' => $totalVisits,
            'total_bookings' => $totalBookings,
            'conversion_rate' => ($totalVisits > 0) ? round(($totalBookings / $totalVisits) * 100, 1) : 0,
            'peak_hour' => $peakHourRange,
            'trending_car' => $trendingCarName,
            'days_since_gallery' => $daysSinceGallery,
            'top_regions' => $topRegionsStr,
        ];

        // 2. AMBIL DARI REAL AI SERVICE (Dengan Cache agar performa dashboard responsif)
        $userId = auth()->id() ?? 'guest';
        $cacheKey = "ai_insights_user_{$userId}";

        $insights = Cache::get($cacheKey);

        if ($insights === null) {
            $aiService = new AiService();
            $insights = $aiService->generateInsights($analyticsData);

            if (!empty($insights)) {
                // Simpan di cache selama 2 jam jika sukses
                Cache::put($cacheKey, $insights, now()->addHours(2));
            }
        }

        $isRealAi = !empty($insights);

        // 3. FALLBACK STATIC RULES (Jika AI dimatikan atau request error)
        if (empty($insights)) {
            $insights = [];

            // Rule A: Performa Penjualan
            if ($totalVisits > 0) {
                $convRate = $analyticsData['conversion_rate'];
                if ($convRate >= 3) {
                    $insights[] = [
                        'icon' => 'heroicon-o-presentation-chart-line',
                        'color' => 'success',
                        'title' => 'Strategi Penjualan Efektif',
                        'text' => "Tingkat konversi Anda ({$convRate}%) sangat baik. Terus bagikan testimoni pelanggan ke media sosial untuk mempertahankan kepercayaan ini."
                    ];
                } else {
                    $insights[] = [
                        'icon' => 'heroicon-o-light-bulb',
                        'color' => 'warning',
                        'title' => 'Peluang Closing',
                        'text' => "Traffic Anda cukup baik, namun closing (booking) masih bisa ditingkatkan. Coba buat promo 'Limited Time' untuk unit yang paling sering dilihat."
                    ];
                }
            }

            // Rule B: Kesegaran Konten
            if (!$lastGallery) {
                $insights[] = [
                    'icon' => 'heroicon-o-camera',
                    'color' => 'info',
                    'title' => 'Unggah Foto Pertama',
                    'text' => "Galeri foto Anda masih kosong. Unggah foto penyerahan unit (handover) atau unit showroom ready stock untuk menarik minat calon pembeli."
                ];
            } elseif ($lastGallery->updated_at->diffInDays(now()) > 7) {
                $insights[] = [
                    'icon' => 'heroicon-o-camera',
                    'color' => 'info',
                    'title' => 'Update Galeri Diperlukan',
                    'text' => "Sudah lebih dari 7 hari Anda tidak mengupdate foto galeri. Pelanggan lebih tertarik pada unit dengan foto-foto terbaru untuk bahan Story Instagram."
                ];
            }

            // Rule C: Waktu Terbaik Posting
            if ($peakHour) {
                $insights[] = [
                    'icon' => 'heroicon-o-megaphone',
                    'color' => 'primary',
                    'title' => 'Jadwal Konten Sosmed',
                    'text' => "Pengunjung paling ramai di jam **{$peakHourRange} WIB**. Pastikan Anda memposting update galeri atau promo baru di Instagram pada jam tersebut."
                ];
            }

            // Rule D: Unit Trending
            if ($trendingCar && isset($car)) {
                $insights[] = [
                    'icon' => 'heroicon-o-fire',
                    'color' => 'danger',
                    'title' => 'Unit Sedang Tren',
                    'text' => "Mobil **{$car->name}** sedang banyak dicari minggu ini. Fokuskan materi konten Instagram Anda pada unit ini untuk menarik lebih banyak leads."
                ];
            }
        }

        return [
            'insights' => $insights,
            'is_real_ai' => $isRealAi
        ];
    }
}
