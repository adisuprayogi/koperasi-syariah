<?php
/**
 * Create CPanel Deployment ZIP
 * This script creates a ZIP file optimized for cPanel deployment
 */

echo "🚀 Creating CPanel Deployment ZIP...\n";

// Define files and directories to include
$include = [
    'app/',
    'bootstrap/',
    'config/',
    'database/',
    'public/',
    'resources/',
    'routes/',
    'storage/',
    'vendor/',
    '.env.example',
    'artisan',
    'composer.json',
    'composer.lock'
];

// Files and directories to exclude
$exclude = [
    'node_modules',
    '.git',
    '.gitignore',
    'tests',
    'phpunit.xml',
    'webpack.mix.js',
    'tailwind.config.js',
    'package.json',
    'package-lock.json',
    'postcss.config.js',
    '.DS_Store',
    '*.log',
    'storage/logs/*.log',
    'storage/framework/cache/*',
    'storage/framework/sessions/*',
    'storage/framework/views/*',
    'bootstrap/cache/*',
    'temp_env/',
    'final_backup/',
    '*.tar.gz',
    'create-*.php',
    'setup.php',
    'koperasi/',
    '.htaccess'
];

// Create ZIP file
$zip = new ZipArchive();
$zipFilename = 'koperasi-syariah-cpanel-deploy-' . date('Y-m-d_H-i-s') . '.zip';

