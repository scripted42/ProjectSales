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
        return [
            'plan' => self::where('key', 'license_plan')->first()?->value ?? 'regular',
            'expired_at' => self::where('key', 'license_expiry')->first()?->value ?? '2026-12-31',
            'is_suspended' => (bool) self::where('key', 'is_suspended')->first()?->value,
            'status' => 'local'
        ];
    }

    public static function isPro(): bool
    {
        // Super Developer bypass
        if (auth()->check() && auth()->user()->role === 'developer') {
            return true;
        }

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
