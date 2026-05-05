<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use App\Models\SiteLog;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends Widget
{
    protected static string $view = 'filament.widgets.top-products-widget';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $topViews = SiteLog::where('log_type', 'visit')
            ->whereNotNull('car_id')
            ->select('car_id', DB::raw('count(*) as total'))
            ->groupBy('car_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $topInteractions = SiteLog::whereIn('log_type', ['wa_click', 'test_drive'])
            ->whereNotNull('car_id')
            ->select('car_id', DB::raw('count(*) as total'))
            ->groupBy('car_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return [
            'topViews' => $topViews,
            'topInteractions' => $topInteractions,
        ];
    }
}
