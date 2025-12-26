<?php
/**
 * Production Environment Key Generator
 * Run this script via browser to generate new APP_KEY
 * URL: https://your-domain.com/server-keygen.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== Koperasi Syariah - APP_KEY Generator ===\n\n";

// Check if .env file exists
$envFile = '.env';
if (!file_exists($envFile)) {
    echo "❌ ERROR: .env file not found!\n";
    echo "Please copy .env.production to .env first.\n";
    exit(1);
}

echo "📁 Found .env file\n";

// Read current .env content
$envContent = file_get_contents($envFile);

// Generate new key
echo "🔑 Generating new APP_KEY...\n";

try {
    // Generate key using Laravel's method
    $key = 'base64:' . base64_encode(random_bytes(32));
    echo "✅ Generated key: " . substr($key, 0, 20) . "...\n";

    // Replace existing APP_KEY in .env
    $pattern = '/^APP_KEY=.*$/m';
    $replacement = 'APP_KEY=' . $key;

    if (preg_match($pattern, $envContent)) {
        $envContent = preg_replace($pattern, $replacement, $envContent);
        echo "✅ Updated existing APP_KEY\n";
    } else {
        // Add APP_KEY if not exists
        $envContent .= "\nAPP_KEY=" . $key . "\n";
        echo "✅ Added new APP_KEY\n";
    }

    // Write back to .env
    if (file_put_contents($envFile, $envContent)) {
        echo "✅ Successfully updated .env file\n";
        echo "\n🎉 APP_KEY has been generated and saved!\n";
        echo "🔒 Key: " . $key . "\n";
        echo "\n⚠️  IMPORTANT:\n";
        echo "1. Keep this key secure and private\n";
        echo "2. Delete this file after use\n";
        echo "3. Do not commit .env to version control\n";
        echo "\n🚀 Next steps:\n";
        echo "1. Run migrations: visit /run-migrations.php\n";
        echo "2. Create storage link: visit /create-symlink.php\n";
        echo "3. Clear cache: visit /clear-cache.php\n";
    } else {
        echo "❌ ERROR: Failed to write to .env file\n";
        echo "Check file permissions.\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Failed to generate APP_KEY.\n";
    exit(1);
}

echo "\n=== END OF PROCESS ===\n";
?>