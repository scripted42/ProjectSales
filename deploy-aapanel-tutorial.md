# 🚀 Tutorial Deploy ProjectSalesCars di aaPanel

> **Stack**: Laravel 12 + Filament 3 + MySQL + Vite/TailwindCSS  
> **Repository Git**: `https://github.com/username/ProjectSales.git`
>
> *Catatan: Folder `mothership-app/` dan `manager/` yang ada di dalam repo tidak digunakan. Kita hanya perlu mengarahkan Nginx ke folder `public/` milik root app.*

---

## 📋 Daftar Isi

1. [Langkah 1: Persiapan Environment di aaPanel](#1-langkah-1-persiapan-environment-di-aapanel)
2. [Langkah 2: Konfigurasi PHP 8.2 (Sangat Penting!)](#2-langkah-2-konfigurasi-php-82-sangat-penting)
3. [Langkah 3: Tambah Website & Database Baru](#3-langkah-3-tambah-website--database-baru)
4. [Langkah 4: Deploy Source Code via Git](#4-langkah-4-deploy-source-code-via-git)
5. [Langkah 5: Konfigurasi File `.env`](#5-langkah-5-konfigurasi-file-env)
6. [Langkah 6: Jalankan Setup Aplikasi via Terminal SSH](#6-langkah-6-jalankan-setup-aplikasi-via-terminal-ssh)
7. [Langkah 7: Konfigurasi Website di aaPanel (Running Dir & Rewrite)](#7-langkah-7-konfigurasi-website-di-aapanel-running-dir--rewrite)
8. [Langkah 8: Setup SSL (HTTPS) Let's Encrypt](#8-langkah-8-setup-ssl-https-lets-encrypt)
9. [Langkah 9: Jalankan Route Setup via Browser](#9-langkah-9-jalankan-route-setup-via-browser)
10. [Langkah 10: Setup Queue Worker (Supervisor)](#10-langkah-10-setup-queue-worker-supervisor)
11. [Langkah 11: Checklist Pengujian & Troubleshooting](#11-langkah-11-checklist-pengujian--troubleshooting)
12. [Langkah 12: Cara Update Aplikasi (Redeploy)](#12-langkah-12-cara-update-aplikasi-redeploy)

---

## 1. Langkah 1: Persiapan Environment di aaPanel

Login ke panel aaPanel Anda menggunakan alamat URL panel, username, dan password panel Anda sendiri.

Buka menu **App Store** di sebelah kiri aaPanel, lalu cari dan install software berikut jika belum ada:

| Software | Versi Rekomendasi | Keterangan |
| :--- | :--- | :--- |
| **Nginx** | `1.22` atau `1.24` | Web server utama |
| **PHP** | **`8.2`** | Wajib sesuai kebutuhan Laravel 12 / Filament 3 |
| **MySQL** | `8.0` atau `5.7` | Database server |
| **Node.js Version Manager** | Terbaru | Untuk install Node.js & NPM (build assets) |
| **Supervisor Manager** | Terbaru | Untuk menjalankan queue worker di background |

### Install Node.js:
1. Setelah **Node.js Version Manager** terinstall, buka pengaturannya.
2. Install **Node.js LTS (Versi 18 atau 20)**.
3. Pastikan statusnya terpasang (Command line environment active).

---

## 2. Langkah 2: Konfigurasi PHP 8.2 (Sangat Penting!)

Secara default, aaPanel membatasi beberapa fungsi PHP yang dibutuhkan oleh Composer, Laravel, dan Filament untuk keamanan. Kita harus membukanya terlebih dahulu.

### 2a. Install PHP Extensions
1. Masuk ke **App Store** -> Cari **PHP-8.2** -> Klik **Settings**.
2. Pilih tab **Install extensions**.
3. Install extension berikut jika belum tercentang hijau:
   *   `intl` (Wajib untuk Filament)
   *   `zip` (Wajib untuk Composer)
   *   `fileinfo` (Wajib untuk upload gambar)
   *   `opcache` (Opsional, sangat bagus untuk performa production)

### 2b. Hapus Pembatasan Fungsi (Disable Functions)
Fungsi-fungsi ini wajib dihapus dari daftar hitam agar Composer dan command Artisan berjalan normal:
1. Masuk ke **App Store** -> **PHP-8.2** -> Klik **Settings**.
2. Pilih tab **Disabled functions**.
3. Cari dan hapus fungsi-fungsi berikut dari daftar (klik tombol **Del** di sebelahnya):
   *   `putenv`
   *   `proc_open`
   *   `shell_exec`
   *   `symlink`
   *   `passthru`
   *   `pcntl_alarm`
   *   `pcntl_signal`
4. Restart layanan PHP dengan memilih tab **Service** -> Klik **Restart**.

---

## 3. Langkah 3: Tambah Website & Database Baru

1. Masuk ke menu **Website** -> Klik **Add Site**.
2. Konfigurasikan seperti berikut:
   *   **Domain**: `domain-anda.com`
   *   **Create DB**: Pilih **MySQL**
       *   **Database Name**: `nama_database_anda`
       *   **Username**: `username_database_anda`
       *   **Password**: *(Tulis password yang kuat — **CATAT!**)*
   *   **PHP Version**: `PHP-82`
   *   **Site Category**: Default
3. Klik **Submit**.

---

## 4. Langkah 4: Deploy Source Code via Git

Kita akan meng-clone repository langsung ke folder direktori situs web Anda `/www/wwwroot/domain-anda.com`.

1. Buka menu **Terminal** di sebelah kiri aaPanel (atau masuk via SSH klien seperti PuTTY/Termius menggunakan IP VPS Anda).
2. Jalankan perintah berikut secara berurutan:

```bash
# Pindah ke direktori situs web Anda
cd /www/wwwroot/domain-anda.com

# Hapus file default bawaan aaPanel (seperti index.html, 404.html)
rm -rf *

# Clone repository langsung ke folder ini (tanda titik di akhir wajib)
git clone https://github.com/username/ProjectSales.git .
```

Jika repository bersifat privat dan Anda menggunakan HTTPS, masukkan username GitHub dan Personal Access Token (PAT) Anda sebagai password.

---

## 5. Langkah 5: Konfigurasi File `.env`

1. Masuk ke menu **Files** di aaPanel.
2. Buka folder `/www/wwwroot/domain-anda.com`.
3. Cari file bernama `.env.example`, klik kanan lalu pilih **Copy**, kemudian paste di tempat yang sama dengan nama `.env`.
4. Klik double file `.env` untuk mengeditnya, lalu sesuaikan konfigurasi database dan email seperti berikut:

```env
APP_NAME="Nama Aplikasi Anda"
APP_ENV=production
APP_KEY=                          # Biarkan kosong dulu, nanti di-generate otomatis
APP_DEBUG=false
APP_URL=https://domain-anda.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_MAINTENANCE_DRIVER=file

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

# ── Database Connection ───────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda       # Sesuai database yang dibuat di Langkah 3
DB_USERNAME=username_database_anda   # Sesuai username database yang dibuat di Langkah 3
DB_PASSWORD=PASSWORD_DATABASE_ANDA  # Sesuai password database yang dibuat di Langkah 3

# ── Session & Cache ───────────────────────
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
CACHE_STORE=database

# ── Email Settings ────────────────────────
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME="email-pengirim@gmail.com"
MAIL_PASSWORD="app-password-email-anda"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="email-pengirim@gmail.com"
MAIL_FROM_NAME="AutoShow Pro Security"

VITE_APP_NAME="${APP_NAME}"

# ── Developer Setup & Deployment ──────────
DEVELOPER_NAME="Nama Developer"
DEVELOPER_EMAIL="email-developer-anda@gmail.com"
DEVELOPER_PASSWORD="password-developer-anda"

DEVELOPER_OTP_ENABLED=false         # Matikan OTP di server baru agar bypass verifikasi email
DEVELOPER_OTP_STATIC=123456         # OTP statis alternatif jika dibutuhkan

DEPLOYMENT_KEY="kunci-rahasia-anda"  # Token keamanan untuk inisialisasi route
SEED_MOCK_DATA=false                # Set ke false untuk database bersih tanpa data demo

# ── AutoShow Real AI Settings ─────────────
OPENROUTER_API_KEY=""                # API Key default OpenRouter (opsional)
AI_DEFAULT_PROVIDER="openrouter"     # Pilihan: openrouter, deepseek, gemini, atau disabled
AI_DEFAULT_MODEL="qwen/qwen-2.5-7b-instruct:free"
```

5. Klik **Save** (Ctrl+S).

> [!WARNING]
> Pastikan `APP_DEBUG=false` untuk production agar informasi sensitif tidak bocor saat terjadi error di client browser.

---

## 6. Langkah 6: Jalankan Setup Aplikasi via Terminal SSH

Kembali ke **Terminal** aaPanel atau SSH, lalu jalankan perintah berikut untuk menginstall library PHP, membangun aset CSS/JS, dan mengatur hak akses folder:

```bash
cd /www/wwwroot/domain-anda.com

# 1. Install Composer dependencies tanpa package development
composer install --optimize-autoloader --no-dev

# 2. Generate Application Key Laravel
php artisan key:generate

# 3. Install dan Build Assets Frontend (Vite)
npm install
npm run build

# 4. Berikan Hak Akses (Permissions) ke Web Server (www)
chmod -R 775 storage bootstrap/cache
chown -R www:www storage bootstrap/cache public/

# 5. Aktifkan Cache Config & Route untuk mempercepat performa di production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

*Catatan: Jika perintah `php` atau `composer` tidak ditemukan atau salah versi, gunakan path absolut versi PHP 8.2 milik aaPanel:*
*   *Gunakan `/www/server/php/82/bin/php artisan ...` sebagai pengganti `php artisan ...`*
*   *Gunakan `/www/server/php/82/bin/php /usr/bin/composer install ...` jika composer bermasalah.*

---

## 7. Langkah 7: Konfigurasi Website di aaPanel (Running Dir & Rewrite)

Laravel memerlukan pengaturan khusus pada web server agar point directory-nya mengarah ke folder `public`.

### 7a. Ubah Running Directory ke `/public`
1. Buka menu **Website** -> Klik pada nama domain Anda (`domain-anda.com`).
2. Masuk ke tab **Site directory**.
3. Di bagian **Running directory**, pilih `/public` dari dropdown menu.
4. Klik **Save**.

### 7b. Konfigurasi URL Rewrite (Penting untuk routing Laravel)
1. Masuk ke tab **URL rewrite** di halaman pengaturan website yang sama.
2. Pilih preset **laravel5** dari dropdown list, atau copy-paste konfigurasi berikut ke editor teks:
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```
3. Klik **Save**.

---

## 8. Langkah 8: Setup SSL (HTTPS) Let's Encrypt

Sebelum mengaktifkan SSL, pastikan domain `domain-anda.com` dan `www.domain-anda.com` sudah mengarah (pointing A record) ke IP VPS Anda di DNS Manager domain Anda. Anda bisa memverifikasinya di [dnschecker.org](https://dnschecker.org).

Jika sudah terpointing dengan benar:
1. Buka menu **Website** -> Klik domain `domain-anda.com`.
2. Pilih tab **SSL**.
3. Pilih tab **Let's Encrypt**.
4. Centang nama domain Anda (`domain-anda.com` dan `www.domain-anda.com`).
5. Klik **Apply**.
6. Setelah SSL berhasil terpasang, aktifkan opsi **Force HTTPS** di pojok kanan atas pengaturan SSL tersebut.

---

## 9. Langkah 9: Jalankan Route Inisialisasi via Browser

Aplikasi ini sudah dilengkapi dengan satu route inisialisasi terpadu yang aman untuk mempermudah migrasi database, pembuatan link storage, dan pembersihan cache tanpa perlu mengetik command SSH secara manual.

Akses URL berikut pada web browser Anda:

`https://domain-anda.com/deploy/init?key=kunci-rahasia-anda`

*(Ganti `kunci-rahasia-anda` dengan nilai `DEPLOYMENT_KEY` yang Anda setel di berkas `.env`)*

### Hasil yang Diharapkan:
Layar akan menampilkan status inisialisasi:
1. **1. Database Migrated & Seeded Successfully!** (Membuat tabel database bersih dan mendaftarkan akun Developer Anda).
2. **2. Storage Link Re-created Successfully!** (Menghubungkan folder upload gambar/foto).
3. **3. Configuration & View Cache Cleared!** (Membersihkan sisa cache aplikasi).

### Akun Developer Default:
*   📧 **Email**: Sesuai dengan `DEVELOPER_EMAIL` di `.env` (default: `email-developer-anda@gmail.com`)
*   🔑 **Password**: Sesuai dengan `DEVELOPER_PASSWORD` di `.env` (default: `password-developer-anda`)
*   🎭 **Role**: `developer`

Akses panel admin di: `https://domain-anda.com/admin` dan masuk menggunakan akun Developer tersebut. Segera ganti password dan email admin default ini di halaman pengaturan profil setelah Anda login pertama kali!

---

## 10. Langkah 10: Setup Queue Worker (Supervisor)

Karena di dalam file `.env` kita menggunakan `QUEUE_CONNECTION=database`, antrian pengiriman email dan background jobs lainnya tidak akan berjalan otomatis sebelum kita mengaktifkan Queue Worker.

1. Buka menu **App Store** di aaPanel -> Buka pengaturan **Supervisor Manager**.
2. Klik tombol **Add Daemon** (Tambah Proses).
3. Isi konfigurasi daemon seperti berikut:
   *   **Name**: `hyundai-queue`
   *   **Run User**: `www`
   *   **Run Dir**: `/www/wwwroot/domain-anda.com`
   *   **Start Command**: `/www/server/php/82/bin/php artisan queue:work --sleep=3 --tries=3 --max-time=3600`
   *   **Processes**: `1`
4. Klik **Confirm**.
5. Pastikan status daemon tersebut adalah **Running** (Ikon hijau menyala).

---

## 11. Langkah 11: Checklist Pengujian & Troubleshooting

### Checklist Pengujian Utama:
*   [ ] Akses `https://domain-anda.com` apakah halaman landing page terbuka sempurna dan responsif.
*   [ ] Cek ikon gembok SSL (HTTPS) apakah sudah terverifikasi aman.
*   [ ] Periksa seluruh asset (CSS/JS) termuat dengan benar (halaman tidak terlihat hancur/blank).
*   [ ] Masuk ke menu detail mobil (misal `/car/{slug}`) dan pastikan data dari database tampil.
*   [ ] Uji form kontak (lead input) dan pastikan pengiriman data berhasil.
*   [ ] Uji fitur tombol WhatsApp redirect (`/track-wa`) berfungsi mengarah ke nomor sales.
*   [ ] Buka `/admin`, coba login, dan tes upload gambar pada salah satu produk mobil (untuk memastikan symlink folder storage berjalan).

### Troubleshooting Umum:

#### ❌ Error 500 / Halaman Blank
Periksa log error Laravel untuk mengetahui penyebab pastinya:
```bash
tail -n 100 /www/wwwroot/domain-anda.com/storage/logs/laravel.log
```
Or clear cache manual jika ada perubahan file konfigurasi:
```bash
# Mengosongkan cache aplikasi via SSH
/www/server/php/82/bin/php artisan optimize:clear
```

#### ❌ CSS/JS Tidak Termuat (Halaman Blank/Polos Tanpa Style)
Penyebabnya adalah folder build asset Vite belum dibuat atau gagal terkompilasi.
Solusi: Jalankan kompilasi ulang melalui terminal VPS:
```bash
cd /www/wwwroot/domain-anda.com
npm install
npm run build
```

#### ❌ Gambar Produk Tidak Muncul
Pastikan symbolic link storage sudah benar. Jalankan via SSH:
```bash
cd /www/wwwroot/domain-anda.com
rm -rf public/storage
/www/server/php/82/bin/php artisan storage:link
```

---

## 12. Langkah 12: Cara Update Aplikasi (Redeploy)

Jika di kemudian hari ada pembaruan kode aplikasi yang sudah di-push ke GitHub, Anda dapat melakukan update aplikasi secara cepat melalui langkah ini:

1. Buka **Terminal** di aaPanel atau login via SSH.
2. Jalankan perintah berikut:

```bash
cd /www/wwwroot/domain-anda.com

# 1. Aktifkan Mode Maintenance agar user tidak mengakses web selama update
/www/server/php/82/bin/php artisan down

# 2. Tarik kode terbaru dari GitHub
git pull origin main

# 3. Update library PHP jika ada perubahan di composer.json
composer install --optimize-autoloader --no-dev

# 4. Build ulang file frontend (jika ada perubahan file CSS/JS/Vite)
npm run build

# 5. Jalankan migrasi database baru jika ada tambahan tabel/kolom
/www/server/php/82/bin/php artisan migrate --force

# 6. Bersihkan dan bangun kembali cache config/routes
/www/server/php/82/bin/php artisan optimize

# 7. Matikan Mode Maintenance (Website kembali online)
/www/server/php/82/bin/php artisan up
```

Or setelah melakukan `git pull`, Anda juga dapat membuka URL inisialisasi terpadu di browser untuk menyegarkan database dan cache:
*   `https://domain-anda.com/deploy/init?key=kunci-rahasia-anda`

---

*Panduan deployment aaPanel ini dirancang khusus untuk Project **ProjectSalesCars**.*  
*Jika menemui kendala di tengah jalan, mohon periksa file log error di `/www/wwwroot/domain-anda.com/storage/logs/laravel.log`.*
