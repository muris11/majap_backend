 # Laravel 12 cPanel Deployment Guide
 
 ## Struktur Folder di cPanel
 
 ```
 public_html/                    <- ROOT (upload semua file Laravel di sini)
 ├── .htaccess                   <- File keamanan utama (sudah dibuat)
 ├── .env                        <- Environment production
 ├── app/
 │   └── .htaccess               <- Blokir akses
 ├── bootstrap/
 │   └── .htaccess               <- Blokir akses
 ├── config/
 │   └── .htaccess               <- Blokir akses
 ├── database/
 │   └── .htaccess               <- Blokir akses
 ├── lang/
 │   └── .htaccess               <- Blokir akses
 ├── public/                     <- Entry point Laravel
 │   ├── .htaccess
 │   ├── index.php
 │   └── storage -> ../storage/app/public (symlink)
 ├── resources/
 │   └── .htaccess               <- Blokir akses
 ├── routes/
 │   └── .htaccess               <- Blokir akses
 ├── storage/
 │   └── .htaccess               <- Blokir akses
 ├── tests/
 │   └── .htaccess               <- Blokir akses
 └── vendor/
     └── .htaccess               <- Blokir akses
 ```
 
 ## Langkah Deployment
 
 ### 1. Persiapan Lokal
 
 ```bash
 # Install dependencies production only
 composer install --optimize-autoloader --no-dev
 
 # Build assets (jika menggunakan Vite)
 npm run build
 
 # Hapus folder node_modules (tidak perlu di production)
 rm -rf node_modules
 ```
 
 ### 2. Upload ke cPanel
 
 Upload semua file ke `public_html/` (atau subdomain folder)
 
 **JANGAN upload:**
 - `.git/` folder
 - `node_modules/` folder
 - `.env` (buat baru di server)
 - `storage/logs/*.log`
 
 ### 3. Konfigurasi .env di Server
 
 Buat file `.env` baru dengan nilai production:
 
 ```env
 APP_NAME="Mahasiswa Jabodetabek Polindra"
 APP_ENV=production
 APP_KEY=base64:GENERATE_VIA_ARTISAN
 APP_DEBUG=false
 APP_URL=https://your-domain.com
 
 # CRITICAL Security Settings
 TRUSTED_PROXIES=*
 
 # Database (sesuaikan dengan cPanel)
 DB_CONNECTION=mysql
 DB_HOST=localhost
 DB_PORT=3306
 DB_DATABASE=your_cpanel_db
 DB_USERNAME=your_cpanel_dbuser
 DB_PASSWORD=your_strong_password
 
 # Session Security
 SESSION_DRIVER=database
 SESSION_LIFETIME=120
 SESSION_ENCRYPT=true
 SESSION_SECURE_COOKIE=true
 SESSION_HTTP_ONLY=true
 SESSION_SAME_SITE=lax
 
 # Cache & Queue
 CACHE_STORE=database
 QUEUE_CONNECTION=database
 
 # CORS (sesuaikan dengan frontend domain)
 CORS_ALLOWED_ORIGINS=https://your-frontend-domain.com
 ```
 
 ### 4. Perintah Artisan via SSH atau Cron
 
 Jika ada SSH access:
 
 ```bash
 cd ~/public_html
 
 # Generate app key (SEKALI saja)
 php artisan key:generate
 
 # Jalankan migrasi
 php artisan migrate --force
 
 # Buat symlink storage
 php artisan storage:link
 
 # Optimasi untuk production
 php artisan config:cache
 php artisan route:cache
 php artisan view:cache
 php artisan event:cache
 php artisan icons:cache
 
 # Filament optimization
 php artisan filament:cache-components
 ```
 
 Jika TIDAK ada SSH, buat file `artisan-runner.php` sementara:
 
 ```php
 <?php
 // HAPUS FILE INI SETELAH SELESAI!
 require __DIR__.'/vendor/autoload.php';
 $app = require_once __DIR__.'/bootstrap/app.php';
 $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
 
 $commands = [
     'key:generate',
     'migrate --force',
     'storage:link',
     'config:cache',
     'route:cache', 
     'view:cache',
 ];
 
 foreach ($commands as $cmd) {
     echo "<h3>Running: php artisan $cmd</h3>";
     $kernel->call($cmd);
     echo "<pre>" . Artisan::output() . "</pre>";
 }
 
 echo "<h2>HAPUS FILE INI SEKARANG!</h2>";
 ```
 
 ### 5. Verifikasi Keamanan
 
 Test akses ke file sensitif (harus return 403 Forbidden):
 
 - `https://your-domain.com/.env`
 - `https://your-domain.com/composer.json`
 - `https://your-domain.com/vendor/autoload.php`
 - `https://your-domain.com/storage/logs/laravel.log`
 - `https://your-domain.com/artisan`
 - `https://your-domain.com/config/app.php`
 
 ### 6. Setup Cron Job (Queue & Scheduler)
 
 Di cPanel > Cron Jobs, tambahkan:
 
 ```
 * * * * * cd ~/public_html && php artisan schedule:run >> /dev/null 2>&1
 ```
 
 ## Troubleshooting
 
 ### Error 500 Internal Server Error
 
 1. Cek permission folder storage:
    ```bash
    chmod -R 775 storage bootstrap/cache
    ```
 
 2. Cek file `.htaccess` tidak corrupt
 
 3. Cek error log di cPanel > Error Log
 
 ### Asset/CSS Tidak Muncul
 
 1. Pastikan `APP_URL` di `.env` benar dan pakai HTTPS
 2. Pastikan `storage:link` sudah dijalankan
 3. Clear cache: `php artisan cache:clear`
 
 ### Filament Admin Error
 
 1. Jalankan: `php artisan filament:upgrade`
 2. Clear semua cache:
    ```bash
    php artisan optimize:clear
    php artisan filament:cache-components
    ```
 
 ## Peringatan Keamanan
 
 1. **JANGAN** set `APP_DEBUG=true` di production
 2. **JANGAN** expose `.env` file
 3. **SELALU** gunakan HTTPS
 4. **HAPUS** file debug/test setelah deployment
 5. **BACKUP** database secara berkala
 6. **UPDATE** dependencies secara berkala untuk security patch
