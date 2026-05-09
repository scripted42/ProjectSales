<?php

namespace App\Filament\Widgets;

use App\Models\SiteLog;
use App\Models\Setting;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class EchartsHeatmapWidget extends Widget
{
    protected static string $view = 'filament.widgets.echarts-heatmap';
    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Setting::isPro();
    }

    protected function getViewData(): array
    {
        // Fetch logs and group by Day of Week (0=Sun, 6=Sat) and Hour (0-23)
        // Note: SQLite strftime('%w', created_at) returns 0-6 (Sun-Sat)
        // SQLite strftime('%H', created_at) returns 00-23
        $logs = SiteLog::select(
                DB::raw("CAST(strftime('%w', created_at) AS INTEGER) as day_of_week"),
                DB::raw("CAST(strftime('%H', created_at) AS INTEGER) as hour_of_day"),
                DB::raw('count(*) as total')
            )
            ->groupBy('day_of_week', 'hour_of_day')
            ->get();

        $data = [];
        // Initialize 7 days * 24 hours with 0
        for ($d = 0; $d < 7; $d++) {
            for ($h = 0; $h < 24; $h++) {
                $data[$d][$h] = 0;
            }
        }

        foreach ($logs as $log) {
            if ($log->day_of_week !== null && $log->hour_of_day !== null) {
                $data[$log->day_of_week][$log->hour_of_day] = $log->total;
            }
        }

        // ECharts Heatmap format: [X(Hour), Y(Day), Value]
        // Days: 0=Sun ... 6=Sat
        $heatmapData = [];
        for ($d = 0; $d < 7; $d++) {
            for ($h = 0; $h < 24; $h++) {
                $heatmapData[] = [$h, $d, $data[$d][$h]];
            }
        }

        $maxValue = empty($heatmapData) ? 10 : max(array_column($heatmapData, 2));
        if ($maxValue == 0) $maxValue = 10; // Fallback

        return [
            'chartData' => json_encode($heatmapData),
            'maxValue' => $maxValue
        ];
    }
}
