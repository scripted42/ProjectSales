# 🏆 AutoShow Pro Portal - Premium Car Dealership System

AutoShow Pro adalah platform manajemen showroom mobil modern yang dirancang khusus untuk profesional sales. Platform ini menawarkan pengalaman visual "Pro Max" dengan fokus pada konversi tinggi melalui desain clean, integrasi WhatsApp, dan sistem manajemen inventory yang intuitif, kini dilengkapi dengan kontrol keamanan kelas dunia dan fitur developer sandbox.

---

## 📸 Preview Halaman Utama

<p align="center">
  <img src="screenshots/hero_preview.png" width="800" alt="Hero Section Preview">
</p>
<p align="center">
  <em>Sistem Slideshow Hero Banner yang Sinematik</em>
</p>

---

## 🚀 Fitur Utama & Keunggulan Baru

- **Premium Showroom Landing Page**: Tampilan modern dengan tema clean white, visual glassmorphism, dan font premium *Outfit*.
- **Cinematic Hero Slideshow**: Banner produk interaktif yang menampilkan unit unggulan secara dinamis.
- **Break-the-Frame Card Design**: Desain kartu unit mobil yang artistik dengan efek overflow 3D (gambar mobil melayang melompati batas kartu).
- **Booking Test Drive System (Livewire)**: Fitur penjadwalan test drive interaktif yang terintegrasi langsung dengan notifikasi WhatsApp ke Sales.
- **Simulasi Kredit Cerdas (Livewire)**: Kalkulator kredit interaktif real-time untuk simulasi uang muka, suku bunga, tenor, dan cicilan bulanan.
- **Popup Video Promosi Premium**: Video promosi pop-up otomatis di awal kunjungan (mendukung MP4 lokal, direct URL, atau YouTube) dengan sistem *muted-first autoplay* dan tombol *Unmute* dinamis yang ramah browser.
- **WhatsApp Lead Integration**: Menghubungkan pembeli langsung dengan Sales Expert melalui tautan WhatsApp terenkripsi otomatis.
- **⚡ Developer Analytics Sandbox (Baru)**: Panel widget analitik dashboard kustom khusus untuk developer dengan tombol interaktif `Isi Data Dummy` & `Reset Data Real` bertenaga Livewire yang sangat aman (menyaring data dummy berdasarkan alamat IP `127.0.0.1` dan email `@example.com` tanpa merusak data pelanggan nyata).
- **🔐 Developer OTP Security Gate (Baru)**: Sistem otentikasi dua langkah (OTP) dinamis yang dikirimkan langsung ke email pribadi developer untuk melindungi area operasional sensitif.
- **🛡️ Hardened Security Protection (Baru)**: Penutupan celah informasi sensitif dengan penonaktifan `APP_DEBUG=false` untuk merender halaman `500 Server Error` yang aman dan elegan saat database/server mengalami gangguan.
- **🎨 Favicon Dinamis & Fallback (Baru)**: Browser tab icon dinamis yang mengikuti settingan logo di Admin Panel, terintegrasi langsung dengan fallback logo resmi Hyundai biru untuk menghilangkan total ikon Laravel merah bawaan.

---

## 📊 ERD (Entity Relationship Diagram)

```mermaid
erDiagram
    CARS ||--o{ TEST_DRIVE_BOOKINGS : "selected_in"
    CARS ||--o{ GALLERIES : "has"
    CONSULTANTS ||--o{ CARS : "manages"
    
    CARS {
        bigint id PK
        string name
        string slug
        string category
        decimal price
        text description
        json features
        string image "Thumbnail"
        string hero_image "Banner"
        json images "Gallery"
        string flyer "PDF"
        boolean is_available
    }
    
    TEST_DRIVE_BOOKINGS {
        bigint id PK
        string name "Sesuai KTP"
        string phone "WhatsApp"
        string email
        bigint car_id FK
        date booking_date
        string status "pending/confirmed/etc"
        text notes
    }
    
    CONSULTANTS {
        bigint id PK
        string name
        string phone
        string photo
        string location
    }

    PROMOS {
        bigint id PK
        string title
        string image
        boolean is_active
    }

    SETTINGS {
        bigint id PK
        string key
        text value
    }

    SITE_LOGS {
        bigint id PK
        string activity
        text description
        timestamp created_at
    }
```

