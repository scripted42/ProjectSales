<?php

use App\Http\Controllers\LandingPageController;
use App\Livewire\OtpChallenge;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index']);
Route::get('/news', [LandingPageController::class, 'postsIndex'])->name('posts.index');
Route::get('/news/{post:slug}', [LandingPageController::class, 'postsShow'])->name('posts.show');
Route::get('/admin/otp', OtpChallenge::class)->name('filament.admin.auth.otp');
Route::get('/car/{car:slug}', [LandingPageController::class, 'show'])->name('car.show');

Route::get('/track-wa', function (\Illuminate\Http\Request $request) {
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
        'log_type' => 'wa_click',
        'source' => $source,
        'car_id' => $request->car_id,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'created_at' => now(),
    ]);

    $phone = \App\Models\Consultant::first()->formatted_phone ?? '#';
    $text = $request->text ?? 'Halo, saya tertarik dengan mobil Hyundai.';
    
    return redirect("https://api.whatsapp.com/send/?phone={$phone}&text=" . urlencode($text));
})->name('track.wa');

// Route khusus untuk hosting tanpa SSH (Hapus jika sudah di-upload ke cPanel dan dijalankan)
Route::get('/install-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "Database migrated successfully!<br><pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/storage-link', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        return "Storage link created successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

