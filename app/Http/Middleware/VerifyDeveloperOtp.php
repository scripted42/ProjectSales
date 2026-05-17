<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyDeveloperOtp
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user && $user->role === 'developer') {
            if (!$request->is('admin/otp') && !$request->is('livewire/*') && !$request->is('admin/logout')) {
                if (!session('otp_verified')) {
                    if (!$user->otp_code || now()->greaterThan($user->otp_expires_at)) {
                        $otp = sprintf("%06d", mt_rand(1, 999999));
                        $user->update([
                            'otp_code' => $otp,
                            'otp_expires_at' => now()->addMinutes(5),
                        ]);
                        \Illuminate\Support\Facades\Mail::to('wahyukurniawan101630@gmail.com')->send(new \App\Mail\DeveloperOtpMail($otp));
                    }
                    return redirect('/admin/otp');
                }
            }
        }

        return $next($request);
    }
}