---

## 🛠️ Panduan Instalasi & Deployment Utama

Pilih metode instalasi yang sesuai dengan infrastruktur server Anda:

---

### 🌐 Metode A: Menggunakan SSH (VPS, Cloud Server, Dedicated Server)

Metode ini sangat disarankan untuk fleksibilitas tinggi dan manajemen otomatis menggunakan Git, Composer, dan Terminal.

#### 1. Clone Repository & Masuk Direktori
```bash
git clone https://github.com/scripted42/ProjectSales.git
cd ProjectSales
```

#### 2. Install Dependensi PHP & Frontend
```bash
# Install PHP Dependencies (Production Mode)
composer install --optimize-autoloader --no-dev

# Install & Build Frontend Assets
npm install
npm run build
```

#### 3. Konfigurasi Environment File
```bash
cp .env.example .env
php artisan key:generate
```
Edit file `.env` yang baru dibuat untuk memasukkan detail database dan credentials email Anda:
```env
APP_NAME="Hyundai Showroom"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://namadomainanda.com
SESSION_SECURE_COOKIE=true # Proteksi cookie sesi pada jaringan HTTPS

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=nama_user_database
DB_PASSWORD=sandi_database_anda

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME="email_anda@gmail.com"
MAIL_PASSWORD="sandi_aplikasi_gmail_anda"
MAIL_ENCRYPTION=ssl
```

#### 4. Migrasi Database & Hubungkan Penyimpanan
```bash
# Jalankan migrasi tabel database dan data awal (seeding)
php artisan migrate --seed

# Buat symbolic link dari storage ke public folder
php artisan storage:link
```

#### 5. Optimasi Cache & Set Permission Folder
```bash
# Bersihkan dan cache konfigurasi demi kecepatan super
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions agar server web dapat menulis file logs/uploads
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .
```

---

### 📂 Metode B: Manual Tanpa SSH (Shared Hosting / cPanel File Manager)

Gunkan metode ini jika hosting Anda adalah tipe Shared Hosting konvensional yang tidak menyediakan terminal SSH.

#### 1. Bundling Dependensi di Komputer Lokal (Sebelum Upload)
Karena Shared Hosting tidak memiliki Composer/NPM, kita harus mempersiapkan berkasnya terlebih dahulu di komputer lokal Anda:
1. Jalankan perintah `npm run build` di komputer lokal Anda untuk membuat bundel aset final di folder `public/build`.
2. Pastikan folder `vendor` di lokal sudah lengkap terinstall lewat `composer install`.
3. Kompres seluruh file dan folder proyek Anda ke dalam satu file `.zip` (misalnya: `proyek-sales.zip`). **Pastikan folder `vendor` dan `public/build` ikut terkompres di dalamnya!**

#### 2. Unggah dan Ekstrak Berkas di cPanel File Manager
1. Masuk ke **cPanel File Manager** Anda.
2. Unggah file `proyek-sales.zip` ke folder utama di luar `public_html` (misal di direktori `/home/username/ProjectSales`).
3. Ekstrak file zip tersebut di lokasi tersebut.

#### 3. Konfigurasi Folder Publik (`public_html`)
1. Buka folder `/home/username/ProjectSales/public` hasil ekstrak tadi.
2. Pindahkan **seluruh isi** folder `/public` tersebut (termasuk folder `assets`, `build`, file `index.php`, `.htaccess`, dan `robots.txt`) langsung ke dalam folder publik utama hosting Anda yaitu **`public_html`**.

