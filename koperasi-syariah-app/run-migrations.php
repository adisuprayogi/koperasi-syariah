<?php
/**
 * Database Migration Runner
 * Run this script via browser to run Laravel migrations
 * URL: https://your-domain.com/run-migrations.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== Koperasi Syariah - Database Migration Runner ===\n\n";

try {
    // Check if vendor directory exists
    if (!is_dir('vendor')) {
        echo "❌ ERROR: vendor directory not found!\n";
        echo "Please run 'composer install' on your local machine first.\n";
        exit(1);
    }

    // Check if .env file exists
    if (!file_exists('.env')) {
        echo "❌ ERROR: .env file not found!\n";
        echo "Please run server-keygen.php first.\n";
        exit(1);
    }

    echo "📁 vendor directory found\n";
    echo "📁 .env file found\n\n";

    // Load Laravel
    require __DIR__.'/vendor/autoload.php';

    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "🚀 Laravel framework loaded\n";
    echo "📊 Running database migrations...\n\n";

    // Prepare artisan command
    $command = 'migrate --force';

    // Capture migration output
    ob_start();
    $exitCode = $kernel->call($command);
    $output = ob_get_clean();

    if ($exitCode === 0) {
        echo "✅ Migrations completed successfully!\n\n";
        echo "Migration output:\n";
        echo "=================\n";
        echo $output . "\n";
        echo "=================\n\n";

        echo "🎉 Database is now up to date!\n";
        echo "\n⚠️  IMPORTANT:\n";
        echo "1. All migration files have been executed\n";
        echo "2. Database tables are now created/updated\n";
        echo "3. New status management fields are added\n";
        echo "\n🚀 Next steps:\n";
        echo "1. Create storage link: visit /create-symlink.php\n";
        echo "2. Clear cache: visit /clear-cache.php\n";
        echo "3. Check permissions: visit /check-permissions.php\n";

    } else {
        echo "❌ Migration failed!\n\n";
        echo "Error output:\n";
        echo "==============\n";
        echo $output . "\n";
        echo "==============\n\n";
        echo "⚠️  Please check:\n";
        echo "1. Database connection in .env\n";
        echo "2. Database user permissions\n";
        echo "3. Database exists and is accessible\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "⚠️  Please check:\n";
    echo "1. vendor directory exists\n";
    echo "2. .env file is configured\n";
    echo "3. Database connection is working\n";
    echo "4. File permissions are correct\n";
    exit(1);
}

echo "\n=== END OF PROCESS ===\n";
?>