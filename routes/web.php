<?php

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index']);
Route::get('/car/{car:slug}', [LandingPageController::class, 'show'])->name('car.show');

Route::get('/track-wa', function (\Illuminate\Http\Request $request) {
    \App\Models\SiteLog::create([
        'log_type' => 'wa_click',
        'car_id' => $request->car_id,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'created_at' => now(),
    ]);

    $phone = \App\Models\Consultant::first()->formatted_phone ?? '#';
    $text = $request->text ?? 'Halo, saya tertarik dengan mobil Hyundai.';
    
    return redirect("https://api.whatsapp.com/send/?phone={$phone}&text=" . urlencode($text));
})->name('track.wa');
