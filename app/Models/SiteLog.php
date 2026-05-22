<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteLog extends Model
{
    protected $fillable = [
        'log_type',
        'source',
        'car_id',
        'ip_address',
        'user_agent',
        'region',
        'created_at',
    ];

    public $timestamps = false;

    public static function getRegionFromIp($ip)
    {
        if (!$ip) return 'Unknown';
        
        return cache()->remember("ip-loc-{$ip}", 86400 * 7, function () use ($ip) {
            if ($ip === '127.0.0.1' || $ip === '::1' || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return 'Localhost';
            }
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(2)->get("http://ip-api.com/json/{$ip}");
                if ($response->successful()) {
                    $data = $response->json();
                    if (($data['status'] ?? '') === 'success') {
                        return $data['regionName'] ?? $data['city'] ?? 'Unknown';
                    }
                }
            } catch (\Exception $e) {
                // Fallback silently
            }
            return 'Unknown';
        });
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}

