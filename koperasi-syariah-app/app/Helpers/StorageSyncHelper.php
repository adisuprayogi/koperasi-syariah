<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class StorageSyncHelper
{
    /**
     * Sync file dari storage/app/public ke public/storage
     * Panggil ini setelah upload file
     *
     * @param string $path - Path file di storage (contoh: 'anggota/photos/file.jpg')
     * @return bool
     */
    public static function syncToPublic($path)
    {
        try {
            $sourcePath = storage_path('app/public/' . $path);
            $targetPath = public_path('storage/' . $path);

            // Pastikan folder target ada
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Copy file
            if (file_exists($sourcePath)) {
                $result = copy($sourcePath, $targetPath);
                if ($result) {
                    // Set permissions
                    chmod($targetPath, 0644);
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('Storage sync error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Sync seluruh folder dari storage ke public
     *
     * @param string $folder - Nama folder (contoh: 'anggota/photos')
     * @return array
     */
    public static function syncFolder($folder)
    {
        $sourceDir = storage_path('app/public/' . $folder);
        $targetDir = public_path('storage/' . $folder);

        $synced = 0;
        $errors = [];

        try {
            if (!is_dir($sourceDir)) {
                return ['synced' => 0, 'errors' => ['Source directory not found']];
            }

            // Buat folder target
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $files = File::allFiles($sourceDir);

            foreach ($files as $file) {
                $relativePath = $file->getRelativePathname();
                $targetFile = $targetDir . '/' . $relativePath;

                // Buat subfolder jika perlu
                $targetSubDir = dirname($targetFile);
                if (!is_dir($targetSubDir)) {
                    mkdir($targetSubDir, 0755, true);
                }

                // Copy file
                if (copy($file->getPathname(), $targetFile)) {
                    chmod($targetFile, 0644);
                    $synced++;
                } else {
                    $errors[] = 'Failed to copy: ' . $relativePath;
                }
            }

            return ['synced' => $synced, 'errors' => $errors];
        } catch (\Exception $e) {
            return ['synced' => $synced, 'errors' => [$e->getMessage()]];
        }
    }

    /**
     * Hapus file dari kedua lokasi
     *
     * @param string $path
     * @return bool
     */
    public static function deleteFromBoth($path)
    {
        $storagePath = 'public/' . $path;
        $publicPath = 'storage/' . $path;

        $storageDeleted = Storage::delete($storagePath);
        $publicDeleted = false;

        $fullPublicPath = public_path($publicPath);
        if (file_exists($fullPublicPath)) {
            $publicDeleted = unlink($fullPublicPath);
        }

        return $storageDeleted || $publicDeleted;
    }
}