#### 4. Hubungkan File `index.php` ke Inti Proyek
1. Di dalam folder `public_html`, klik kanan file `index.php` lalu pilih **Edit**.
2. Sesuaikan jalur path (`autoload.php` dan `app.php`) agar mengarah dengan benar ke folder proyek Anda di luar `public_html`. 
   Ubah baris 24 dan 38:
   ```php
   // SEBELUM:
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';

   // SESUDAH (arahkan ke folder ProjectSales di luar public_html):
   require __DIR__.'/../ProjectSales/vendor/autoload.php';
   $app = require_once __DIR__.'/../ProjectSales/bootstrap/app.php';
   ```
3. Klik **Save Changes**.

#### 5. Pembuatan Database & Impor Data (phpMyAdmin)
1. Di cPanel, cari menu **MySQL Database Wizard**. Buat nama database, user baru, dan kata sandi baru. Berikan hak akses penuh (*All Privileges*).
2. Masuk ke **phpMyAdmin** komputer lokal Anda, lakukan **Export** pada database lokal Anda ke dalam file `.sql`.
3. Masuk ke **phpMyAdmin** di cPanel Anda, pilih database yang baru dibuat, lalu lakukan **Import** menggunakan berkas `.sql` lokal Anda tadi.

#### 6. Pengaturan Konfigurasi `.env`
1. Buka folder `/home/username/ProjectSales/` di File Manager cPanel.
2. Buat atau edit file `.env`, lalu masukkan credentials database baru Anda beserta data email SMTP Anda seperti instruksi Metode A. Pastikan `APP_DEBUG=false` demi keamanan data.

#### 7. Membuat Storage Symbolic Link Secara Manual
Karena Shared Hosting tidak memiliki akses terminal untuk menjalankan `php artisan storage:link`, buatlah file script PHP manual:
1. Di dalam folder `/public_html`, buatlah file baru bernama `link-storage.php`.
2. Edit file tersebut dan isi dengan kode berikut:
   ```php
   <?php
   // Ganti 'username' sesuai dengan nama username cPanel Anda
   symlink('/home/username/ProjectSales/storage/app/public', '/home/username/public_html/storage');
   echo "Symbolic storage link created successfully!";
   ```
3. Buka tab baru di browser Anda dan akses: `https://namadomainanda.com/link-storage.php`.
4. Setelah muncul tulisan sukses, **segera hapus file `link-storage.php`** dari File Manager demi keamanan website Anda.

---

## 📺 Panduan Lengkap Oprek STB ZTE B860H Menjadi Server (Mulai Dari Nol)

Jika Anda menggunakan perangkat **STB ZTE ZXV10 B860H v2.1 (2GB RAM)** untuk dijadikan server lokal mandiri 24 jam dengan biaya listrik sangat irit, ikuti panduan lengkap di bawah ini:

### 1. Persiapan Alat & Bahan (Flashing OS)
* **Media Penyimpanan (Pilih Salah Satu):**
  * **MicroSD Card (Sangat Disarankan):** Minimal ukuran **8GB** (disarankan Class 10 untuk kecepatan optimal). Keunggulan: Booting lebih cepat karena menggunakan bus SDIO internal STB, menyisakan port USB kosong untuk keperluan HDD/SSD eksternal tambahan.
  * **USB Flashdisk:** Minimal ukuran **8GB** (gunakan merk tepercaya seperti SanDisk atau Kingston). Keunggulan: Sangat mudah ditemukan, murah, dan memiliki ketahanan tulis (*write endurance*) yang baik untuk operasi server 24 jam.
* **Perangkat Lunak Flashing:** Unduh dan pasang aplikasi **BalenaEtcher** atau **Rufus** di laptop Anda.
* **Image OS Armbian Linux:** Unduh berkas image Armbian khusus untuk chipset **Amlogic S905X** (bisa menggunakan distro berbasis Debian Bullseye atau Ubuntu Focal).

