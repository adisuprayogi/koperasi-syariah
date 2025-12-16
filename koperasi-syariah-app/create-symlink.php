<?php
/**
 * Storage Symbolic Link Creator
 * Run this script via browser to create storage symbolic link
 * URL: https://your-domain.com/create-symlink.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== Koperasi Syariah - Storage Link Creator ===\n\n";

try {
    // Define paths
    $publicStorage = public_path('storage');
    $storageDir = storage_path('app/public');

    echo "📁 Storage directory: " . $storageDir . "\n";
    echo "📁 Public storage target: " . $publicStorage . "\n\n";

    // Check if storage directory exists
    if (!is_dir($storageDir)) {
        echo "❌ ERROR: storage/app/public directory not found!\n";
        echo "Please check Laravel installation.\n";
        exit(1);
    }

    // Check if symlink already exists
    if (is_link($publicStorage)) {
        echo "✅ Storage symlink already exists!\n";
        echo "Link target: " . readlink($publicStorage) . "\n";
        echo "\n🎉 Storage is already properly linked!\n";
        echo "\n🚀 Next steps:\n";
        echo "1. Clear cache: visit /clear-cache.php\n";
        echo "2. Check permissions: visit /check-permissions.php\n";
        exit(0);
    }

    // Check if something exists at the target location
    if (file_exists($publicStorage)) {
        echo "⚠️  WARNING: Something exists at " . $publicStorage . "\n";
        echo "Removing it to create symlink...\n";

        if (is_dir($publicStorage)) {
            if (!rmdir($publicStorage)) {
                echo "❌ ERROR: Cannot remove existing directory\n";
                exit(1);
            }
        } else {
            if (!unlink($publicStorage)) {
                echo "❌ ERROR: Cannot remove existing file\n";
                exit(1);
            }
        }
    }

    echo "🔗 Creating symbolic link...\n";

    // Create the symbolic link
    if (symlink($storageDir, $publicStorage)) {
        echo "✅ Symbolic link created successfully!\n";
        echo "Source: " . $storageDir . "\n";
        echo "Target: " . $publicStorage . "\n";
        echo "\n🎉 Storage is now properly linked!\n";
        echo "\n📂 This allows public access to:\n";
        echo "   - Uploaded logos\n";
        echo "   - Member photos\n";
        echo "   - Generated PDFs\n";
        echo "   - Import/export files\n";
        echo "\n🚀 Next steps:\n";
        echo "1. Clear cache: visit /clear-cache.php\n";
        echo "2. Check permissions: visit /check-permissions.php\n";
        echo "3. Test image uploads\n";

    } else {
        echo "❌ ERROR: Failed to create symbolic link!\n\n";
        echo "⚠️  Possible causes:\n";
        echo "1. Server doesn't allow symbolic links\n";
        echo "2. File permissions issue\n";
        echo "3. Safe mode restrictions\n";
        echo "\n🔧 Alternative solutions:\n";
        echo "1. Contact your hosting provider to enable symlink support\n";
        echo "2. Use cPanel to create symlink manually\n";
        echo "3. Copy storage files manually (not recommended)\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "⚠️  Please check file permissions and server configuration.\n";
    exit(1);
}

echo "\n=== END OF PROCESS ===\n";
?>