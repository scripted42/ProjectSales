<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MothershipStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Clients', Client::count())
                ->description('Registered domains')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('info'),
            Stat::make('Active Clients', Client::where('status', 'active')->count())
                ->description('Running normally')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Suspended Clients', Client::where('status', 'suspended')->count())
                ->description('Websites blocked')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