### 2. Proses Flashing OS Armbian ke Media Penyimpanan
1. Hubungkan MicroSD (menggunakan Card Reader) atau USB Flashdisk ke laptop Anda.
2. Buka aplikasi **BalenaEtcher**, klik **Flash from file** dan pilih file image `.img` Armbian yang sudah diunduh.
3. Klik **Select target** dan pilih MicroSD atau USB Flashdisk Anda, kemudian klik **Flash!**. Tunggu hingga proses verifikasi selesai.
4. **Sangat Penting (Konfigurasi DTB Chipset):** 
   Setelah flashing selesai, buka partisi drive bernama `BOOT` di laptop Anda.
   * Masuk ke folder `/dtb/` -> cari file bernama `meson-gxl-s905x-p212.dtb` atau `meson-gxl-s905x-b860h.dtb`.
   * Salin file tersebut, lalu letakkan di direktori utama (root) media penyimpanan (MicroSD atau Flashdisk) Anda.
   * Ubah nama file salinan tersebut menjadi **`dtb.img`** (ini agar STB mengenali spesifikasi hardware layar dan port B860H Anda dengan tepat).

### 3. Booting Pertama Kali di STB
1. Cabut kabel adaptor daya dari STB ZTE B860H Anda.
2. Hubungkan kabel **LAN** dari modem/router wifi ke port LAN STB (kabel LAN menjamin koneksi server stabil tanpa putus dibanding Wi-Fi).
3. Hubungkan kabel **HDMI** dari STB ke TV atau monitor untuk melihat proses text terminal booting.
4. Masukkan kartu MicroSD atau Flashdisk yang sudah terisi OS Armbian tadi ke slot STB (Flashdisk dicolokkan ke **Port USB 1**).
5. **Metode Reset Jack AV:** 
   * Siapkan tusuk gigi atau klip kertas. Masukkan ke dalam lubang **jack AV** di belakang STB secara perlahan hingga menyentuh tombol klik kecil di dalamnya.
   * **Tekan dan tahan** tombol reset tersebut.
   * Sambil tetap menahan tombol reset, colokkan adaptor daya 12V ke STB.
   * Tetap tahan tombol reset selama **5-10 detik** hingga TV/monitor Anda menampilkan baris teks Linux berjalan, kemudian lepaskan tusuk gigi.
6. **Login Awal Server:**
   * Di layar terminal, ketik username bawaan: **`root`**
   * Masukkan password bawaan: **`1234`** atau **`password`** (sesuaikan dengan image Armbian yang Anda unduh).
   * Sistem akan langsung mewajibkan Anda membuat kata sandi root baru (masukkan sandi yang kuat dan mudah Anda ingat), membuat akun pengguna baru non-root, serta memilih setelan zona waktu lokal (pilih **`Asia/Jakarta`**).

---

### 🎛️ PILIH SALAH SATU JALUR INSTALASI WEB SERVER (METODE A ATAU METODE B):

---

### [JALUR A]: Instalasi Manual CLI (Sangat Ringan & Performa Maksimal)

Gunakan metode ini jika Anda menginginkan performa server tercepat dengan konsumsi memory RAM sekecil mungkin.

#### A1. Instalasi LEMP Stack di Terminal STB:
```bash
# Perbarui daftar paket aplikasi server
sudo apt update && sudo apt upgrade -y

# Install Nginx, Database MariaDB, dan Modul PHP 8.2/8.3
sudo apt install nginx mariadb-server php-fpm php-mysql php-xml php-curl php-gd php-mbstring php-zip -y
```

#### A2. Optimasi Penggunaan RAM MariaDB (Penting untuk STB 2GB)
Agar database MariaDB berjalan sangat ringan di STB Anda, batasi pemakaian memory-nya:
1. Buka file konfigurasi server MariaDB:
   ```bash
   sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
   ```
2. Temukan baris di bawah `[mysqld]` dan tambahkan batasan memori berikut:
   ```ini
   key_buffer_size = 16M
   max_connections = 20
   query_cache_size = 8M
   ```
