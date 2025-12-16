<?php
/**
 * Laravel Cache Clearer
 * Run this script via browser to clear all Laravel caches
 * URL: https://your-domain.com/clear-cache.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== Koperasi Syariah - Cache Cleaner ===\n\n";

try {
    // Check if vendor directory exists
    if (!is_dir('vendor')) {
        echo "❌ ERROR: vendor directory not found!\n";
        exit(1);
    }

    // Load Laravel
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "🧹 Clearing Laravel caches...\n\n";

    // Cache clearing commands
    $commands = [
        'config:clear' => 'Configuration cache',
        'route:clear' => 'Route cache',
        'view:clear' => 'View cache',
        'cache:clear' => 'Application cache'
    ];

    $allSuccess = true;

    foreach ($commands as $command => $description) {
        echo "🗑️  Clearing {$description}...\n";
        ob_start();
        $exitCode = $kernel->call($command);
        $output = ob_get_clean();

        if ($exitCode === 0) {
            echo "✅ {$description} cleared successfully\n";
        } else {
            echo "❌ Failed to clear {$description}\n";
            if (!empty($output)) {
                echo "   Error: " . trim($output) . "\n";
            }
            $allSuccess = false;
        }
    }

    echo "\n📁 Clearing compiled files...\n";

    // Clear compiled files manually
    $compiledFiles = [
        'bootstrap/cache/config.php',
        'bootstrap/cache/routes-v7.php',
        'bootstrap/cache/compiled.php',
        'bootstrap/cache/services.php'
    ];

    foreach ($compiledFiles as $file) {
        if (file_exists($file)) {
            if (unlink($file)) {
                echo "✅ Removed: " . basename($file) . "\n";
            } else {
                echo "❌ Could not remove: " . basename($file) . "\n";
                $allSuccess = false;
            }
        }
    }

    echo "\n🗑️  Clearing Laravel log files...\n";

    // Clear recent log files (keep last 1)
    $logDir = storage_path('logs');
    if (is_dir($logDir)) {
        $logFiles = glob($logDir . '/laravel-*.log');
        sort($logFiles);

        // Keep only the most recent log file
        while (count($logFiles) > 1) {
            $oldLog = array_shift($logFiles);
            if (unlink($oldLog)) {
                echo "✅ Removed old log: " . basename($oldLog) . "\n";
            }
        }
    }

    if ($allSuccess) {
        echo "\n🎉 All caches cleared successfully!\n\n";
        echo "✨ Benefits:\n";
        echo "   - Updated configuration will be loaded\n";
        echo "   - New routes will be registered\n";
        echo "   - View changes will be reflected\n";
        echo "   - Performance improvements\n\n";

        echo "🚀 Ready to test new features:\n";
        echo "1. Member status management\n";
        echo "2. Enhanced Excel import (17 columns)\n";
        echo "3. Improved UI/UX design\n";
        echo "4. Automatic user deactivation\n\n";

        echo "⚠️  IMPORTANT:\n";
        echo "1. Delete this file after use\n";
        echo "2. Only run when needed\n";
        echo "3. Test application after clearing\n";

    } else {
        echo "\n⚠️  Some cache clearing failed\n";
        echo "This usually doesn't affect core functionality\n";
        echo "But you may want to check file permissions\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "⚠️  Cache clearing failed\n";
    echo "Please check file permissions and Laravel installation\n";
    exit(1);
}

echo "\n=== END OF PROCESS ===\n";
?>