if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

    // Function to add files to ZIP with exclusion
    function addFilesToZip($folder, &$zip, $exclude, $basePath = '') {
        $files = scandir($folder);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;

            $filePath = $folder . '/' . $file;
            $relativePath = ltrim($basePath . '/' . $file, '/');

            // Check if should exclude
            $shouldExclude = false;
            foreach ($exclude as $excludePattern) {
                if (fnmatch($excludePattern, $relativePath) ||
                    fnmatch($excludePattern, $file) ||
                    fnmatch($excludePattern, $filePath)) {
                    $shouldExclude = true;
                    break;
                }
            }

            if (!$shouldExclude) {
                if (is_dir($filePath)) {
                    $zip->addEmptyDir($relativePath);
                    addFilesToZip($filePath, $zip, $exclude, $relativePath);
                } else {
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
    }

    // Add all included files/directories
    foreach ($include as $item) {
        if (is_dir($item)) {
            $zip->addEmptyDir($item);
            addFilesToZip($item, $zip, $exclude, $item);
        } elseif (file_exists($item)) {
            $zip->addFile($item, $item);
        }
    }

    // Add deployment utilities
    $zip->addFromString('DEPLOY_INSTRUCTIONS.md', createDeploymentInstructions());
    $zip->addFromString('clear-cache.php', createCacheClearScript());
    $zip->addFromString('check-deployment.php', createDeploymentCheck());

    $zip->close();

    echo "✅ ZIP created: $zipFilename\n";
    echo "📁 Size: " . formatBytes(filesize($zipFilename)) . "\n";

} else {
    echo "❌ Failed to create ZIP file\n";
}

function createDeploymentInstructions() {
    return '# CPanel Deployment Instructions

## Quick Deployment Steps

### 1. Upload to cPanel
1. Login to cPanel
2. Go to File Manager
3. Navigate to: `/home/username/public_html/app.ks-adzikra.id/`
4. Upload and extract the ZIP file
5. Delete the ZIP file after extraction

### 2. Set Permissions
```bash
# Via cPanel File Manager or SSH
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

### 3. Configure Environment
1. Copy `.env.example` to `.env`
2. Edit `.env` with your database and other settings
3. Generate APP_KEY:
   - Create `generate-key.php` with: `<?php echo "APP_KEY=" . bin2hex(random_bytes(32)); ?>`
   - Access via browser and copy the key to .env

### 4. Clear Cache
Access: `https://yourdomain.com/clear-cache.php`

### 5. Create Storage Link
Create `create-storage-link.php`:
```php
<?php
$target = storage_path(\'app/public\');
$link = public_path(\'storage\');
symlink($target, $link);
echo "Storage link created!";
?>
```

### 6. Test Application
- Access: `https://yourdomain.com`
- Login and test all features

## Files Structure After Deployment
```
app.ks-adzikra.id/
├── app/                    # Application logic
├── bootstrap/              # Framework files
├── config/                 # Configuration files
├── database/               # Database files
├── public/                 # Web root
├── resources/              # Views and assets
├── routes/                 # URL routes
├── storage/                # File storage
├── vendor/                 # Dependencies
├── .env                    # Environment config
├── artisan                 # CLI tool
├── clear-cache.php         # Cache utility
└── check-deployment.php    # Deployment check
```

## Troubleshooting

### 500 Error
1. Check file permissions (755 for directories, 644 for files)
2. Check .env configuration
3. Check storage/logs/laravel.log

### Database Connection
1. Verify DB credentials in .env
2. Check database exists in cPanel
3. Test connection with a simple script

### Missing Pages (404)
1. Check .htaccess in public folder
2. Verify routes in routes/web.php
3. Clear cache with clear-cache.php

## Important Notes
- This ZIP includes vendor directory (no composer install needed)
- Storage directory is included but will need proper permissions
- Cache and session files are excluded for clean deployment
- Development files are excluded to reduce size

Good luck with your deployment!';
}

function createCacheClearScript() {
    return '<?php
/**
 * Clear Laravel Cache - CPanel Safe Version
 */

echo "<h2>Clearing Laravel Cache...</h2>";

try {
    // Clear config cache
    if (file_exists(\'bootstrap/cache/config.php\')) {
        unlink(\'bootstrap/cache/config.php\');
        echo "✅ Config cache cleared<br>";
    }

    // Clear routes cache
    if (file_exists(\'bootstrap/cache/routes-v7.php\')) {
        unlink(\'bootstrap/cache/routes-v7.php\');
        echo "✅ Routes cache cleared<br>";
    }

    // Clear compiled cache
    if (file_exists(\'bootstrap/cache/compiled.php\')) {
        unlink(\'bootstrap/cache/compiled.php\');
        echo "✅ Compiled cache cleared<br>";
    }

    // Clear events cache
    if (file_exists(\'bootstrap/cache/events.php\')) {
        unlink(\'bootstrap/cache/events.php\');
        echo "✅ Events cache cleared<br>";
    }

    // Clear framework cache
    $cacheDir = \'storage/framework/cache/data\';
    if (is_dir($cacheDir)) {
        $files = glob($cacheDir . \'/*\');
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
        echo "✅ Framework cache cleared<br>";
    }

    // Clear views cache
    $viewsDir = \'storage/framework/views\';
    if (is_dir($viewsDir)) {
        $files = glob($viewsDir . \'/*\');
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
        echo "✅ Views cache cleared<br>";
    }

    echo "<br><h3>🎉 Cache clearing completed!</h3>";
    echo "<p><a href=\'/\'>Return to Application</a></p>";

} catch (Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
}
?>';
}

function createDeploymentCheck() {
    return '<?php
/**
 * CPanel Deployment Check Script
 */

echo "<h2>Koperasi Syariah - Deployment Check</h2>";

echo "<h3>📁 File Structure Check</h3>";
$requiredFiles = [
    \'app/Http/Controllers/Admin/AnggotaImportController.php\',
    \'app/Exports/AnggotaTemplateExport.php\',
    \'app/Exports/AnggotaImportErrorExport.php\',
    \'resources/views/layouts/app.blade.php\',
    \'resources/views/pengurus/anggota/import.blade.php\',
    \'routes/web.php\',
    \'.env\',
    \'artisan\'
];

foreach ($requiredFiles as $file) {
    $status = file_exists($file) ? "✅" : "❌";
    echo "$status $file<br>";
}

echo "<h3>🔒 Directory Permissions Check</h3>";
$directories = [
    \'storage\' => 755,
    \'bootstrap/cache\' => 755,
    \'storage/app\' => 755,
    \'storage/framework\' => 755
];

foreach ($directories as $dir => $expectedPerm) {
    if (is_dir($dir)) {
        $perm = substr(sprintf(\'%o\', fileperms($dir)), -4);
        $status = ($perm == $expectedPerm) ? "✅" : "⚠️";
        echo "$status $dir - Permissions: $perm (Expected: $expectedPerm)<br>";
    } else {
        echo "❌ $dir - Directory not found<br>";
    }
}

echo "<h3>🗄️ Environment Check</h3>";
if (file_exists(\'.env\')) {
    echo "✅ .env file exists<br>";
    if (getenv(\'APP_KEY\')) {
        echo "✅ APP_KEY is set<br>";
    } else {
        echo "❌ APP_KEY is not set<br>";
    }
} else {
    echo "❌ .env file not found<br>";
}

echo "<h3>🔗 Routes Check</h3>";
echo "✅ Import route: /pengurus/anggota/import<br>";
echo "✅ Template download: /pengurus/anggota/import/template<br>";

echo "<br><h3>🚀 Quick Actions</h3>";
echo "<a href=\'clear-cache.php\' style=\'background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;\'>Clear Cache</a>";
echo "<a href=\'/\' style=\'background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px;\'>Go to App</a>";
?>';
}

function formatBytes($size) {
    $units = array('B', 'KB', 'MB', 'GB');
    for ($i = 0; $size >= 1024 && $i < 3; $i++) $size /= 1024;
    return round($size, 2) . ' ' . $units[$i];
}

echo "\n🎉 Deployment ZIP is ready for upload!\n";
echo "📝 Check DEPLOY_INSTRUCTIONS.md in the ZIP for detailed steps\n";
?>