-- ===================================================
-- Koperasi Syariah - Add Status Fields to Anggota Table
-- ===================================================
-- Run this SQL script if migration fails or you need
-- to add status management fields manually
-- ===================================================

USE `koperasi_syariah`; -- Change this to your actual database name

-- Check if table exists first
SHOW TABLES LIKE 'anggota';

-- Add status_keanggotaan field if not exists
ALTER TABLE `anggota`
ADD COLUMN IF NOT EXISTS `status_keanggotaan` ENUM('aktif', 'tidak_aktif', 'keluar') NOT NULL DEFAULT 'aktif' COMMENT 'Member status: aktif, tidak_aktif, keluar' AFTER `jenis_anggota`;

-- Add tanggal_keluar field if not exists
ALTER TABLE `anggota`
ADD COLUMN IF NOT EXISTS `tanggal_keluar` DATE NULL DEFAULT NULL COMMENT 'Tanggal anggota keluar dari koperasi' AFTER `status_keanggotaan`;

-- Add alasan_keluar field if not exists
ALTER TABLE `anggota`
ADD COLUMN IF NOT EXISTS `alasan_keluar` TEXT NULL DEFAULT NULL COMMENT 'Alasan anggota keluar dari koperasi' AFTER `tanggal_keluar`;

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS `idx_anggota_status` ON `anggota`(`status_keanggotaan`);
CREATE INDEX IF NOT EXISTS `idx_anggota_tanggal_keluar` ON `anggota`(`tanggal_keluar`);

-- Verify the changes
DESCRIBE `anggota`;

-- Show the new fields specifically
SELECT
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'anggota'
AND COLUMN_NAME IN ('status_keanggotaan', 'tanggal_keluar', 'alasan_keluar')
ORDER BY ORDINAL_POSITION;

-- ===================================================
-- Test Data (Optional - Uncomment to add sample data)
-- ===================================================

-- UPDATE existing anggota to have proper dates if tanggal_gabung is null
-- UPDATE `anggota`
-- SET `tanggal_gabung` = DATE(created_at)
-- WHERE `tanggal_gabung` IS NULL;

-- Add sample status data for testing (Optional)
-- UPDATE `anggota`
-- SET `status_keanggotaan` = 'aktif'
-- WHERE `status_keanggotaan` IS NULL OR `status_keanggotaan` = '';

-- ===================================================
-- Verification Queries
-- ===================================================

-- Count members by status
SELECT
    `status_keanggotaan`,
    COUNT(*) as total,
    COUNT(CASE WHEN `tanggal_keluar` IS NOT NULL THEN 1 END) as with_exit_date,
    COUNT(CASE WHEN `alasan_keluar` IS NOT NULL AND `alasan_keluar` != '' THEN 1 END) as with_exit_reason
FROM `anggota`
GROUP BY `status_keanggotaan`;

-- Show sample data with new fields
SELECT
    `no_anggota`,
    `nama_lengkap`,
    `status_keanggotaan`,
    `tanggal_gabung`,
    `tanggal_keluar`,
    `alasan_keluar`
FROM `anggota`
ORDER BY `created_at` DESC
LIMIT 5;

-- ===================================================
-- Status Values Reference
-- ===================================================
/*
Status Keanggotaan Values:
- 'aktif': Member is active and can use the system
- 'tidak_aktif': Member is inactive but still in system
- 'keluar': Member has left, user account deactivated

Field Descriptions:
- status_keanggotaan: ENUM('aktif', 'tidak_aktif', 'keluar')
- tanggal_keluar: DATE - When member left (NULL if still active)
- alasan_keluar: TEXT - Reason for leaving (NULL if still active)
*/

-- ===================================================
-- Script Completion Message
-- ===================================================
SELECT '✅ Status fields successfully added to anggota table!' as message;