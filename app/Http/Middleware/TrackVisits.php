<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't track admin or ajax/livewire requests
        if (!$request->is('admin*') && !$request->ajax()) {
            $carId = $request->route('car') ? $request->route('car')->id : 'home';
            $sessionKey = 'logged_visit_' . $carId;

            // Only log if not already logged in this session
            if (!session()->has($sessionKey)) {
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

                \App\Models\SiteLog::create([
                    'log_type' => 'visit',
                    'source' => $source,
                    'car_id' => is_numeric($carId) ? $carId : null,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                ]);

                session()->put($sessionKey, true);
            }
        }
        
        return $next($request);
    }
}
