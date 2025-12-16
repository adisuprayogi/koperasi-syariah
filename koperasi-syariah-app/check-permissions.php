<?php
/**
 * File Permissions Checker
 * Run this script via browser to check and report file permissions
 * URL: https://your-domain.com/check-permissions.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== Koperasi Syariah - File Permissions Checker ===\n\n";

$requiredDirs = [
    'storage' => [755, 'writable'],
    'storage/app' => [755, 'writable'],
    'storage/app/public' => [755, 'writable'],
    'storage/framework' => [755, 'writable'],
    'storage/framework/cache' => [755, 'writable'],
    'storage/framework/sessions' => [755, 'writable'],
    'storage/framework/views' => [755, 'writable'],
    'storage/logs' => [755, 'writable'],
    'bootstrap/cache' => [755, 'writable'],
    'public' => [755, 'readable'],
    'public/storage' => [777, 'readable'], // Should be symlink
];

$requiredFiles = [
    '.env' => [644, 'readable'],
    'artisan' => [755, 'executable'],
    'composer.json' => [644, 'readable'],
    'public/index.php' => [644, 'readable'],
    'public/.htaccess' => [644, 'readable'],
];

$issues = [];
$warnings = [];

echo "📋 Checking directory permissions...\n\n";

foreach ($requiredDirs as $dir => $expected) {
    $expectedPerm = $expected[0];
    $expectedType = $expected[1];

    echo "📁 {$dir}\n";

    if (!file_exists($dir)) {
        echo "   ❌ Directory does not exist!\n";
        $issues[] = "Missing directory: {$dir}";
        continue;
    }

    if (!is_dir($dir)) {
        echo "   ❌ Not a directory!\n";
        $issues[] = "Not a directory: {$dir}";
        continue;
    }

    $actualPerm = substr(sprintf('%o', fileperms($dir)), -4);
    $isWritable = is_writable($dir);
    $isReadable = is_readable($dir);

    echo "   📊 Permission: {$actualPerm} (expected: " . decoct($expectedPerm) . ")\n";

    if ($expectedType === 'writable' && !$isWritable) {
        echo "   ❌ Not writable!\n";
        $issues[] = "Directory not writable: {$dir} ({$actualPerm})";
    } elseif ($expectedType === 'readable' && !$isReadable) {
        echo "   ❌ Not readable!\n";
        $issues[] = "Directory not readable: {$dir} ({$actualPerm})";
    } else {
        echo "   ✅ OK\n";
    }

    echo "\n";
}

echo "📋 Checking file permissions...\n\n";

foreach ($requiredFiles as $file => $expected) {
    $expectedPerm = $expected[0];
    $expectedType = $expected[1];

    echo "📄 {$file}\n";

    if (!file_exists($file)) {
        echo "   ❌ File does not exist!\n";
        $issues[] = "Missing file: {$file}";
        continue;
    }

    $actualPerm = substr(sprintf('%o', fileperms($file)), -4);
    $isWritable = is_writable($file);
    $isReadable = is_readable($file);
    $isExecutable = is_executable($file);

    echo "   📊 Permission: {$actualPerm} (expected: " . decoct($expectedPerm) . ")\n";

    if ($expectedType === 'executable' && !$isExecutable) {
        echo "   ❌ Not executable!\n";
        $issues[] = "File not executable: {$file} ({$actualPerm})";
    } elseif ($expectedType === 'readable' && !$isReadable) {
        echo "   ❌ Not readable!\n";
        $issues[] = "File not readable: {$file} ({$actualPerm})";
    } else {
        echo "   ✅ OK\n";
    }

    echo "\n";
}

echo "📋 Checking special requirements...\n\n";

// Check storage symlink
$publicStorage = public_path('storage');
if (is_link($publicStorage)) {
    echo "🔗 public/storage symlink: ✅ Exists\n";
    echo "   Points to: " . readlink($publicStorage) . "\n";
} else {
    echo "🔗 public/storage symlink: ❌ Missing!\n";
    echo "   Run /create-symlink.php to create it\n";
    $issues[] = "Missing storage symlink";
}

echo "\n";

// Check vendor directory
if (is_dir('vendor')) {
    echo "📦 vendor directory: ✅ Exists\n";
} else {
    echo "📦 vendor directory: ❌ Missing!\n";
    echo "   Run 'composer install' on your local machine\n";
    $issues[] = "Missing vendor directory";
}

echo "\n";

// Check .env file
if (file_exists('.env')) {
    echo "🔐 .env file: ✅ Exists\n";

    // Check if APP_KEY is set
    $envContent = file_get_contents('.env');
    if (strpos($envContent, 'APP_KEY=') !== false && strpos($envContent, 'base64:') !== false) {
        echo "   APP_KEY: ✅ Set\n";
    } else {
        echo "   APP_KEY: ❌ Not set or invalid!\n";
        echo "   Run /server-keygen.php to generate it\n";
        $issues[] = "Missing or invalid APP_KEY";
    }
} else {
    echo "🔐 .env file: ❌ Missing!\n";
    echo "   Copy .env.production to .env and configure it\n";
    $issues[] = "Missing .env file";
}

// Final report
echo "\n" . str_repeat("=", 50) . "\n\n";

if (empty($issues)) {
    echo "🎉 ALL CHECKS PASSED!\n\n";
    echo "✨ File permissions are correct\n";
    echo "✨ All required files exist\n";
    echo "✨ Laravel should run properly\n\n";
    echo "🚀 Your application is ready!\n\n";

    echo "📱 Test URLs:\n";
    echo "   - Homepage: " . (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . "/\n";
    echo "   - Login: " . (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . "/login\n";
    echo "   - Admin: " . (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . "/admin/dashboard\n";

} else {
    echo "⚠️  ISSUES FOUND!\n\n";
    echo "Please fix the following issues:\n\n";

    foreach ($issues as $issue) {
        echo "❌ {$issue}\n";
    }

    echo "\n🔧 Common fixes:\n";
    echo "1. Use cPanel File Manager to change permissions\n";
    echo "2. Contact your hosting provider for permission issues\n";
    echo "3. Make sure vendor directory is uploaded\n";
    echo "4. Run the setup scripts in the correct order\n";
    echo "5. Check that files were uploaded correctly (ASCII/Binary mode)\n";
}

echo "\n=== END OF CHECK ===\n";
?>