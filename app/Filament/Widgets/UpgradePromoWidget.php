<?php

namespace App\Filament\Widgets;

use App\Models\Setting;
use Filament\Widgets\Widget;

class UpgradePromoWidget extends Widget
{
    protected static string $view = 'filament.widgets.upgrade-promo-widget';
    protected static ?array $extraAttributes = [
        'class' => 'upgrade-promo-widget',
    ];
    protected static ?int $sort = 0; // Show at the very top
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return !Setting::isPro();
    }
}
