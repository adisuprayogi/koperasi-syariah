<?php
/**
 * Laravel Diagnostic Tool
 * Run this to identify 500 errors
 * URL: https://your-domain.com/diagnostic.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== LARAVEL DIAGNOSTIC TOOL ===\n\n";

// Step 1: Basic PHP info
echo "🔍 PHP Version: " . phpversion() . "\n";
echo "📁 Current Directory: " . __DIR__ . "\n";
echo "📁 Working Directory: " . getcwd() . "\n\n";

// Step 2: Check file structure
echo "📋 CHECKING FILE STRUCTURE:\n";
$requiredFiles = [
    'vendor/autoload.php',
    'bootstrap/app.php',
    'config/app.php',
    'routes/web.php',
    'app/Http/Kernel.php',
    '.env'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        $size = is_file($file) ? filesize($file) : 'dir';
        $perm = substr(sprintf('%o', fileperms($file)), -4);
        echo "✅ {$file} ({$size}, {$perm})\n";
    } else {
        echo "❌ {$file} - MISSING!\n";
    }
}

echo "\n";

// Step 3: Check .env file
echo "🔐 CHECKING .ENV FILE:\n";
if (file_exists('.env')) {
    echo "✅ .env file exists\n";

    $envContent = file_get_contents('.env');
    $checks = [
        'APP_NAME' => 'APP_NAME',
        'APP_ENV' => 'APP_ENV',
        'APP_KEY' => 'APP_KEY=base64:',
        'APP_URL' => 'APP_URL',
        'DB_CONNECTION' => 'DB_CONNECTION',
        'DB_DATABASE' => 'DB_DATABASE',
        'DB_USERNAME' => 'DB_USERNAME'
    ];

    foreach ($checks as $name => $search) {
        if (strpos($envContent, $search) !== false) {
            echo "✅ {$name} found\n";
        } else {
            echo "❌ {$name} missing\n";
        }
    }
} else {
    echo "❌ .env file missing!\n";
    echo "   Copy .env.production to .env\n";
}

echo "\n";

// Step 4: Check vendor directory
echo "📦 CHECKING VENDOR:\n";
if (is_dir('vendor')) {
    echo "✅ vendor directory exists\n";

    // Check key vendor files
    $vendorFiles = [
        'vendor/autoload.php',
        'vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
        'vendor/composer/autoload_classmap.php'
    ];

    foreach ($vendorFiles as $file) {
        if (file_exists($file)) {
            echo "✅ " . basename($file) . "\n";
        } else {
            echo "❌ " . basename($file) . " missing\n";
        }
    }
} else {
    echo "❌ vendor directory missing!\n";
    echo "   Upload vendor directory from local machine\n";
}

echo "\n";

// Step 5: Try to load Laravel
echo "🚀 TESTING LARAVEL LOADING:\n";

try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "✅ Composer autoloader loaded\n";

        if (file_exists('bootstrap/app.php')) {
            $app = require_once 'bootstrap/app.php';
            echo "✅ Laravel application loaded\n";

            // Test basic Laravel functionality
            if ($app instanceof \Illuminate\Foundation\Application) {
                echo "✅ Laravel app instance created\n";

                // Check environment
                $env = $app->environment();
                echo "📊 Environment: {$env}\n";

                // Check encryption key
                if ($app['config']['app.key']) {
                    $keyLength = strlen($app['config']['app.key']);
                    echo "✅ APP_KEY set (length: {$keyLength})\n";

                    if (strlen($app['config']['app.key']) < 32) {
                        echo "⚠️  APP_KEY seems too short\n";
                    }
                } else {
                    echo "❌ APP_KEY not set!\n";
                }

            } else {
                echo "❌ Failed to create Laravel app instance\n";
            }
        } else {
            echo "❌ bootstrap/app.php not found\n";
        }
    } else {
        echo "❌ Cannot load autoloader\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR loading Laravel: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n";

// Step 6: Check storage permissions
echo "📁 CHECKING STORAGE PERMISSIONS:\n";
$storageDirs = [
    'storage',
    'storage/app',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($storageDirs as $dir) {
    if (is_dir($dir)) {
        $perm = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir) ? '✅' : '❌';
        echo "{$writable} {$dir} ({$perm})\n";
    } else {
        echo "❌ {$dir} - MISSING\n";
    }
}

echo "\n";

// Step 7: Check database connection (if Laravel loads)
echo "🗄️  TESTING DATABASE CONNECTION:\n";
try {
    if (file_exists('vendor/autoload.php') && file_exists('bootstrap/app.php')) {
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';

        try {
            $db = $app['db'];
            $db->getPdo();
            echo "✅ Database connection successful\n";

            // Check if migrations table exists
            if ($db->getSchemaBuilder()->hasTable('migrations')) {
                echo "✅ Migrations table exists\n";

                // Count migrations
                $migrationCount = $db->table('migrations')->count();
                echo "📊 {$migrationCount} migrations run\n";
            } else {
                echo "⚠️  Migrations table not found\n";
                echo "   Run: /run-migrations.php\n";
            }

        } catch (\Exception $e) {
            echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        }
    }
} catch (Exception $e) {
    echo "⚠️  Cannot test database - Laravel not loaded\n";
}

echo "\n";

// Step 8: Check for common issues
echo "🔍 COMMON ISSUES CHECK:\n";

// Check if document root is correct
$expectedPublic = realpath('public');
$actualCurrent = realpath(__DIR__);

if ($expectedPublic === $actualCurrent) {
    echo "✅ Running from public directory\n";
} else {
    echo "❌ NOT running from public directory!\n";
    echo "   Current: {$actualCurrent}\n";
    echo "   Should be: {$expectedPublic}\n";
    echo "   Check cPanel Document Root setting\n";
}

// Check if .htaccess exists in public
if (file_exists('public/.htaccess')) {
    echo "✅ .htaccess exists in public folder\n";
} else {
    echo "❌ .htaccess missing in public folder!\n";
}

// Check storage link
if (is_link('public/storage')) {
    echo "✅ Storage symlink exists\n";
} else {
    echo "⚠️  Storage symlink missing\n";
    echo "   Run: /create-symlink.php\n";
}

echo "\n";

// Step 9: Recommendations
echo "💡 RECOMMENDATIONS:\n";

if (!file_exists('.env')) {
    echo "1. Copy .env.production to .env\n";
}

if (file_exists('.env') && strpos(file_get_contents('.env'), 'APP_KEY=base64:') === false) {
    echo "2. Run /server-keygen.php to generate APP_KEY\n";
}

if (!is_dir('vendor')) {
    echo "3. Upload vendor directory\n";
}

if (!file_exists('storage/framework/cache/.gitignore')) {
    echo "4. Run /run-migrations.php to setup storage\n";
}

if (!is_link('public/storage')) {
    echo "5. Run /create-symlink.php to create storage link\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
echo "📧 Copy this output for support if needed\n";
?>