3. Simpan dengan menekan `Ctrl + O` -> `Enter`, lalu keluar dengan `Ctrl + X`.

#### A3. Konfigurasi Nginx Virtual Host
1. Buat file konfigurasi Nginx untuk domain Anda:
   ```bash
   sudo nano /etc/nginx/sites-available/hyundai
   ```
2. Masukkan konfigurasi berikut:
   ```nginx
   server {
       listen 80;
       server_name hyundaisurabaya.com;
       root /var/www/ProjectSales/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;
       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```
3. Aktifkan konfigurasi website:
   ```bash
   sudo ln -s /etc/nginx/sites-available/hyundai /etc/nginx/sites-enabled/
   sudo systemctl restart nginx
   ```

#### A4. Pembuatan Swap Memory (Pencegah Crash RAM)
Jika memori RAM terasa sesak, tambahkan swap virtual RAM 1GB agar server anti-crash:
```bash
sudo fallocate -l 1G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
```

#### A5. Otomatisasi Auto-Start Layanan CLI saat Reboot / Listrik Padam
Jalankan perintah pengaktifan layanan *Systemd* berikut agar web server menyala otomatis saat STB hidup kembali:
```bash
sudo systemctl enable nginx
sudo systemctl enable mariadb
sudo systemctl enable php8.2-fpm # Sesuaikan versi PHP Anda
```

---

### [JALUR B]: Instalasi Praktis via aaPanel (Web GUI - Sangat Direkomendasikan untuk Pemula)

Gunakan metode ini jika Anda menginginkan kemudahan mengunggah berkas zip, mengedit file `.env`, dan mengelola database MySQL langsung lewat tampilan browser visual yang ramah pengguna.

#### B1. Langkah Instalasi aaPanel di Terminal STB:
1. Hubungkan SSH STB Anda lewat PuTTY/Termius.
2. Jalankan perintah instalasi resmi aaPanel untuk Debian/Ubuntu ARM64 berikut:
   ```bash
   wget -O install.sh http://www.aapanel.com/script/install-ubuntu_6.0_en.sh && sudo bash install.sh aapanel
   ```
3. Ketik **`y`** saat diminta konfirmasi instalasi, lalu tekan **Enter**. Proses instalasi berkisar 5 s/d 10 menit.
4. Di akhir proses, terminal akan menampilkan **alamat IP Login Panel, Username, dan Password**. Catat detail tersebut dengan aman!

#### B2. Pemasangan Paket LNMP Ringan (Penting untuk STB RAM 2GB):
1. Buka browser laptop Anda, masuk ke alamat IP login aaPanel Anda (misalnya: `http://192.168.1.100:8888/login_key`).
2. Masukkan username dan password panel Anda.
3. Setelah masuk, jendela pop-up **One-Click Installation** akan mendeteksi server baru. **PILIH SETELAN BERIKUT AGAR SUPER RINGAN DI STB:**
   * Nginx: **1.22 atau 1.24**
   * MySQL: **MySQL 5.6** atau **MariaDB 10.4** (*JANGAN pilih MySQL 8.0 karena sangat berat untuk RAM 2GB!*)
   * PHP: **8.2** atau **8.3**
   * phpMyAdmin: **5.2**
   * **SANGAT PENTING:** Pilih metode **`Fast` (RPM/Package)**. *JANGAN pernah memilih metode `Compiled` karena proses compile mandiri di STB memakan waktu berjam-jam dan memberatkan CPU.*
4. Klik **One-Key Install** dan tunggu hingga indikator selesai semua.

