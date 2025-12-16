<?php
/**
 * Simple Debug Tool - Check Basic Requirements
 * URL: https://your-domain.com/simple-debug.php
 */

echo "=== SIMPLE DEBUG TOOL ===\n\n";

// Check current directory
echo "Current Directory: " . __DIR__ . "\n";
echo "Working Directory: " . getcwd() . "\n\n";

// Check PHP Version
echo "PHP Version: " . phpversion() . "\n\n";

// Check required files
echo "CHECKING REQUIRED FILES:\n";
$requiredFiles = [
    'vendor/autoload.php' => 'Composer Autoloader',
    'bootstrap/app.php' => 'Laravel Bootstrap',
    'config/app.php' => 'Laravel Config',
    '.env' => 'Environment File'
];

foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        $size = is_file($file) ? filesize($file) : 'dir';
        echo "✅ {$file} ({$size} bytes) - {$description}\n";
    } else {
        echo "❌ {$file} - MISSING! - {$description}\n";
    }
}

echo "\n";

// Check if vendor directory has required packages
echo "CHECKING VENDOR DIRECTORY:\n";
if (is_dir('vendor')) {
    echo "✅ vendor directory exists\n";

    $vendorFiles = [
        'vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
        'vendor/composer/autoload_classmap.php',
        'vendor/composer/autoload_real.php'
    ];

    foreach ($vendorFiles as $file) {
        if (file_exists($file)) {
            echo "✅ " . basename(dirname($file)) . "/" . basename($file) . "\n";
        } else {
            echo "❌ " . basename(dirname($file)) . "/" . basename($file) . " - MISSING!\n";
        }
    }
} else {
    echo "❌ vendor directory missing!\n";
    echo "   This means composer dependencies are not uploaded\n";
}

echo "\n";

// Check .env configuration
echo "CHECKING .ENV FILE:\n";
if (file_exists('.env')) {
    echo "✅ .env file exists\n";

    $envContent = file_get_contents('.env');
    $importantVars = ['APP_NAME', 'APP_ENV', 'APP_KEY', 'DB_DATABASE', 'DB_USERNAME'];

    foreach ($importantVars as $var) {
        if (strpos($envContent, $var . '=') !== false) {
            echo "✅ {$var} found\n";
        } else {
            echo "❌ {$var} missing\n";
        }
    }
} else {
    echo "❌ .env file missing!\n";
    echo "   Copy .env.production to .env\n";
}

echo "\n";

// Check file permissions
echo "CHECKING KEY PERMISSIONS:\n";
$keyFiles = [
    '.' => 'Root Directory',
    'storage' => 'Storage Directory',
    'bootstrap/cache' => 'Cache Directory',
    'public/index.php' => 'Entry Point'
];

foreach ($keyFiles as $path => $description) {
    if (file_exists($path)) {
        $perm = substr(sprintf('%o', fileperms($path)), -4);
        $readable = is_readable($path) ? 'R' : '-';
        $writable = is_writable($path) ? 'W' : '-';

        echo "{$readable}{$writable} {$path} ({$perm}) - {$description}\n";
    } else {
        echo "❌ {$path} missing - {$description}\n";
    }
}

echo "\n";

// Check URL configuration
echo "CHECKING URL CONFIGURATION:\n";
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'unknown';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';

echo "Protocol: {$protocol}\n";
echo "Host: {$host}\n";
echo "Request URI: {$requestUri}\n";
echo "Full URL: {$protocol}://{$host}{$requestUri}\n\n";

// Check if we're in public directory
echo "CHECKING PUBLIC DIRECTORY:\n";
if (file_exists('index.php')) {
    echo "✅ Running from public directory (index.php found)\n";

    // Check if this is Laravel's index.php
    $indexContent = file_get_contents('index.php');
    if (strpos($indexContent, 'laravel') !== false || strpos($indexContent, 'bootstrap/app.php') !== false) {
        echo "✅ Laravel index.php detected\n";
    } else {
        echo "⚠️  Not Laravel index.php?\n";
    }
} else {
    echo "❌ Not in public directory (index.php not found)\n";
    echo "   Document root should point to /public directory\n";
}

echo "\n";

// Recommendations
echo "🔧 COMMON FIXES:\n\n";

if (!file_exists('vendor/autoload.php')) {
    echo "1. UPLOAD VENDOR DIRECTORY:\n";
    echo "   - Download complete vendor folder from local\n";
    echo "   - Or run 'composer install' locally\n";
    echo "   - Upload entire vendor directory to server\n\n";
}

if (!file_exists('.env')) {
    echo "2. SETUP ENVIRONMENT:\n";
    echo "   - Copy .env.production to .env\n";
    echo "   - Edit database credentials in .env\n";
    echo "   - Run server-keygen.php to generate APP_KEY\n\n";
}

if (!file_exists('index.php')) {
    echo "3. FIX DOCUMENT ROOT:\n";
    echo "   - cPanel → Domains → Modify Domain\n";
    echo "   - Change Document Root to end with /public\n";
    echo "   - Example: /public_html/domain/app/public\n\n";
}

echo "4. RUN SETUP SCRIPTS:\n";
echo "   - server-keygen.php (generate APP_KEY)\n";
echo "   - run-migrations.php (database setup)\n";
echo "   - create-symlink.php (storage link)\n";
echo "   - clear-cache.php (clear caches)\n\n";

echo "5. CHECK FILE PERMISSIONS:\n";
echo "   - storage/ directory should be writable (755)\n";
echo "   - bootstrap/cache/ should be writable (755)\n";
echo "   - All PHP files should be readable (644)\n\n";

echo "📋 NEXT STEPS:\n";
echo "1. Fix all ❌ items above\n";
echo "2. Upload missing files\n";
echo "3. Set correct document root\n";
echo "4. Test application\n\n";

echo "🔍 DETAILED TROUBLESHOOTING:\n";
echo "If vendor directory exists but Laravel still fails:\n";
echo "1. Check if files were uploaded in BINARY mode\n";
echo "2. Verify composer.lock matches vendor directory\n";
echo "3. Check for corrupted files\n";
echo "4. Try re-uploading vendor directory\n\n";

echo "=== END OF SIMPLE DEBUG ===\n";
?>