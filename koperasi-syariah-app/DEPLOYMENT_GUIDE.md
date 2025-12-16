# 📋 Deployment Instructions - Koperasi Syariah App

## 🚀 Quick Deploy Checklist

### 1️⃣ **File Structure Upload**
Upload these files/folders to your cPanel:

```
/app/                <- Application core
/bootstrap/          <- Laravel bootstrap
/config/             <- Configuration files
/database/           <- Database migrations & seeds
/public/             <- Public web files (DOCUMENT ROOT)
/resources/          <- Views, assets, languages
/routes/             <- Web routes
/storage/            <- Storage directory (writable)
/vendor/             <- Composer dependencies
.env.production      <- Environment configuration
composer.json        <- Composer configuration
composer.lock        <- Dependency lock file
artisan              <- Laravel command line tool
.htaccess            <- Apache configuration
index.php            <- Entry point
```

### 2️⃣ **Critical Configuration Steps**

#### 📁 **Document Root Configuration**
- Set your document root to: `/public_html/your-domain/app/public`
- NOT: `/public_html/your-domain/app`

#### 🔐 **Environment Setup**
1. Copy `.env.production` to `.env`
2. Update values in `.env`:
   ```bash
   APP_NAME="Koperasi Syariah"
   APP_ENV=production
   APP_KEY=base64:YOUR_GENERATED_KEY_HERE
   APP_DEBUG=false
   APP_URL=https://your-domain.com

   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

#### ⚡ **Generate APP_KEY**
Run `server-keygen.php` via browser:
```
https://your-domain.com/server-keygen.php
```

#### 🔧 **Run Database Migrations**
Visit via browser:
```
https://your-domain.com/run-migrations.php
```

#### 🔗 **Create Storage Link**
Visit via browser:
```
https://your-domain.com/create-symlink.php
```

#### 🗂️ **Set File Permissions**
Use cPanel File Manager or run:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 3️⃣ **New Features Added**

#### ✅ **Member Status Management**
- Status: aktif, tidak_aktif, keluar
- Automatic user account deactivation when status = keluar
- Join date tracking (tanggal_gabung)
- Exit tracking (tanggal_keluar, alasan_keluar)

#### ✅ **Enhanced Excel Import (17 Columns)**
- **A-M**: Existing member data fields
- **N**: status_keanggotaan (aktif/tidak_aktif/keluar)
- **O**: tanggal_gabung (WAJIB - YYYY-MM-DD)
- **P**: tanggal_keluar (optional - YYYY-MM-DD)
- **Q**: alasan_keluar (optional - text)

#### ✅ **Improved UI/UX**
- Modern status management forms
- Better visual design with Tailwind CSS
- Professional member exit forms with validation

### 4️⃣ **Deployment Scripts Included**

| Script | Purpose |
|--------|---------|
| `server-keygen.php` | Generate new APP_KEY |
| `run-migrations.php` | Run database migrations |
| `create-symlink.php` | Create storage symbolic link |
| `clear-cache.php` | Clear all Laravel caches |
| `check-permissions.php` | Verify file permissions |
| `create-production-backup.sh` | Server backup script |

### 5️⃣ **Testing After Deployment**

1. ✅ **Homepage loads correctly**
2. ✅ **Login pages work** (admin: pengurus@koperasi.local / password123)
3. ✅ **Import Excel feature works** with new status fields
4. ✅ **Member status management works** (aktif/keluar)
5. ✅ **PDF generation works** for member cards
6. ✅ **All images display correctly**

### 6️⃣ **Troubleshooting**

| Issue | Solution |
|-------|----------|
| 500 Error | Check `.env` configuration and permissions |
| 404 Errors | Verify document root points to `/public` |
| Images not showing | Run `clear-cache.php` and check permissions |
| PDF not working | Verify DomPDF is in vendor directory |
| Login fails | Check database connection and run migrations |

### 7️⃣ **URL Routes Available**

- **Landing**: `/` (auto-redirect by role)
- **Login**: `/login`
- **Admin Dashboard**: `/admin/dashboard`
- **Pengurus Dashboard**: `/pengurus/dashboard`
- **Member Management**: `/pengurus/anggota`
- **Import Members**: `/pengurus/anggota/import`
- **Member Status**: `/pengurus/anggota/{id}/keluar`
- **Documentation**: `/documentation/user-manual-pdf`

---

## 🎯 **IMPORTANT NOTES**

### ⚠️ **Must Do After Upload**
1. Set document root to `/public` folder
2. Copy `.env.production` → `.env` and configure
3. Generate APP_KEY with `server-keygen.php`
4. Run migrations with `run-migrations.php`
5. Create storage link with `create-symlink.php`

### 🔒 **Security**
- APP_DEBUG set to false in production
- Error reporting disabled
- Secure file permissions applied
- Database credentials protected

### 📊 **New Status Management Features**
- Members can be marked as "keluar" with dates and reasons
- User accounts automatically deactivated when members leave
- Complete audit trail for member status changes
- Excel import supports all status fields

### 📞 **Support**
If you encounter issues:
1. Check this guide first
2. Run diagnostic scripts provided
3. Verify all steps in deployment checklist

---

**Deployment Date**: <?= date('Y-m-d H:i:s') ?>
**Version**: Laravel 11 with Status Management v2.0
**Package**: koperasi-syariah-production-<?= date('Y-m-d_H-i-s') ?>.tar.gz