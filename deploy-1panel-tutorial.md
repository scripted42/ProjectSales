# 🚀 Tutorial Deploy ProjectSalesCars ke 1Panel

> **Stack**: Laravel 12 + Filament 3 + MySQL + Vite/TailwindCSS  
> **Repo**: [github.com/username/ProjectSales](https://github.com/username/ProjectSales)  
>
> **⚠️ Penting**: Folder `mothership-app/` dan `manager/` masih ada di repo tapi **sudah tidak digunakan**.  
> Cukup clone seluruh repo, arahkan Nginx ke folder `public/` milik root app saja.

---

## 📋 Daftar Isi

1. [Prasyarat](#1-prasyarat)
2. [Setup Database & Website di 1Panel](#2-setup-database--website-di-1panel)
3. [Clone dari GitHub ke Server](#3-clone-dari-github-ke-server)
4. [Konfigurasi File `.env`](#4-konfigurasi-file-env)
5. [Setup Aplikasi via SSH](#5-setup-aplikasi-via-ssh)
6. [Konfigurasi Nginx](#6-konfigurasi-nginx)
7. [Setup SSL Let's Encrypt](#7-setup-ssl-lets-encrypt)
8. [Setup Selesai — Akses via Browser](#8-setup-selesai--akses-via-browser)
9. [Konfigurasi Queue Worker](#9-konfigurasi-queue-worker)
10. [Checklist Final](#10-checklist-final)
11. [Troubleshooting](#11-troubleshooting)
12. [Update Aplikasi (Redeploy)](#12-update-aplikasi-redeploy)

---

## 1. Prasyarat

### Install via 1Panel → App Store

| Aplikasi | Versi | Keterangan |
|----------|-------|------------|
| **OpenResty / Nginx** | 1.21+ | Web server |
| **PHP** | **8.2** | Wajib sesuai `composer.json` |
| **MySQL** | 8.0+ | Database |
| **Composer** | 2.x | PHP dependency manager |
| **Node.js** | 18+ | Build assets Vite |
| **Git** | latest | Clone repo |

### PHP Extensions yang Wajib Aktif
Di **1Panel → App Store → PHP 8.2 → Extensions**:

| Extension | Keterangan |
|-----------|------------|
| `pdo_mysql` | Koneksi MySQL |
| `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo` | Standard Laravel |
| `intl` | **Wajib** — ada di `composer.json` |
| `zip` | **Wajib** — ada di `composer.json` |
| `gd` | Manipulasi gambar |

---

## 2. Setup Database & Website di 1Panel

### 2a. Buat Database MySQL

1. **1Panel → Database → MySQL → Tambah Database**
2. Isi:
   - **Nama Database**: `nama_database_anda`
   - **Username**: `username_database_anda`
   - **Password**: *(buat password kuat — **catat!**)*
3. Klik Simpan

### 2b. Buat Website

1. **1Panel → Website → Situs Web → Buat Situs**
2. Isi:
   - **Tipe**: PHP
   - **Domain**: `domain-anda.com`
   - **Alias**: `www.domain-anda.com`
   - **PHP Version**: **8.2**
   - **Root Directory**: biarkan default (misal `/www/wwwroot/domain-anda.com`)
3. Klik Simpan — **catat path direktorinya**

> [!NOTE]
> 1Panel akan membuat folder beserta file placeholder `index.html`. Kita akan menggantinya dengan clone dari GitHub.

---

## 3. Clone dari GitHub ke Server

Buka **1Panel → Terminal** atau masuk via **SSH**:

```bash
# Pergi ke direktori website
cd /www/wwwroot/domain-anda.com

# Hapus semua file placeholder default dari 1Panel
rm -rf *

# Clone repo (titik di akhir = clone langsung ke folder ini)
git clone https://github.com/username/ProjectSales.git .
```

Setelah clone, struktur folder akan seperti ini:
```
/www/wwwroot/domain-anda.com/
├── app/               ← Kode utama Laravel
├── bootstrap/
├── config/
├── database/
├── manager/           ← Ada tapi tidak digunakan, abaikan
├── mothership-app/    ← Ada tapi tidak digunakan, abaikan
├── public/            ← ⬅ INI yang jadi Document Root Nginx
│   └── index.php
├── resources/
├── routes/
├── storage/
├── composer.json
├── package.json
└── vite.config.js
```

> [!IMPORTANT]
> Nginx harus diarahkan ke `/www/wwwroot/domain-anda.com/public/` — bukan ke root folder. Ini langkah paling kritis!

---

## 4. Konfigurasi File `.env`

```bash
# Salin template .env
cp .env.example .env
```

Edit file `.env` (bisa via **1Panel → File Manager** klik `.env` → Edit):

```env
APP_NAME="Nama Aplikasi Anda"
APP_ENV=production
APP_KEY=                          # Kosongkan — akan di-generate otomatis
APP_DEBUG=false
APP_URL=https://domain-anda.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# ── Database ──────────────────────────────
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=username_database_anda   # ← sesuaikan
DB_PASSWORD=PASSWORD_DATABASE_ANDA    # ← isi password yang dibuat tadi

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

# ── Email ─────────────────────────────────
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
```

> [!WARNING]
> `APP_DEBUG=false` dan `LOG_LEVEL=error` **wajib** di production. Jika debug=true, data sensitif bisa bocor ke browser!

---

## 5. Setup Aplikasi via SSH

Jalankan semua perintah berikut:

```bash
cd /www/wwwroot/domain-anda.com

# 1. Install PHP dependencies (skip dev packages)
composer install --optimize-autoloader --no-dev

# 2. Generate App Key
php artisan key:generate

# 3. Build assets CSS & JS (Vite)
npm install
npm run build

# 4. Set file permissions
chmod -R 775 storage bootstrap/cache
chown -R www:www storage bootstrap/cache public/

# 5. Optimasi production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> [!NOTE]
> Jika `php` tidak ditemukan, coba path lengkap:  
> `/www/server/php/82/bin/php artisan key:generate`

---

## 6. Konfigurasi Nginx

Di **1Panel → Website → domain-anda.com → Konfigurasi → Nginx Config**:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name domain-anda.com www.domain-anda.com;
    # Redirect semua HTTP ke HTTPS
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name domain-anda.com www.domain-anda.com;

    # SSL — diisi otomatis oleh 1Panel setelah Let's Encrypt aktif
    ssl_certificate /etc/letsencrypt/live/domain-anda.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/domain-anda.com/privkey.pem;

    # ⬇ Root WAJIB mengarah ke /public — bukan root project!
    root /www/wwwroot/domain-anda.com/public;
    index index.php;

    charset utf-8;
    client_max_body_size 50M;

    # Kompresi gzip
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml image/svg+xml;

    # Laravel URL rewriting
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM handler
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_read_timeout 300;
    }

    # Cache static assets (output Vite)
    location ~* \.(jpg|jpeg|gif|png|css|js|ico|webp|svg|woff|woff2|ttf)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Blokir akses file tersembunyi & .env
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Logs
    access_log /www/wwwlogs/domain-anda.com.log;
    error_log /www/wwwlogs/domain-anda.com.error.log;
}
```

> [!CAUTION]
> Pastikan baris `root` mengarah ke `…/public` bukan `…/domain-anda.com`. Salah satu ini adalah penyebab Error 500 terbanyak!

---

## 7. Setup SSL Let's Encrypt

1. **1Panel → Website → domain-anda.com → HTTPS**
2. Pilih **Let's Encrypt**
3. Centang domain:
   - ✅ `domain-anda.com`
   - ✅ `www.domain-anda.com`
4. Klik **Konfirmasi** → tunggu 1–2 menit
5. Aktifkan **Force HTTPS**

> [!IMPORTANT]
> DNS domain **harus sudah mengarah ke IP server** sebelum generate SSL.  
> Cek di: [dnschecker.org](https://dnschecker.org)

---

## 8. Setup Selesai — Akses via Browser

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

## 9. Konfigurasi Queue Worker

Karena `QUEUE_CONNECTION=database`, queue worker harus aktif untuk memproses email dan background jobs.

### Via Supervisor (1Panel → App Store → Supervisor)

Buat program baru dengan konfigurasi:

```ini
[program:hyundai-queue]
process_name=%(program_name)s_%(process_num)02d
command=/usr/bin/php /www/wwwroot/domain-anda.com/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www
numprocs=1
redirect_stderr=true
stdout_logfile=/www/wwwlogs/hyundai-queue.log
stopwaitsecs=3600
```

Klik **Simpan → Start**.

---

## 10. Checklist Final

### Halaman & Navigasi
- [ ] `https://domain-anda.com` — landing page tampil sempurna
- [ ] SSL aktif — gembok hijau di browser
- [ ] CSS/JS assets termuat (tidak blank/polos)
- [ ] `/news` — halaman artikel tampil
- [ ] `/car/{slug}` — halaman detail mobil tampil
- [ ] `/pricelist` — halaman pricelist tampil

### Fungsionalitas
- [ ] Form lead/kontak berhasil disubmit
- [ ] Email diterima setelah submit form
- [ ] WhatsApp redirect (`/track-wa`) berfungsi
- [ ] Upload gambar di admin berfungsi

### Admin Panel
- [ ] `https://domain-anda.com/admin` terbuka
- [ ] Login admin berhasil
- [ ] Dashboard menampilkan data
- [ ] Password sudah diganti dari default

### Server
- [ ] Queue worker berjalan di Supervisor
- [ ] Log bersih: `tail -f /www/wwwlogs/domain-anda.com.error.log`
- [ ] Storage symlink aktif: `ls -la /www/wwwroot/domain-anda.com/public/storage`

---

## 11. Troubleshooting

### ❌ Error 500 / Blank Page
```bash
# Lihat log detail
tail -n 100 /www/wwwroot/domain-anda.com/storage/logs/laravel.log

# Clear cache via SSH:
php artisan optimize:clear
```

### ❌ Halaman tampil tapi CSS/JS tidak muncul (polos)
```bash
# Assets belum di-build
cd /www/wwwroot/domain-anda.com
npm install && npm run build

# Cek folder ada
ls public/build/
```

### ❌ Gambar tidak tampil
```bash
# Via SSH:
php artisan storage:link
chmod -R 775 storage/
chown -R www:www storage/
```

### ❌ "No application encryption key"
```bash
php artisan key:generate
```

### ❌ Error koneksi database
- Periksa nilai `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` di `.env`
- Test: `php artisan tinker` → ketik `DB::connection()->getPdo();`

### ❌ PHP-FPM socket tidak ditemukan
```bash
# Cari path socket PHP yang benar
find /run /var/run -name "*.sock" | grep php
```
Sesuaikan di Nginx config: `fastcgi_pass unix:/path/yang/benar/php8.2-fpm.sock;`

### ❌ Permission denied
```bash
chmod -R 775 storage/ bootstrap/cache/
chown -R www:www storage/ bootstrap/cache/
```

---

## 12. Update Aplikasi (Redeploy)

Setiap ada perubahan yang sudah di-push ke GitHub:

```bash
cd /www/wwwroot/domain-anda.com

# 1. Aktifkan maintenance mode
php artisan down

# 2. Pull kode terbaru
git pull origin main

# 3. Update PHP dependencies (jika ada perubahan composer.json)
composer install --optimize-autoloader --no-dev

# 4. Build ulang assets (jika ada perubahan CSS/JS)
npm run build

# 5. Jalankan migrasi baru (jika ada)
php artisan migrate --force

# 6. Refresh cache
php artisan optimize

# 7. Matikan maintenance mode
php artisan up
```

Or setelah melakukan `git pull`, Anda juga dapat membuka URL inisialisasi terpadu di browser untuk menyegarkan database dan cache:
*   `https://domain-anda.com/deploy/init?key=kunci-rahasia-anda`

---

## 📌 Referensi Cepat

### Perintah SSH

| Perintah | Fungsi |
|----------|--------|
| `php artisan optimize` | Cache config + route + view sekaligus |
| `php artisan optimize:clear` | Hapus semua cache |
| `php artisan migrate --force` | Jalankan migrasi |
| `php artisan storage:link` | Buat symlink storage |
| `php artisan down / up` | Mode maintenance ON/OFF |
| `git pull origin main` | Update dari GitHub |

### Route Utility Bawaan App

| URL | Fungsi |
|-----|--------|
| `/deploy/init?key=YOUR_KEY` | Inisialisasi Database, Storage Link, dan Clear Cache |

---

> **Project**: ProjectSalesCars  
> **Repo**: [github.com/username/ProjectSales](https://github.com/username/ProjectSales)  
> **Platform**: 1Panel + Nginx/OpenResty + PHP 8.2 + MySQL 8.0  
> **Dibuat**: Mei 2026
