<?php

namespace App\Filament\Widgets;

use App\Models\Setting;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class LicenseStatusWidget extends Widget
{
    protected static string $view = 'filament.widgets.license-status-widget';
    
    protected static ?int $sort = -1; // Show at the very top, before stats

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->role === 'developer';
    }

    public function getLicenseInfo(): array
    {
        $data = Setting::getLicenseData();
        $expiry = Carbon::parse($data['expired_at'] ?? now());
        $now = now();
        
        $isPro = Setting::isPro();
        $isExpired = $expiry->isPast();
        $daysLeft = (int) $now->diffInDays($expiry, false);
        $isNearExpiry = !$isExpired && $daysLeft <= 7;

        return [
            'plan' => $data['plan'] ?? 'regular',
            'is_pro' => $isPro,
            'expired_at' => $expiry->format('d M Y'),
            'is_expired' => $isExpired,
            'is_near_expiry' => $isNearExpiry,
            'days_left' => $daysLeft,
            'status_label' => $isExpired ? 'EXPIRED' : ($isNearExpiry ? 'NEAR EXPIRY' : 'ACTIVE'),
            'status_color' => $isExpired ? 'danger' : ($isNearExpiry ? 'warning' : 'success'),
        ];
    }
}
