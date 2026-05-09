<?php

namespace App\Filament\Widgets;

use App\Models\SiteLog;
use App\Models\TestDriveBooking;
use App\Models\Setting;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class AiInsightsWidget extends Widget
{
    protected static string $view = 'filament.widgets.ai-insights-widget';
    protected static ?int $sort = 0; // Show very top
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Setting::isPro();
    }

    protected function getViewData(): array
    {
        $insights = [];

        // 1. Conversion Insight
        $totalVisits = SiteLog::where('log_type', 'visit')->count();
        $totalBookings = TestDriveBooking::count();
        if ($totalVisits > 0) {
            $conversionRate = round(($totalBookings / $totalVisits) * 100, 1);
            if ($conversionRate >= 5) {
                $insights[] = [
                    'icon' => 'heroicon-o-arrow-trending-up',
                    'color' => 'success',
                    'title' => 'Konversi Tinggi',
                    'text' => "Luar biasa! Tingkat konversi website Anda mencapai {$conversionRate}%. Strategi marketing Anda saat ini sangat efektif."
                ];
            } elseif ($conversionRate > 0) {
                $insights[] = [
                    'icon' => 'heroicon-o-exclamation-triangle',
                    'color' => 'warning',
                    'title' => 'Potensi Peningkatan',
                    'text' => "Tingkat konversi saat ini {$conversionRate}%. Pertimbangkan untuk menawarkan promo khusus pada halaman detail mobil untuk meningkatkan booking."
                ];
            } else {
                $insights[] = [
                    'icon' => 'heroicon-o-information-circle',
                    'color' => 'info',
                    'title' => 'Kumpulkan Lebih Banyak Data',
                    'text' => "Traffic website mulai masuk, namun belum ada konversi booking. Pastikan tombol WhatsApp mudah terlihat."
                ];
            }
        } else {
            $insights[] = [
                'icon' => 'heroicon-o-chart-bar',
                'color' => 'info',
                'title' => 'Belum Ada Traffic',
                'text' => "Website Anda siap digunakan. Mulailah sebarkan link showroom Anda ke media sosial untuk mendapatkan pengunjung pertama."
            ];
        }

        // 2. Source Insight
        $topSource = SiteLog::where('log_type', 'visit')
            ->select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->orderBy('total', 'desc')
            ->first();

        if ($topSource && $topSource->source) {
            $sourceName = ucfirst($topSource->source);
            $insights[] = [
                'icon' => 'heroicon-o-globe-alt',
                'color' => 'primary',
                'title' => 'Sumber Traffic Terbaik',
                'text' => "Sebagian besar pengunjung datang dari **{$sourceName}**. Disarankan untuk memfokuskan atau menambah budget promosi di platform tersebut."
            ];
        }

        // 3. Product Insight
        $topCar = TestDriveBooking::select('car_id', DB::raw('count(*) as total'))
            ->groupBy('car_id')
            ->orderBy('total', 'desc')
            ->with('car')
            ->first();

        if ($topCar && $topCar->car) {
            $insights[] = [
                'icon' => 'heroicon-o-star',
                'color' => 'warning',
                'title' => 'Unit Terpopuler',
                'text' => "Model **{$topCar->car->name}** menyumbang booking terbanyak. Pastikan unit test drive tersedia dan siapkan penawaran trade-in khusus untuk model ini."
            ];
        }

        return [
            'insights' => $insights
        ];
    }
}
