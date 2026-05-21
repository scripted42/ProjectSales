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

    $phone = \App\Models\Consultant::first()->formatted_phone ?? '#';
    $text = $request->text ?? 'Halo, saya tertarik dengan mobil Hyundai.';
    
    return redirect("https://api.whatsapp.com/send/?phone={$phone}&text=" . urlencode($text));
})->name('track.wa');

// ==========================================
// UTILITY ROUTES (FOR HOSTING WITHOUT SSH)
// ==========================================

// 1. Menjalankan Migrasi Database
Route::get('/install-db', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return "Database migrated successfully!<br><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// 2. Membuat Akun Developer Utama (Akses Full)
Route::get('/buat-admin', function () {
    $user = User::updateOrCreate(
        ['email' => 'admin@showroom.com'],
        [
            'name' => 'Admin Developer',
            'password' => Hash::make('password'),
            'role' => 'developer', 
            'plan' => 'pro',       
            'email_verified_at' => now(),
        ]
    );
    return "User Developer Siap! Email: admin@showroom.com | Password: password";
});

// Route untuk menyalin data dari database.sqlite ke MySQL
Route::get('/import-sqlite', function () {
    try {
        $sqlitePath = database_path('database.sqlite');
        if (!file_exists($sqlitePath)) {
            return "File database.sqlite tidak ditemukan di folder database/ di server VPS Anda. Silakan upload terlebih dahulu menggunakan aaPanel File Manager.";
        }

        // Connect to SQLite
        $sqlite = new \PDO("sqlite:" . $sqlitePath);
        $sqlite->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // List tables to copy
        $tables = [
            'users',
            'cars',
            'consultants',
            'galleries',
            'videos',
            'promos',
            'leads',
            'test_drive_bookings',
            'site_logs',
            'settings',
            'posts'
        ];

        $output = "";

        // Truncate tables in MySQL and copy data
        foreach ($tables as $table) {
            // Get data from SQLite
            $stmt = $sqlite->query("SELECT * FROM {$table}");
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($rows)) {
                $output .= "Tabel {$table}: Kosong, dilewati.<br>";
                continue;
            }

            // Truncate MySQL table
            \DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            \DB::table($table)->truncate();
            \DB::statement("SET FOREIGN_KEY_CHECKS=1;");

            // Get columns of the MySQL table
            $mysqlColumns = array_flip(\Illuminate\Support\Facades\Schema::getColumnListing($table));

            // Insert into MySQL
            foreach ($rows as $row) {
                // Filter row keys based on MySQL table columns
                $filteredRow = array_intersect_key($row, $mysqlColumns);
                \DB::table($table)->insert($filteredRow);
            }

            $output .= "Tabel {$table}: Sukses menyalin " . count($rows) . " data.<br>";
        }

        return "<strong>Sukses Migrasi Data SQLite ke MySQL!</strong><br><br>" . $output;
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Route untuk mengimpor database MySQL lokal (database_backup.sql) ke MySQL VPS
Route::get('/import-sql', function (\Illuminate\Http\Request $request) {
    if ($request->get('key') !== 'wahyu') {
        abort(404);
    }
    try {
        $sqlPath = base_path('database_backup.sql');
        if (!file_exists($sqlPath)) {
            return "File database_backup.sql tidak ditemukan di base path server VPS.";
        }
        
        // Nonaktifkan foreign key checks selama import
        \DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        
        // Jalankan perintah SQL dari file backup
        \DB::unprepared(file_get_contents($sqlPath));
        
        // Aktifkan kembali foreign key checks
        \DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        
        return "Sukses Migrasi Database MySQL Lokal ke VPS!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Route untuk mengekstrak media/gambar (storage_public.zip) ke storage/app/public/ VPS
Route::get('/extract-storage', function (\Illuminate\Http\Request $request) {
    if ($request->get('key') !== 'wahyu') {
        abort(404);
    }
    try {
        $zipPath = base_path('storage_public.zip');
        if (!file_exists($zipPath)) {
            return "File storage_public.zip tidak ditemukan di base path server VPS.";
        }
        
        $zip = new \ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo(storage_path('app/public'));
            $zip->close();
            return "Sukses mengekstrak semua media/gambar ke storage VPS!";
        } else {
            return "Gagal membuka file zip.";
        }
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Route bantu diagnosa storage untuk memeriksa apakah media terekstrak & symlink berjalan
Route::get('/check-storage', function (\Illuminate\Http\Request $request) {
    if ($request->get('key') !== 'wahyu') {
        abort(404);
    }
    
    $diagnostics = [];
    
    $zipPath = base_path('storage_public.zip');
    $diagnostics['zip_exists'] = file_exists($zipPath) ? 'Yes' : 'No';
    if (file_exists($zipPath)) {
        $diagnostics['zip_size'] = number_format(filesize($zipPath)) . ' bytes';
    }
    
    $storagePublicPath = storage_path('app/public');
    $diagnostics['storage_public_exists'] = file_exists($storagePublicPath) ? 'Yes' : 'No';
    if (file_exists($storagePublicPath)) {
        $files = scandir($storagePublicPath);
        $diagnostics['storage_public_contents'] = array_diff($files, ['.', '..']);
        
        $carsPath = $storagePublicPath . '/cars';
        if (file_exists($carsPath)) {
            $carFiles = scandir($carsPath);
            $diagnostics['cars_contents_count'] = count(array_diff($carFiles, ['.', '..']));
            $diagnostics['cars_sample'] = array_slice(array_values(array_diff($carFiles, ['.', '..'])), 0, 5);
        } else {
            $diagnostics['cars_directory'] = 'Not found under storage/app/public';
        }
    }
    
    $publicStoragePath = public_path('storage');
    $diagnostics['public_storage_exists'] = file_exists($publicStoragePath) ? 'Yes' : 'No';
    $diagnostics['public_storage_is_link'] = is_link($publicStoragePath) ? 'Yes' : 'No';
    if (is_link($publicStoragePath)) {
        $diagnostics['public_storage_link_target'] = readlink($publicStoragePath);
    } elseif (file_exists($publicStoragePath)) {
        $diagnostics['public_storage_is_dir'] = is_dir($publicStoragePath) ? 'Yes' : 'No';
    }
    
    return response()->json($diagnostics);
});

// Route bantu untuk melihat kode OTP developer jika email tidak masuk/terblokir (akses rahasia menggunakan ?key=wahyu)
Route::get('/show-otp', function (\Illuminate\Http\Request $request) {
    if ($request->get('key') !== 'wahyu') {
        abort(404);
    }
    $user = \App\Models\User::where('role', 'developer')->first();
    if ($user) {
        return "Kode OTP untuk {$user->email} adalah: <strong>{$user->otp_code}</strong> (Kadaluarsa pada: {$user->otp_expires_at})";
    }
    return "User developer tidak ditemukan.";
});

// 3. Membersihkan Cache & Hapus Sisa Menu Lama
Route::get('/storage-link', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    if (file_exists($link)) {
        return "The [public/storage] directory already exists.";
    }
    app('files')->link($target, $link);
    return "The [public/storage] directory has been linked.";
});

Route::get('/clear-all', function() {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    
    // Paksa hapus file SystemSettings di hosting jika masih ada
    $file1 = app_path('Filament/Pages/SystemSettings.php');
    $file2 = resource_path('views/filament/pages/system-settings.blade.php');
    
    if (file_exists($file1)) @unlink($file1);
    if (file_exists($file2)) @unlink($file2);
    
    return "Semua cache berhasil dibersihkan dan menu lama telah dihapus!";
});

// 4. Memperbaiki Storage Link
Route::get('/fix-storage', function () {
    try {
        Artisan::call('storage:link');
        return "Storage link created successfully!";
    } catch (\Exception $e) {
        return "Error Storage: " . $e->getMessage();
    }
});
