<?php

namespace App\Filament\Widgets;

use App\Models\SiteLog;
use App\Models\TestDriveBooking;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Aktivitas Penjualan (30 Hari Terakhir)';
    protected static string $color = 'info';

    protected function getData(): array
    {
        $days = collect(range(29, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));

        $visits = SiteLog::where('log_type', 'visit')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        $waClicks = SiteLog::where('log_type', 'wa_click')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        $bookings = TestDriveBooking::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Pengunjung',
                    'data' => $days->map(fn ($date) => $visits[$date] ?? 0)->toArray(),
                    'borderColor' => '#3b82f6',
                ],
                [
                    'label' => 'Klik WhatsApp',
                    'data' => $days->map(fn ($date) => $waClicks[$date] ?? 0)->toArray(),
                    'borderColor' => '#10b981',
                ],
                [
                    'label' => 'Booking Test Drive',
                    'data' => $days->map(fn ($date) => $bookings[$date] ?? 0)->toArray(),
                    'borderColor' => '#f59e0b',
                ],
            ],
            'labels' => $days->map(fn ($date) => Carbon::parse($date)->format('d M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
