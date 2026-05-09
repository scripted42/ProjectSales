<?php

namespace App\Filament\Pages;

use App\Models\Client;
use Filament\Pages\Page;
use App\Filament\Widgets\MothershipStatsWidget;

class MothershipMonitoring extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'Monitoring Center';
    protected static ?string $navigationGroup = 'SaaS Management';
    protected static string $view = 'filament.pages.mothership-monitoring';

    protected function getHeaderWidgets(): array
    {
        return [
            MothershipStatsWidget::class,
        ];
    }

    public function getClients()
    {
        return Client::all();
    }
}