#### B3. Menambahkan Website & Konfigurasi Laravel di aaPanel:
1. Masuk ke menu **Website** di panel sebelah kiri -> klik **Add Site**.
2. Masukkan domain utama Anda: `hyundaisurabaya.com`.
3. Pada opsi **Database**, pilih **MySQL** (ini akan otomatis membuat database dan user baru untuk proyek Anda). Klik **Submit**.
4. Masuk ke menu **Files** -> buka folder website Anda di `/www/wwwroot/hyundaisurabaya.com`.
5. Klik **Upload** -> unggah file `.zip` proyek Anda -> klik kanan file zip -> pilih **Unzip** untuk ekstrak file proyek sales.
6. **Setting Running Directory Laravel:**
   * Kembali ke menu **Website** -> klik nama domain Anda -> klik **Site Directory**.
   * Ubah nilai **Running Directory** dari `/` menjadi **`/public`** -> klik **Save** (ini wajib agar index Laravel terbaca dengan benar).
7. **Setting URL Rewrite (Routing Laravel):**
   * Di dalam setelan domain tersebut, pilih menu **URL Rewrite**.
   * Pilih template preset **`laravel`** dari menu dropdown yang tersedia -> klik **Save** (ini wajib agar semua link halaman detail mobil, blog, dll. tidak menghasilkan error 404).

> [!TIP]
> Semua paket yang diinstal melalui aaPanel secara bawaan sudah langsung dikonfigurasi aktif secara otomatis saat booting (*auto-startenabled*). Anda tidak perlu mengetik perintah `systemctl` manual lagi untuk Nginx/MySQL/PHP!

---

## ☁️ Deployment dengan Cloudflare Tunnel (Zero Trust)

Jika Anda ingin meng-onlinekan server lokal (seperti STB Linux, PC XAMPP, atau VM lokal) agar dapat diakses dengan domain publik tanpa perlu membuka port (*Port Forwarding*) pada router/modem ISP, gunakan **Cloudflare Tunnel**:

> [!IMPORTANT]
> **💡 TIPS PENTING: BYPASS VERIFIKASI KARTU KREDIT CLOUDFLARE**
> Jika Anda mencoba membuat tunnel melalui Dashboard Web Cloudflare Zero Trust di browser, Cloudflare akan **mewajibkan Anda memasukkan informasi kartu kredit/debit** untuk verifikasi (meskipun layanannya 100% gratis).
>
> **CARA BYPASS (100% GRATIS TANPA KARTU KREDIT):**
> Ikuti panduan di bawah ini secara **STRICT / KETAT menggunakan metode lokal CLI (`cloudflared` lewat terminal SSH)**. Dengan membuat tunnel langsung melalui perintah terminal `cloudflared tunnel create`, Cloudflare akan **sepenuhnya melewatkan (bypass) syarat kartu kredit** dan Anda bisa meng-onlinekan server lokal Anda secara gratis tanpa hambatan pembayaran!

### 1. Registrasi Domain Baru & Hubungkan ke Cloudflare

Sebelum memasang Cloudflare Tunnel, Anda harus memiliki nama domain aktif dan mendelegasikannya ke Cloudflare:

#### A. Pembelian / Registrasi Domain Baru:
1. Kunjungi penyedia registrasi domain pilihan Anda (seperti **Domainesia**, **Niagahoster**, **Dewaweb**, atau **Namecheap**).
2. Cari nama domain yang Anda inginkan (misalnya: `hyundaisurabaya.com`).
3. Lakukan pembelian dan selesaikan proses verifikasi kepemilikan domain di portal registrar tersebut.

