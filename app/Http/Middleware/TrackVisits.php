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
            \App\Models\SiteLog::create([
                'log_type' => 'visit',
                'car_id' => $request->route('car') ? $request->route('car')->id : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        }
        
        return $next($request);
    }
}
