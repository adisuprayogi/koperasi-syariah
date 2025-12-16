<?php
/**
 * SQL Status Fields Runner
 * Run this script via browser to execute SQL for adding status fields
 * URL: https://your-domain.com/run-sql-status-fields.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== SQL STATUS FIELDS RUNNER ===\n\n";

try {
    // Check if .env exists
    if (!file_exists('.env')) {
        echo "❌ ERROR: .env file not found!\n";
        echo "Please copy .env.production to .env and configure it.\n";
        exit(1);
    }

    // Load environment variables
    $envContent = file_get_contents('.env');
    $dbHost = getEnvValue($envContent, 'DB_HOST', '127.0.0.1');
    $dbPort = getEnvValue($envContent, 'DB_PORT', '3306');
    $dbDatabase = getEnvValue($envContent, 'DB_DATABASE', '');
    $dbUsername = getEnvValue($envContent, 'DB_USERNAME', '');
    $dbPassword = getEnvValue($envContent, 'DB_PASSWORD', '');

    if (empty($dbDatabase) || empty($dbUsername)) {
        echo "❌ ERROR: Database configuration not found in .env!\n";
        echo "Please check DB_DATABASE and DB_USERNAME in your .env file.\n";
        exit(1);
    }

    echo "🗄️  Database Configuration:\n";
    echo "   Host: {$dbHost}\n";
    echo "   Port: {$dbPort}\n";
    echo "   Database: {$dbDatabase}\n";
    echo "   Username: {$dbUsername}\n\n";

    // Connect to database
    try {
        $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbDatabase};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbUsername, $dbPassword, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        echo "✅ Database connection successful!\n\n";
    } catch (PDOException $e) {
        echo "❌ ERROR: Database connection failed!\n";
        echo "Error: " . $e->getMessage() . "\n";
        echo "Please check your database credentials in .env file.\n";
        exit(1);
    }

    echo "🔧 Running SQL queries to add status fields...\n\n";

    // Step 1: Check if anggota table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'anggota'");
    $stmt->execute();
    $tableExists = $stmt->fetch();

    if (!$tableExists) {
        echo "❌ ERROR: anggota table not found!\n";
        echo "Please run database migrations first.\n";
        exit(1);
    }

    echo "✅ anggota table found\n\n";

    // Step 2: Check current table structure
    echo "📋 Current table structure:\n";
    $stmt = $pdo->prepare("DESCRIBE anggota");
    $stmt->execute();
    $columns = $stmt->fetchAll();

    $existingColumns = [];
    foreach ($columns as $column) {
        $existingColumns[] = $column['Field'];
        echo "   - {$column['Field']} ({$column['Type']})\n";
    }

    echo "\n";

    // Step 3: Add status_keanggotaan field if not exists
    if (!in_array('status_keanggotaan', $existingColumns)) {
        echo "➕ Adding status_keanggotaan field...\n";
        $sql = "ALTER TABLE `anggota` ADD COLUMN `status_keanggotaan` ENUM('aktif', 'tidak_aktif', 'keluar') NOT NULL DEFAULT 'aktif' COMMENT 'Member status: aktif, tidak_aktif, keluar' AFTER `jenis_anggota`";
        $pdo->exec($sql);
        echo "✅ status_keanggotaan field added successfully\n";
    } else {
        echo "✅ status_keanggotaan field already exists\n";
    }

    // Step 4: Add tanggal_keluar field if not exists
    if (!in_array('tanggal_keluar', $existingColumns)) {
        echo "➕ Adding tanggal_keluar field...\n";
        $sql = "ALTER TABLE `anggota` ADD COLUMN `tanggal_keluar` DATE NULL DEFAULT NULL COMMENT 'Tanggal anggota keluar dari koperasi' AFTER `status_keanggotaan`";
        $pdo->exec($sql);
        echo "✅ tanggal_keluar field added successfully\n";
    } else {
        echo "✅ tanggal_keluar field already exists\n";
    }

    // Step 5: Add alasan_keluar field if not exists
    if (!in_array('alasan_keluar', $existingColumns)) {
        echo "➕ Adding alasan_keluar field...\n";
        $sql = "ALTER TABLE `anggota` ADD COLUMN `alasan_keluar` TEXT NULL DEFAULT NULL COMMENT 'Alasan anggota keluar dari koperasi' AFTER `tanggal_keluar`";
        $pdo->exec($sql);
        echo "✅ alasan_keluar field added successfully\n";
    } else {
        echo "✅ alasan_keluar field already exists\n";
    }

    echo "\n🔍 Verifying added fields...\n";

    // Step 6: Verify the changes
    $stmt = $pdo->prepare("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'anggota' AND COLUMN_NAME IN ('status_keanggotaan', 'tanggal_keluar', 'alasan_keluar') ORDER BY ORDINAL_POSITION");
    $stmt->execute();
    $newColumns = $stmt->fetchAll();

    foreach ($newColumns as $column) {
        echo "✅ {$column['COLUMN_NAME']}:\n";
        echo "   Type: {$column['DATA_TYPE']}\n";
        echo "   Nullable: {$column['IS_NULLABLE']}\n";
        echo "   Default: " . ($column['COLUMN_DEFAULT'] ?? 'NULL') . "\n";
        echo "   Comment: " . ($column['COLUMN_COMMENT'] ?? 'No comment') . "\n\n";
    }

    // Step 7: Update existing records if needed
    echo "🔄 Updating existing records...\n";

    // Update existing anggota to have proper dates if tanggal_gabung is null
    $stmt = $pdo->prepare("UPDATE anggota SET tanggal_gabung = DATE(created_at) WHERE tanggal_gabung IS NULL");
    $result = $stmt->execute();
    echo "✅ Updated " . $stmt->rowCount() . " records with tanggal_gabung\n";

    // Set default status for existing records
    $stmt = $pdo->prepare("UPDATE anggota SET status_keanggotaan = 'aktif' WHERE status_keanggotaan IS NULL OR status_keanggotaan = ''");
    $result = $stmt->execute();
    echo "✅ Set default status for " . $stmt->rowCount() . " records\n";

    echo "\n📊 Final statistics:\n";

    // Count members by status
    $stmt = $pdo->prepare("SELECT status_keanggotaan, COUNT(*) as total, COUNT(CASE WHEN tanggal_keluar IS NOT NULL THEN 1 END) as with_exit_date, COUNT(CASE WHEN alasan_keluar IS NOT NULL AND alasan_keluar != '' THEN 1 END) as with_exit_reason FROM anggota GROUP BY status_keanggotaan");
    $stmt->execute();
    $stats = $stmt->fetchAll();

    foreach ($stats as $stat) {
        echo "   {$stat['status_keanggotaan']}: {$stat['total']} members ({$stat['with_exit_date']} with exit date, {$stat['with_exit_reason']} with exit reason)\n";
    }

    echo "\n🎉 SUCCESS! Status fields have been added to anggota table!\n\n";

    echo "📋 Summary of changes:\n";
    echo "   ✅ Added status_keanggotaan field (ENUM)\n";
    echo "   ✅ Added tanggal_keluar field (DATE)\n";
    echo "   ✅ Added alasan_keluar field (TEXT)\n";
    echo "   ✅ Updated existing records with proper defaults\n";
    echo "   ✅ Updated tanggal_gabung for records without dates\n\n";

    echo "🚀 Next steps:\n";
    echo "   1. Test the application\n";
    echo "   2. Test member status management features\n";
    echo "   3. Test Excel import with new status fields\n";
    echo "   4. Delete this file for security\n\n";

    echo "⚠️  IMPORTANT: Delete this file after successful execution!\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    echo "⚠️  Troubleshooting:\n";
    echo "1. Check database credentials in .env\n";
    echo "2. Verify database exists and is accessible\n";
    echo "3. Check database user permissions\n";
    echo "4. Ensure anggota table exists\n";
    exit(1);
}

function getEnvValue($envContent, $key, $default = '') {
    if (preg_match("/^{$key}=[\"']?([^\"'\n\r]+)/m", $envContent, $matches)) {
        return $matches[1];
    }
    return $default;
}

echo "\n=== END OF PROCESS ===\n";
?>