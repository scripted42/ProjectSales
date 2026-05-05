<?php

namespace App\Filament\Widgets;

use App\Models\SiteLog;
use App\Models\TestDriveBooking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalVisits = SiteLog::where('log_type', 'visit')->count();
        $totalWAClicks = SiteLog::where('log_type', 'wa_click')->count();
        $totalBookings = TestDriveBooking::count();
        $totalFollowedUp = TestDriveBooking::where('status', '!=', 'pending')->count();
        
        $conversionRate = $totalVisits > 0 ? round(($totalWAClicks + $totalBookings) / $totalVisits * 100, 1) : 0;

        return [
            Stat::make('Total Visitors', $totalVisits)
                ->description('Total traffic kunjungan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),
            Stat::make('Interaksi WA', $totalWAClicks)
                ->description('Klik tombol WhatsApp')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('success'),
            Stat::make('Test Drive', $totalBookings)
                ->description('Form booking terkumpul')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning'),
            Stat::make('Conversion Rate', $conversionRate . '%')
                ->description('Efektivitas Website')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('primary'),
            Stat::make('Follow-up Success', $totalFollowedUp)
                ->description('Booking yang diproses')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
