# 👥 Panduan Deploy Client Baru (Multi-Tenant / Single-Instance per Client) di aaPanel

Panduan ini menjelaskan langkah demi langkah untuk melakukan deploy aplikasi baru bagi client baru (**Sales B**) yang membeli aplikasi Anda. Setiap client akan memiliki **domain/subdomain sendiri, database sendiri, dan folder media sendiri** agar data antar sales tidak tercampur.

---

## 🏗️ Skema Deployment
*   **Aplikasi Sales A (Eksisting)**: `domain-sales-a.com` -> Database: `db_sales_a`
*   **Aplikasi Sales B (Baru)**: `domain-sales-b.com` -> Database: `db_sales_b`

Anda bisa men-deploy Sales B pada server VPS yang **sama** (menggunakan aaPanel eksisting) atau pada VPS **baru**. Langkah di bawah ini mengasumsikan Anda menggunakan VPS/aaPanel yang sama untuk menghemat biaya server.

---

## 📋 Langkah-Langkah Deploy untuk Sales B

### 1. Buat Website & Database Baru di aaPanel
1. Masuk ke aaPanel Anda.
2. Buka menu **Website** -> Klik **Add Site**.
3. Isi data untuk **Sales B**:
   *   **Domain**: `domain-sales-b.com` (atau subdomain, misal `salesb.hyundaisurabaya.com`)
   *   **Create DB**: Pilih **MySQL**
       *   **Database Name**: `db_sales_b` (buat nama unik untuk Sales B)
       *   **Username**: `user_sales_b`
       *   **Password**: *(buat password yang kuat — **catat!**)*
   *   **PHP Version**: Pilih **PHP-82**
4. Klik **Submit**.

---

### 2. Clone Source Code dari GitHub
Kita akan meletakkan kode aplikasi ke folder web Sales B di `/www/wwwroot/domain-sales-b.com`.

1. Buka menu **Terminal** di aaPanel.
2. Jalankan perintah berikut untuk meng-clone kode:
   ```bash
   cd /www/wwwroot/domain-sales-b.com
   
   # Hapus file default aaPanel
   rm -rf *
   
   # Clone repository ke folder ini
   git clone https://github.com/scripted42/ProjectSales.git .
   ```

---

### 3. Buat dan Sesuaikan File `.env` untuk Sales B
File `.env` inilah yang membedakan identitas Sales A dan Sales B.

1. Buka menu **Files** di aaPanel, masuk ke folder `/www/wwwroot/domain-sales-b.com`.
2. Copy `.env.example` dan ganti namanya menjadi `.env`.
3. Klik dua kali pada `.env` untuk mengeditnya. Sesuaikan parameter berikut:

```env
APP_NAME="Hyundai Sales B"           # Nama aplikasi/sales baru
APP_ENV=production
APP_KEY=                             # Biarkan kosong, nanti di-generate otomatis
APP_DEBUG=false
APP_URL=https://domain-sales-b.com   # Domain milik Sales B

# ── Database Connection (Sesuai database Sales B pada Langkah 1) ──
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sales_b
DB_USERNAME=user_sales_b
DB_PASSWORD=PASSWORD_DATABASE_SALES_B

# ── Email Settings (Gunakan email SMTP milik Sales B / Pengirim khusus) ──
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME="email-sales-b@gmail.com"
MAIL_PASSWORD="app-password-gmail-sales-b"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="email-sales-b@gmail.com"
MAIL_FROM_NAME="AutoShow Pro - Sales B"

# ── Developer & Deployment Setup (PENTING!) ──
DEVELOPER_NAME="Super Developer"
DEVELOPER_EMAIL="email-developer-anda@gmail.com"  # Email Anda sebagai developer/admin utama
DEVELOPER_PASSWORD="password-rahasia-anda"

DEVELOPER_OTP_ENABLED=false          # Set ke false untuk mempermudah setup awal tanpa OTP email
DEVELOPER_OTP_STATIC=123456

DEPLOYMENT_KEY="kunci-keamanan-sales-b"  # Buat token acak khusus untuk Sales B
SEED_MOCK_DATA=false                 # ⚠️ WAJIB FALSE! Agar database Sales B bersih tanpa data dummy/Sales A

# ── AutoShow Real AI Settings ─────────────
OPENROUTER_API_KEY=""                # API Key default OpenRouter (opsional)
AI_DEFAULT_PROVIDER="openrouter"     # Pilihan: openrouter, deepseek, gemini, atau disabled
AI_DEFAULT_MODEL="qwen/qwen-2.5-7b-instruct:free"
```
4. Klik **Save** (Ctrl+S).

