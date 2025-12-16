<?php
/**
 * Import Debug Tool
 * Run this to test import functionality and identify issues
 * URL: https://your-domain.com/debug-import.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== IMPORT DEBUG TOOL ===\n\n";

try {
    // Check if Laravel loads
    if (file_exists('vendor/autoload.php') && file_exists('bootstrap/app.php')) {
        require_once 'vendor/autoload.php';
        $app = require_once 'bootstrap/app.php';
        echo "✅ Laravel loaded successfully\n\n";
    } else {
        echo "❌ Laravel cannot be loaded!\n";
        exit(1);
    }

    // Check database connection
    try {
        $db = $app['db'];
        $db->getPdo();
        echo "✅ Database connection successful\n\n";
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n\n";
        exit(1);
    }

    // Check if anggota table exists and has required columns
    echo "📋 CHECKING ANGGOTA TABLE STRUCTURE:\n";
    try {
        $columns = $db->select("DESCRIBE anggota");
        $columnNames = array_map(function($col) {
            return $col->Field;
        }, $columns);

        $requiredColumns = [
            'status_keanggotaan',
            'tanggal_keluar',
            'alasan_keluar'
        ];

        foreach ($requiredColumns as $col) {
            if (in_array($col, $columnNames)) {
                echo "✅ {$col} column exists\n";
            } else {
                echo "❌ {$col} column missing!\n";
                echo "   Run SQL script to add status fields\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ Cannot check table structure: " . $e->getMessage() . "\n";
    }

    echo "\n";

    // Check if User and Anggota models exist
    echo "📋 CHECKING MODELS:\n";
    if (class_exists('App\Models\User')) {
        echo "✅ User model exists\n";
    } else {
        echo "❌ User model missing!\n";
    }

    if (class_exists('App\Models\Anggota')) {
        echo "✅ Anggota model exists\n";

        // Check if Anggota has required methods
        if (method_exists('App\Models\Anggota', 'generateNoAnggota')) {
            echo "✅ generateNoAnggota method exists\n";
        } else {
            echo "❌ generateNoAnggota method missing!\n";
        }
    } else {
        echo "❌ Anggota model missing!\n";
    }

    echo "\n";

    // Check if Excel library is available
    echo "📋 CHECKING EXCEL LIBRARY:\n";
    if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
        echo "✅ Excel library available\n";
    } else {
        echo "❌ Excel library missing!\n";
        echo "   Check vendor/laravel-excel package\n";
    }

    echo "\n";

    // Check import controller
    echo "📋 CHECKING IMPORT CONTROLLER:\n";
    if (class_exists('App\Http\Controllers\Admin\AnggotaImportController')) {
        echo "✅ AnggotaImportController exists\n";
    } else {
        echo "❌ AnggotaImportController missing!\n";
    }

    // Check export template
    echo "📋 CHECKING EXPORT TEMPLATE:\n";
    if (class_exists('App\Exports\AnggotaTemplateExport')) {
        echo "✅ AnggotaTemplateExport exists\n";
    } else {
        echo "❌ AnggotaTemplateExport missing!\n";
    }

    echo "\n";

    // Test basic Anggota model operations
    echo "🧪 TESTING ANGGOTA MODEL:\n";
    try {
        $anggotaCount = \App\Models\Anggota::count();
        echo "✅ Anggota model works ({$anggotaCount} records)\n";

        if (method_exists('App\Models\Anggota', 'generateNoAnggota')) {
            $testNo = \App\Models\Anggota::generateNoAnggota();
            echo "✅ generateNoAnggota() works (example: {$testNo})\n";
        }
    } catch (Exception $e) {
        echo "❌ Anggota model error: " . $e->getMessage() . "\n";
    }

    echo "\n";

    // Check file uploads
    echo "📋 CHECKING UPLOAD CONFIGURATION:\n";
    $uploadMax = ini_get('upload_max_filesize');
    $postMax = ini_get('post_max_size');
    $memoryLimit = ini_get('memory_limit');
    $maxExecutionTime = ini_get('max_execution_time');

    echo "   upload_max_filesize: {$uploadMax}\n";
    echo "   post_max_size: {$postMax}\n";
    echo "   memory_limit: {$memoryLimit}\n";
    echo "   max_execution_time: {$maxExecutionTime}s\n";

    if ((int)$uploadMax < 10) {
        echo "⚠️  upload_max_filesize too small (should be at least 10M)\n";
    }

    echo "\n";

    // Check storage directory
    echo "📋 CHECKING STORAGE DIRECTORIES:\n";
    $storageDirs = [
        'storage/app/public/imports',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
    ];

    foreach ($storageDirs as $dir) {
        if (is_dir($dir)) {
            $writable = is_writable($dir) ? '✅' : '❌';
            echo "   {$writable} {$dir} (writable)\n";
        } else {
            echo "   ❌ {$dir} (missing)\n";
        }
    }

    echo "\n";

    echo "🎯 COMMON IMPORT ISSUES & SOLUTIONS:\n";
    echo "1. ❌ 'Column not found' error:\n";
    echo "   → Run SQL script to add status fields\n";
    echo "   → Check if migration was successful\n\n";

    echo "2. ❌ 'Class not found' error:\n";
    echo "   → Upload complete vendor directory\n";
    echo "   → Run composer install locally\n\n";

    echo "3. ❌ 'Cannot read file' error:\n";
    echo "   → Check file permissions\n";
    echo "   → Verify PHP upload limits\n";
    echo "   → Check .htaccess configuration\n\n";

    echo "4. ❌ 'Database connection' error:\n";
    echo "   → Check .env database settings\n";
    echo "   → Verify database exists\n";
    echo "   → Check database user permissions\n\n";

    echo "5. ❌ 'Invalid format' error:\n";
    echo "   → Use latest Excel template (17 columns)\n";
    echo "   → Check date format (YYYY-MM-DD)\n";
    echo "   → Verify email format is valid\n";
    echo "   → Check NIK is 16 digits\n\n";

    echo "🔧 QUICK FIXES:\n";
    echo "1. Run: /run-sql-status-fields.php\n";
    echo "2. Run: /clear-cache.php\n";
    echo "3. Download new template from import page\n";
    echo "4. Check all error messages carefully\n\n";

    echo "📞 FOR FURTHER HELP:\n";
    echo "1. Copy this diagnostic output\n";
    echo "2. Note the exact error message\n";
    echo "3. Check Laravel logs in storage/logs/\n";

} catch (Exception $e) {
    echo "❌ DEBUG ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== END OF DEBUG ===\n";
?>