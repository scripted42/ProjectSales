<?php

use App\Http\Controllers\LandingPageController;
use App\Livewire\OtpChallenge;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

Route::get('/', [LandingPageController::class, 'index']);
Route::get('/news', [LandingPageController::class, 'postsIndex'])->name('posts.index');
Route::get('/news/{post:slug}', [LandingPageController::class, 'postsShow'])->name('posts.show');
Route::get('/admin/otp', OtpChallenge::class)->name('filament.admin.auth.otp');
Route::get('/car/{car:slug}', [LandingPageController::class, 'show'])->name('car.show');
Route::get('/pricelist', [LandingPageController::class, 'pricelist'])->name('pricelist');

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

    $phone = \App\Models\Consultant::first()?->formatted_phone ?? '#';
    $text = $request->text ?? 'Halo, saya tertarik dengan mobil Hyundai.';
    
    return redirect("https://api.whatsapp.com/send/?phone={$phone}&text=" . urlencode($text));
})->name('track.wa');

// ==========================================
// SECURE SYSTEM DEPLOYMENT UTILITIES
// ==========================================

Route::get('/deploy/init', function (\Illuminate\Http\Request $request) {
    $secureKey = env('DEPLOYMENT_KEY');
    if (!$secureKey || $request->get('key') !== $secureKey) {
        abort(403, 'Unauthorized deployment key.');
    }

    try {
        // 1. Run migrations & seeders
        Artisan::call('migrate', ['--force' => true, '--seed' => true]);
        $output = "1. Database Migrated & Seeded Successfully!<br>";

        // 2. Fix storage link
        $link = public_path('storage');
        if (file_exists($link) || is_link($link)) {
            @unlink($link);
        }
        try {
            Artisan::call('storage:link');
        } catch (\Exception $ex) {
            // Symlink fallback if Artisan call fails
            @symlink(storage_path('app/public'), $link);
        }
        $output .= "2. Storage Link Re-created Successfully!<br>";

        // 3. Clear all cache/optimized files
        Artisan::call('optimize:clear');
        $output .= "3. Configuration & View Cache Cleared!<br>";

        return "<h3>AutoShow Deployment Tool</h3><p style='color:green'>{$output}</p><p>Aplikasi siap digunakan. Silakan login ke <a href='/admin/login'>Dashboard Admin</a> menggunakan user Developer.</p>";
    } catch (\Exception $e) {
        return "<h3>AutoShow Deployment Tool</h3><p style='color:red'>Error during deployment: " . $e->getMessage() . "</p>";
    }
});
