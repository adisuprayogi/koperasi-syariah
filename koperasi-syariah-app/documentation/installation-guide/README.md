# Panduan Instalasi Aplikasi Koperasi Syariah

## Persyaratan Sistem

### Requirements Minimal
- **PHP**: 8.1 atau lebih tinggi
- **Database**: MySQL 8.0+ atau MariaDB 10.3+
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **Composer**: 2.0 atau lebih tinggi
- **Node.js**: 16+ atau npm 8+ (untuk development)
- **Memory**: Minimal 2GB RAM
- **Storage**: Minimal 5GB free space

### PHP Extensions yang Diperlukan
- PHP Fileinfo
- PHP GD
- PHP JSON
- PHP Mbstring
- PHP OpenSSL
- PHP PDO
- PHP PDO MySQL
- PHP Tokenizer
- PHP XML
- PHP ctype
- PHP bcmath
- PHP curl

## Langkah 1: Download Aplikasi

### Option A: Clone dari Git Repository
```bash
git clone https://github.com/your-repo/koperasi-syariah-app.git
cd koperasi-syariah-app
```

### Option B: Download ZIP File
1. Download file ZIP dari repository
2. Extract ke direktori web server
3. Rename folder menjadi `koperasi-syariah-app`
4. Buka terminal/command prompt dan navigasi ke folder tersebut

## Langkah 2: Install Dependencies

### Install PHP Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### Install JavaScript Assets (untuk production)
```bash
npm install --production
npm run build
```

## Langkah 3: Konfigurasi Environment

### Copy Environment File
```bash
cp .env.example .env
```

### Generate Application Key
```bash
php artisan key:generate
```

### Edit Environment File (.env)
Buka file `.env` dan sesuaikan konfigurasi berikut:

```env
# Basic Configuration
APP_NAME="Koperasi Syariah"
APP_ENV=production
APP_KEY=base64:generated_key_here
APP_DEBUG=false
APP_URL=http://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koperasi_syariah
DB_USERNAME=username
DB_PASSWORD=password

# Mail Configuration (untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Application Configuration
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# File Upload
FILESYSTEM_DISK=local
```

## Langkah 4: Setup Database

### Buat Database Baru
```sql
CREATE DATABASE koperasi_syariah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Run Database Migration
```bash
php artisan migrate --force
```

### Seed Initial Data
```bash
php artisan db:seed --class=DatabaseSeeder --force
```

## Langkah 5: Setup Storage & Permissions

### Link Storage Directory
```bash
php artisan storage:link
```

### Set Directory Permissions
```bash
# Linux/macOS
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Untuk web server writable
chmod -R 777 storage/framework/cache
chmod -R 777 storage/framework/sessions
chmod -R 777 storage/framework/views
chmod -R 777 storage/logs
```

### Create Storage Directories
```bash
mkdir -p storage/app/public/uploads
mkdir -p storage/app/public/exports
mkdir -p storage/app/public/reports
```

## Langkah 6: Konfigurasi Web Server

### Apache Configuration
Buat file virtual host:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/koperasi-syariah-app/public

    <Directory /path/to/koperasi-syariah-app>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/koperasi-error.log
    CustomLog ${APACHE_LOG_DIR}/koperasi-access.log combined
</VirtualHost>
```

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/koperasi-syariah-app/public;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## Langkah 7: Optimasi & Cache

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Cache Configuration (untuk production)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Optimize Autoloader
```bash
composer dump-autoload --optimize
```

## Langkah 8: Setup Cron Jobs

Buat cron job untuk menjalankan scheduled tasks:

```bash
# Buka crontab
crontab -e

# Tambahkan baris berikut (setiap menit)
* * * * * cd /path/to/koperasi-syariah-app && php artisan schedule:run >> /dev/null 2>&1
```

## Langkah 9: Testing Instalasi

### 1. Test Database Connection
```bash
php artisan tinker
> DB::connection()->getPdo();
```

### 2. Test Application
- Buka browser dan akses `http://your-domain.com`
- Pastikan halaman login muncul
- Login dengan akun default:
  - Email: `admin@koperasi.com`
  - Password: `password`

### 3. Test Key Features
- Registrasi anggota baru
- Input transaksi simpanan
- Generate laporan
- Export data ke Excel

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
- Check file permissions
- Verify .env configuration
- Check Laravel logs: `storage/logs/laravel.log`

#### 2. Database Connection Failed
- Verify database credentials
- Check database service status
- Ensure database exists

#### 3. Blank Page
- Enable debug mode temporarily: `APP_DEBUG=true`
- Check PHP error logs
- Verify all dependencies are installed

#### 4. Upload/Download Issues
- Check storage permissions
- Verify storage link is created
- Check PHP upload limits

## Setup untuk Development

### Install Development Dependencies
```bash
composer install
npm install
```

### Enable Development Features
```env
APP_ENV=local
APP_DEBUG=true
```

### Run Development Server
```bash
php artisan serve
npm run dev
```

## Security Recommendations

### 1. SSL Configuration
- Install SSL certificate
- Redirect HTTP ke HTTPS
- Update APP_URL ke HTTPS

### 2. Firewall Configuration
- Block direct access to sensitive directories
- Allow only necessary ports
- Implement rate limiting

### 3. Regular Maintenance
- Backup database regularly
- Update dependencies
- Monitor logs
- Clear caches periodically

## Support

Jika mengalami masalah selama instalasi:

1. Check [Troubleshooting Guide](../troubleshooting/README.md)
2. Review [FAQ](../user-manual/FAQ.md)
3. Contact support team
4. Check GitHub Issues

---

**Version**: 1.0.0
**Last Updated**: December 2024
**Compatible**: PHP 8.1+, Laravel 11