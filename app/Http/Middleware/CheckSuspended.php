<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    public function handle(Request $request, Closure $next): Response
    {
        // Don't block API or Admin login/logout during suspension
        // Also don't block Super Developers
        if ($request->is('api/*') || $request->is('admin/login') || $request->is('admin/logout') || (auth()->check() && auth()->user()->role === 'developer')) {
            return $next($request);
        }

        $isSuspended = Setting::where('key', 'is_suspended')->first()?->value === '1';

        if ($isSuspended) {
            // Show a custom suspension view or simple message
            return response()->view('errors.suspended', [], 403);
        }

        return $next($request);
    }
}
