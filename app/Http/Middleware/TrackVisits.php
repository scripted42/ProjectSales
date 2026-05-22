<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiteLog;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // HANYA hitung jika akses halaman publik (BUKAN admin, BUKAN ajax, BUKAN livewire)
        if (!$request->is('admin*') && !$request->ajax() && !$request->hasHeader('X-Livewire')) {
            
            $referer = $request->header('referer');
            $source = 'direct';
            
            if ($request->has('utm_source')) {
                $source = $request->utm_source;
            } elseif ($referer) {
                if (str_contains($referer, 'google.com')) $source = 'google';
                elseif (str_contains($referer, 'facebook.com')) $source = 'facebook';
                elseif (str_contains($referer, 'instagram.com')) $source = 'instagram';
                elseif (str_contains($referer, 'tiktok.com')) $source = 'tiktok';
            }

            $ip = $request->ip();
            $region = SiteLog::getRegionFromIp($ip);
            
            // Skip logging for foreign visitors (bots/VPNs outside target ID market)
            if ($region === 'Foreign') {
                return $next($request);
            }
            
            SiteLog::create([
                'log_type' => 'visit',
                'source' => $source,
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'region' => $region,
                'created_at' => now(),
            ]);
        }

        return $next($request);
    }
}