#### B. Menambahkan Domain ke Akun Cloudflare:
1. Daftar atau masuk ke dashboard **[Cloudflare](https://dash.cloudflare.com/)**.
2. Klik tombol **Add a Site** / **Tambahkan Situs**, lalu masukkan nama domain Anda (tanpa `www` atau `https`, cukup `hyundaisurabaya.com`).
3. Pilih paket layanan **Free** (Gratis), lalu klik **Continue**.
4. Cloudflare akan secara otomatis memindai DNS record bawaan domain Anda. Klik **Continue** lagi.

#### C. Mengubah Nameserver di Portal Registrar Domain:
1. Cloudflare akan menampilkan sepasang **Cloudflare Nameservers** baru (contohnya: `heather.ns.cloudflare.com` dan `darren.ns.cloudflare.com`).
2. Buka tab baru, masuk ke **Client Area / Portal Member** tempat Anda membeli domain tadi (misal portal Domainesia).
3. Cari menu **Domain Management** / **Kelola Domain** -> cari submenu **Nameservers**.
4. Ubah setelan Nameservers dari *"Use Default Nameservers"* menjadi *"Use Custom Nameservers"*.
5. Masukkan kedua Nameservers yang diberikan oleh Cloudflare tadi ke kolom yang tersedia (misal Nameserver 1 dan Nameserver 2). Hapus nameserver lain jika ada.
6. Klik **Save / Update Nameservers**.
7. Kembali ke dashboard Cloudflare Anda, lalu klik **Check Nameservers** -> **Finish**.

> [!NOTE]
> Proses perubahan Nameservers (disebut propagasi DNS) biasanya memakan waktu antara **5 menit hingga maksimal 24 jam** tergantung dari ISP dan registrar Anda. Anda dapat memantau status propagasi secara global menggunakan layanan seperti [DNSChecker](https://dnschecker.org/).

### 2. Instalasi Cloudflared di Server Lokal
Unduh dan pasang agen `cloudflared` di server Anda:
- **Untuk Linux Debian/Ubuntu (STB/Server):**
  ```bash
  curl -L --output cloudflared.deb https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-arm.deb # (Ganti arm dengan amd64 jika di PC/VPS biasa)
  sudo dpkg -i cloudflared.deb
  ```

### 3. Autentikasi Cloudflared
Hubungkan agen lokal server Anda dengan akun Cloudflare Anda:
```bash
cloudflared tunnel login
```
*Salin tautan URL yang muncul di terminal, buka di browser Anda, lalu setujui otorisasi domain pilihan Anda.*

### 4. Membuat Tunnel Baru
```bash
cloudflared tunnel create hyundai-tunnel
```
*Perintah ini akan menghasilkan ID Tunnel berupa string UUID yang unik dan menyimpan file rahasia kredensial JSON di folder `/root/.cloudflared/`.*

### 5. Membuat Konfigurasi `config.yml`
Buat berkas konfigurasi di direktori `/root/.cloudflared/config.yml`:
```bash
nano /root/.cloudflared/config.yml
```
Masukkan konfigurasi berikut (sesuaikan UUID dan domain Anda):
```yaml
tunnel: <UUID_TUNNEL_ANDA>
credentials-file: /root/.cloudflared/<UUID_TUNNEL_ANDA>.json

ingress:
  - hostname: hyundaisurabaya.com
    service: http://localhost:8000  # Arahkan ke port php artisan serve atau Nginx Anda
  - service: http_status:404
```

### 6. Menghubungkan Subdomain / Domain via DNS
Jalankan perintah ini agar domain utama Anda terhubung secara otomatis melalui DNS CNAME Cloudflare:
```bash
cloudflared tunnel route dns hyundai-tunnel hyundaisurabaya.com
```

### 7. Menjalankan Tunnel Sebagai Layanan Sistem (Service)
Agar tunnel tetap berjalan secara otomatis di latar belakang saat server/STB dinyalakan kembali:
```bash
# Install sebagai service sistem Linux
sudo cloudflared service install

# Jalankan dan aktifkan layanannya
sudo systemctl start cloudflared
sudo systemctl enable cloudflared
```
*Sekarang, server lokal Anda sudah terhubung secara aman di balik jaringan Cloudflare, lengkap dengan enkripsi SSL gratis (HTTPS) tanpa perlu setting IP Publik Statis!*

---

## 👤 Kontributor & Hak Cipta
- **AutoShow Pro Team** - Developer & Designer.

&copy; 2026 AutoShow Pro - Hyundai Showroom. Semua Hak Dilindungi.
