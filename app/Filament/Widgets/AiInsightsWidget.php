<?php

namespace App\Filament\Widgets;

use App\Models\SiteLog;
use App\Models\TestDriveBooking;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\Setting;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

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
        $insights = [];
        $thirtyDaysAgo = now()->subDays(30);

        // 1. DATA DASAR
        $totalVisits = SiteLog::where('log_type', 'visit')->where('created_at', '>=', $thirtyDaysAgo)->count();
        $totalBookings = TestDriveBooking::where('created_at', '>=', $thirtyDaysAgo)->count();

        // 2. INSIGHT: PERFORMA PENJUALAN (PRO ONLY)
        if ($totalVisits > 0) {
            $convRate = round(($totalBookings / $totalVisits) * 100, 1);
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

        // 3. INSIGHT: KESEGARAN KONTEN (SOSIAL MEDIA)
        $lastGallery = Gallery::orderBy('updated_at', 'desc')->first();
        if ($lastGallery && $lastGallery->updated_at->diffInDays(now()) > 7) {
            $insights[] = [
                'icon' => 'heroicon-o-camera',
                'color' => 'info',
                'title' => 'Update Galeri Diperlukan',
                'text' => "Sudah lebih dari 7 hari Anda tidak mengupdate foto galeri. Pelanggan lebih tertarik pada unit dengan foto-foto terbaru untuk bahan Story Instagram."
            ];
        }

        // 4. INSIGHT: WAKTU TERBAIK POSTING (WIB)
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

        if ($peakHour) {
            $formattedHour = (int) $peakHour->hour;
            $hourRange = sprintf("%02d:00 - %02d:00", $formattedHour, ($formattedHour + 1) % 24);
            $insights[] = [
                'icon' => 'heroicon-o-megaphone',
                'color' => 'primary',
                'title' => 'Jadwal Konten Sosmed',
                'text' => "Pengunjung paling ramai di jam **{$hourRange} WIB**. Pastikan Anda memposting update galeri atau promo baru di Instagram pada jam tersebut."
            ];
        }

        // 5. INSIGHT: UNIT TRENDING
        $trendingCar = SiteLog::where('log_type', 'visit')
            ->whereNotNull('car_id')
            ->where('created_at', '>=', now()->subDays(7))
            ->select('car_id', DB::raw('count(*) as total'))
            ->groupBy('car_id')
            ->orderBy('total', 'desc')
            ->first();

        if ($trendingCar) {
            $car = \App\Models\Car::find($trendingCar->car_id);
            if ($car) {
                $insights[] = [
                    'icon' => 'heroicon-o-fire',
                    'color' => 'danger',
                    'title' => 'Unit Sedang Tren',
                    'text' => "Mobil **{$car->name}** sedang banyak dicari minggu ini. Fokuskan materi konten Instagram Anda pada unit ini untuk menarik lebih banyak leads."
                ];
            }
        }

        return [
            'insights' => $insights
        ];
    }
}
