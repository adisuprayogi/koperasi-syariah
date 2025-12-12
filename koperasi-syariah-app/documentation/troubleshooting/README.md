# Troubleshooting Guide

## Daftar Isi

1. [Quick Reference](#quick-reference)
2. [Installation Issues](#installation-issues)
3. [Login & Authentication](#login--authentication)
4. [Database Problems](#database-problems)
5. [Performance Issues](#performance-issues)
6. [File Upload & Download](#file-upload--download)
7. [Export & Report Generation](#export--report-generation)
8. [Email & Notifications](#email--notifications)
9. [Mobile & Browser Issues](#mobile--browser-issues)
10. [System Errors](#system-errors)

## Quick Reference

### Most Common Issues & Solutions

| Problem | Quick Fix |
|---------|-----------|
| **500 Internal Server Error** | Check Laravel logs: `storage/logs/laravel.log` |
| **Cannot login** | Clear browser cache, verify credentials |
| **White blank page** | Enable debug mode: `APP_DEBUG=true` in `.env` |
| **Database connection failed** | Check database credentials in `.env` |
| **File upload error** | Check folder permissions: `storage/app/public` |
| **PDF generation fails** | Check DOMPDF configuration and memory limit |
| **Export Excel error** | Verify `maatwebsite/excel` package installation |
| **Email not sending** | Check SMTP configuration in `.env` |

### Essential Commands

```bash
# Clear all caches (first thing to try)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check Laravel version and environment
php artisan --version
php artisan env

# Check database connection
php artisan tinker
> DB::connection()->getPdo();

# Check file permissions
ls -la storage/
ls -la bootstrap/cache/

# Check composer packages
composer validate
composer dump-autoload
```

## Installation Issues

### "Class not found" or "Target class does not exist"

**Symptoms**:
- Error 500 with "Class [SomeClass] not found"
- Composer autoloader issues

**Solutions**:

1. **Regenerate autoloader**:
```bash
composer dump-autoload -o
```

2. **Check composer.json**:
```bash
composer validate
```

3. **Reinstall dependencies**:
```bash
composer install --no-dev --optimize-autoloader
```

4. **Clear Laravel cache**:
```bash
php artisan cache:clear
php artisan config:clear
```

### "SQLSTATE[HY000] [2002] Connection refused"

**Symptoms**:
- Database connection error
- Can't connect to MySQL/MariaDB

**Solutions**:

1. **Check database service**:
```bash
# MySQL
sudo systemctl status mysql
sudo systemctl start mysql

# MariaDB
sudo systemctl status mariadb
sudo systemctl start mariadb
```

2. **Verify .env configuration**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koperasi_syariah
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

3. **Test database connection**:
```bash
mysql -u username -p -h localhost
```

4. **Check if database exists**:
```sql
SHOW DATABASES LIKE 'koperasi_syariah';
CREATE DATABASE IF NOT EXISTS koperasi_syariah;
```

### "No application encryption key has been specified"

**Symptoms**:
- Error about missing APP_KEY
- Unable to encrypt/decrypt data

**Solutions**:

1. **Generate new key**:
```bash
php artisan key:generate
```

2. **Verify .env file**:
```env
APP_KEY=base64:generated_key_here
```

3. **Clear cache**:
```bash
php artisan config:clear
```

## Login & Authentication

### "Invalid credentials" despite correct password

**Symptoms**:
- Cannot login with correct credentials
- Password reset not working

**Solutions**:

1. **Check user password hash**:
```bash
php artisan tinker
> $user = App\Models\User::where('email', 'user@example.com')->first();
> $user->password = Hash::make('new_password');
> $user->save();
```

2. **Verify email and case sensitivity**:
```bash
php artisan tinker
> $user = App\Models\User::where('email', 'user@example.com')->first();
> echo $user ? 'User found' : 'User not found';
```

3. **Check authentication config**:
```bash
php artisan tinker
> config('auth.defaults.guard');
> config('auth.providers.users.model');
```

### "Your email address is not verified"

**Symptoms**:
- Can't login due to email verification
- No verification email received

**Solutions**:

1. **Manually verify user**:
```bash
php artisan tinker
> $user = App\Models\User::where('email', 'user@example.com')->first();
> $user->email_verified_at = now();
> $user->save();
```

2. **Check mail configuration** in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

3. **Resend verification email**:
```bash
php artisan auth:resend user@example.com
```

### Session timeout immediately after login

**Symptoms**:
- Kicked out immediately after login
- Session not persisting

**Solutions**:

1. **Check session configuration**:
```bash
php artisan tinker
> config('session.driver');
> config('session.lifetime');
```

2. **Clear session storage**:
```bash
php artisan session:clear
rm -rf storage/framework/sessions/*
```

3. **Check file permissions**:
```bash
chmod -R 755 storage/framework/sessions
```

## Database Problems

### Migration fails with "Foreign key constraint fails"

**Symptoms**:
- Migration rollback error
- Cannot create table with foreign keys

**Solutions**:

1. **Check table order** in migrations:
   - Parent tables must be created before child tables
   - Check foreign key references exist

2. **Disable foreign key checks temporarily**:
```sql
SET FOREIGN_KEY_CHECKS=0;
-- Run migrations
SET FOREIGN_KEY_CHECKS=1;
```

3. **Check table engine**:
```sql
SHOW ENGINE INNODB STATUS;
```

### "SQLSTATE[23000] Integrity constraint violation"

**Symptoms**:
- Duplicate entry error
- Cannot insert/update due to constraint violation

**Solutions**:

1. **Check unique constraints**:
```sql
SHOW CREATE TABLE table_name;
```

2. **Handle duplicate data**:
```bash
php artisan tinker
> App\Models\Anggota::where('no_ktp', '123456789')->first();
```

3. **Use firstOrCreate**:
```php
$anggota = App\Models\Anggota::firstOrCreate(
    ['no_ktp' => '123456789'],
    ['nama_lengkap' => 'John Doe']
);
```

### Query performance issues

**Symptoms**:
- Slow page loading
- Timeout errors

**Solutions**:

1. **Check slow query log**:
```sql
SHOW VARIABLES LIKE 'slow_query_log%';
SELECT * FROM mysql.slow_log ORDER BY start_time DESC LIMIT 10;
```

2. **Add indexes**:
```sql
-- Add index for frequently queried columns
CREATE INDEX idx_anggota_status ON anggota(status_keanggotaan);
CREATE INDEX idx_transaksi_date ON transaksi_simpanan(tanggal_transaksi);
```

3. **Optimize queries**:
```php
// Use eager loading instead of lazy loading
$anggota = Anggota::with(['transaksiSimpanan', 'pengajuanPembiayaan'])->get();

// Use select only needed columns
$transaksi = TransaksiSimpanan::select('id', 'anggota_id', 'jumlah')->get();
```

## Performance Issues

### Slow page loading

**Symptoms**:
- Pages take >5 seconds to load
- High CPU usage

**Solutions**:

1. **Enable caching**:
```bash
# Production cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Check memory usage**:
```bash
php artisan tinker
> memory_get_usage(true);
> memory_get_peak_usage(true);
```

3. **Optimize composer autoloader**:
```bash
composer dump-autoload --optimize
```

4. **Use CDN for assets**:
```html
<!-- Use CDN for common libraries -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
```

### Out of memory errors

**Symptoms**:
- PHP Fatal error: Allowed memory size exhausted
- Script execution timeout

**Solutions**:

1. **Increase PHP memory limit**:
```ini
; In php.ini
memory_limit = 512M
max_execution_time = 300
```

2. **Optimize memory usage in code**:
```php
// Use chunking for large datasets
TransaksiSimpanan::chunk(1000, function ($transaksi) {
    foreach ($transaksi as $t) {
        // Process each record
    }
});

// Free memory explicitly
unset($largeVariable);
```

3. **Increase Laravel memory limit**:
```php
// In Controller method
ini_set('memory_limit', '512M');
```

## File Upload & Download

### "The file could not be uploaded"

**Symptoms**:
- Upload fails silently
- File size too large error

**Solutions**:

1. **Check PHP upload limits**:
```ini
; In php.ini
upload_max_filesize = 50M
post_max_size = 50M
max_file_uploads = 20
```

2. **Check Laravel configuration**:
```php
// In config/filesystems.php
'default' => 'local',
'local' => [
    'driver' => 'local',
    'root' => storage_path('app'),
],
```

3. **Verify folder permissions**:
```bash
chmod -R 755 storage/app/public
chmod -R 777 storage/framework/cache
```

4. **Check disk space**:
```bash
df -h
```

### Download fails or corrupt files

**Symptoms**:
- Downloaded file is empty/corrupt
- Download doesn't start

**Solutions**:

1. **Check file existence**:
```php
$path = storage_path('app/public/filename.pdf');
if (!file_exists($path)) {
    abort(404, 'File not found');
}
```

2. **Set proper headers**:
```php
return response()->download($path, 'filename.pdf', [
    'Content-Type' => 'application/pdf',
]);
```

3. **Check file permissions**:
```bash
ls -la storage/app/public/
chmod 644 filename.pdf
```

## Export & Report Generation

### Excel export fails

**Symptoms**:
- Download returns HTML instead of Excel
- Excel file is corrupted

**Solutions**:

1. **Check maatwebsite/excel installation**:
```bash
composer show maatwebsite/excel
composer require maatwebsite/excel
```

2. **Check export class**:
```php
// Verify class implements FromView
class MyExport implements FromView
{
    public function view(): View
    {
        return view('exports.my-template', $this->data);
    }
}
```

3. **Check route configuration**:
```php
Route::get('/export/excel', [ExportController::class, 'excel'])->name('export.excel');
```

### PDF generation fails

**Symptoms**:
- PDF download fails
- PDF shows blank page

**Solutions**:

1. **Check DOMPDF configuration**:
```php
// config/dompdf.php
'default_font' => 'Arial',
'paper_size' => 'a4',
'default_orientation' => 'portrait'
```

2. **Increase memory limit**:
```php
// In controller method
ini_set('memory_limit', '512M');
```

3. **Check HTML syntax**:
```html
<!-- Ensure all tags are properly closed -->
<div>
    <h1>My Report</h1>
    <p>Content here</p>
</div>
```

4. **Use absolute paths for images**:
```php
$url = asset('images/logo.png');
```

## Email & Notifications

### Email not sending

**Symptoms**:
- No email received
- Connection timeout errors

**Solutions**:

1. **Test mail configuration**:
```bash
php artisan tinker
> Mail::raw('Test email', function ($message) {
>     $message->to('test@example.com')->subject('Test');
> });
```

2. **Check .env mail settings**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@koperasi.com"
```

3. **Check firewall**:
```bash
telnet smtp.gmail.com 587
```

4. **Use mail driver for testing**:
```env
MAIL_MAILER=log
```

## Mobile & Browser Issues

### Responsive design not working on mobile

**Symptoms**:
- Layout broken on mobile devices
- Horizontal scrolling required

**Solutions**:

1. **Add viewport meta tag**:
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

2. **Use responsive CSS classes**:
```html
<div class="col-12 col-md-6 col-lg-4">Content</div>
```

3. **Test in mobile browser**:
- Use Chrome DevTools device simulation
- Test on actual devices

### Safari specific issues

**Symptoms**:
- Features not working in Safari
- Styling problems

**Solutions**:

1. **Add Safari-specific CSS**:
```css
/* Safari-specific fixes */
@supports (-webkit-appearance: none) {
    .safari-fix {
        /* Safari-specific styles */
    }
}
```

2. **Check JavaScript compatibility**:
```javascript
// Use modern JavaScript with polyfills
if (!window.Promise) {
    // Load promise polyfill
}
```

3. **Test on different Safari versions**:
- Test on iOS and macOS
- Check browser console for errors

## System Errors

### 404 Not Found errors

**Symptoms**:
- Pages return 404 error
- Routes not working

**Solutions**:

1. **Clear route cache**:
```bash
php artisan route:clear
php artisan route:list
```

2. **Check .htaccess file**:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

3. **Check Apache configuration**:
```apache
<Directory "/var/www/html/koperasi/public">
    AllowOverride All
    Require all granted
</Directory>
```

### Laravel Framework errors

**Symptoms**:
- "Route not defined"
- "View not found"
- "Class not found"

**Solutions**:

1. **Check route definitions**:
```bash
php artisan route:list --name=route.name
```

2. **Check view files**:
```bash
ls -la resources/views/
```

3. **Run composer dump-autoload**:
```bash
composer dump-autoload
```

### Memory exhaustion in production

**Symptoms**:
- Server crashes under load
- 500 errors intermittently

**Solutions**:

1. **Monitor memory usage**:
```bash
top -p $(pgrep php-fpm)
```

2. **Optimize queries** (see Database section)

3. **Implement queue system**:
```bash
php artisan queue:work
```

4. **Use Laravel Horizon** for monitoring:
```bash
composer require laravel/horizon
```

## Emergency Procedures

### Complete system recovery

1. **Backup current state**:
```bash
mysqldump -u root -p koperasi_syariah > emergency_backup.sql
tar -czf code_backup.tar.gz --exclude=node_modules --exclude=vendor .
```

2. **Restore from backup**:
```bash
mysql -u root -p koperasi_syariah < backup_file.sql
```

3. **Clear all caches**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan queue:flush
```

### Emergency password reset

```bash
php artisan tinker
> $user = App\Models\User::where('email', 'admin@koperasi.com')->first();
> $user->password = Hash::make('emergency_password');
> $user->save();
> exit;
```

## Monitoring & Prevention

### Set up monitoring

1. **Enable Laravel logging**:
```php
// config/logging.php
'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => env('LOG_LEVEL', 'debug'),
    'days' => 14,
],
```

2. **Monitor error logs**:
```bash
tail -f storage/logs/laravel.log
```

3. **Set up alerts** for critical errors:
```php
// In App/Exceptions/Handler.php
public function report(Throwable $exception)
{
    if ($exception instanceof CriticalError) {
        // Send alert to admin
    }

    parent::report($exception);
}
```

### Regular maintenance

1. **Weekly tasks**:
```bash
# Clear old logs
find storage/logs -name "*.log" -mtime +30 -delete

# Optimize database
php artisan db:optimize

# Update dependencies
composer update --dry-run
```

2. **Monthly tasks**:
```bash
# Database backup
mysqldump -u root -p koperasi_syariah > monthly_backup.sql

# Update composer packages
composer update

# Check security vulnerabilities
composer audit
```

## Contact Support

If you've tried all the above solutions and still experiencing issues:

### Information to Provide
- Laravel version (`php artisan --version`)
- PHP version (`php --version`)
- Database version
- Complete error message
- Steps to reproduce
- Server environment details

### Support Channels
- **Email**: support@koperasi.com
- **Phone**: (021) 1234-5678
- **GitHub Issues**: Create new issue with detailed description
- **Documentation**: Check latest online documentation

---

**Version**: 1.0.0
**Last Updated**: December 2024
**Next Review**: March 2025