---

### 4. Jalankan Setup Dependensi & Permission via SSH
Kembali ke **Terminal** aaPanel atau SSH klien Anda, lalu jalankan:

```bash
cd /www/wwwroot/domain-sales-b.com

# 1. Install dependensi PHP
composer install --optimize-autoloader --no-dev

# 2. Generate Application Key Laravel
php artisan key:generate

# 3. Build Aset CSS/JS
npm install
npm run build

# 4. Berikan Hak Akses ke Web Server (www)
chmod -R 775 storage bootstrap/cache
chown -R www:www storage bootstrap/cache public/

# 5. Optimasi Cache Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### 5. Konfigurasi Running Directory & Rewrite di aaPanel
1. Buka menu **Website** di aaPanel -> Klik domain `domain-sales-b.com`.
2. Pilih tab **Site directory** -> Pada **Running directory** pilih `/public` -> Klik **Save**.
3. Pilih tab **URL rewrite** -> Pilih preset **laravel5** -> Klik **Save**.

---

### 6. Setup SSL (HTTPS)
1. Pada menu pengaturan website Sales B yang sama, pilih tab **SSL**.
2. Pilih **Let's Encrypt**, centang domainnya, lalu klik **Apply**.
3. Setelah SSL aktif, centang **Force HTTPS** di pojok kanan atas.

---

### 7. Jalankan Inisialisasi Database Bersih via Browser
Buka browser Anda dan akses URL inisialisasi aman menggunakan `DEPLOYMENT_KEY` yang Anda setel di `.env` Sales B:

`https://domain-sales-b.com/deploy/init?key=kunci-keamanan-sales-b`

**Apa yang terjadi pada langkah ini?**
1. **Database** `db_sales_b` dimigrasikan dalam keadaan kosong (tanpa mobil, brosur, atau artikel dummy milik Sales A).
2. **Akun Developer Utama** didefinisikan secara otomatis sesuai isi `.env` Sales B.
3. Folder **Storage Link** (`public/storage`) dibuat baru khusus untuk data gambar Sales B.

---

### 8. Registrasi & Setup Akun untuk Sales B
Setelah inisialisasi sukses:
1. Buka halaman admin di `https://domain-sales-b.com/admin`.
2. Login menggunakan email dan password Developer Anda (yang disetel pada `DEVELOPER_EMAIL` di `.env` Sales B).
3. **Buat Akun untuk Sales B**:
   *   Masuk ke menu **Users** -> Klik **New User**.
   *   Buat akun dengan **Role: Administrator** atau **Sales**, isi nama Sales B, email Sales B, dan password mereka.
4. Berikan akun tersebut kepada Sales B.
5. Sales B sekarang dapat login secara mandiri ke dashboard mereka sendiri untuk mulai mengunggah katalog mobil, mengunggah brosur PDF, mengedit nomor WhatsApp tujuan, dan membuat artikel berita.

---

## 🔄 Cara Cepat Update Aplikasi (Redeploy) untuk Semua Client
Jika Anda melakukan pembaruan fitur di GitHub dan ingin menerapkan pembaruan tersebut ke seluruh client:

### Update untuk Sales A:
```bash
cd /www/wwwroot/domain-sales-a.com
git pull origin main
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan optimize
```

### Update untuk Sales B:
```bash
cd /www/wwwroot/domain-sales-b.com
git pull origin main
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan optimize
```
*(Atau gunakan link inisialisasi masing-masing client di browser setelah melakukan `git pull`).*
