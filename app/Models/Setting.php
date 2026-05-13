<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'is_suspended', 'mothership_token'];

    public static function getLicenseData(): array
    {
        $plan = self::where('key', 'license_plan')->first()?->value ?? 'regular';
        
        // Prioritaskan paket dari user yang sedang login
        if (auth()->check()) {
            $plan = auth()->user()->plan ?? $plan;
        }

        return [
            'plan' => strtolower($plan),
            'expired_at' => self::where('key', 'license_expiry')->first()?->value ?? '2026-12-31',
            'is_suspended' => (bool) self::where('key', 'is_suspended')->first()?->value,
            'status' => 'standalone'
        ];
    }

    public static function isPro(): bool
    {
        // Super Developer always has Pro features
        if (auth()->check() && auth()->user()->role === 'developer') {
            return true;
        }

        // If user is logged in, check their specific plan
        if (auth()->check()) {
            return auth()->user()->plan === 'pro';
        }

        // For public/guest (if applicable), fallback to global setting
        $data = self::getLicenseData();
        return ($data['plan'] ?? 'regular') === 'pro';
    }

    public static function isLicenseActive(): bool
    {
        $data = self::getLicenseData();
        $expiry = \Illuminate\Support\Carbon::parse($data['expired_at'] ?? '2000-01-01');
        return $expiry->isFuture();
    }
}
