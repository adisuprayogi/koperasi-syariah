<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanPembiayaan;
use Illuminate\Support\Facades\Auth;

class FileDownloadController extends Controller
{
    /**
     * Download file from pengajuan pembiayaan
     */
    public function downloadPengajuanFile($pengajuanId, $field)
    {
        try {
            // Get current user
            $user = Auth::user();

            // Find pengajuan
            $pengajuan = PengajuanPembiayaan::findOrFail($pengajuanId);

            // Check authorization
            if (!$this->canAccessFile($user, $pengajuan)) {
                abort(403, 'Unauthorized access');
            }

            // Validate field name to prevent directory traversal
            $allowedFields = [
                'ktp_file',
                'kk_file',
                'slip_gaji_file',
                'proposal_file',
                'jaminan_file',
                'jaminan_file_2',
                'jaminan_file_3',
                'dokumen_lainnya_1',
                'dokumen_lainnya_2',
                'dokumen_lainnya_3',
                'dokumen_lainnya_4',
                'dokumen_lainnya_5',
                'bukti_transfer',
                'bukti_pencairan'
            ];

            if (!in_array($field, $allowedFields)) {
                abort(400, 'Invalid file field');
            }

            // Check if file exists
            if (!$pengajuan->$field) {
                abort(404, 'File not found');
            }

            $filePath = $pengajuan->$field;

            // Check if file exists in storage
            if (!Storage::disk('public')->exists($filePath)) {
                abort(404, 'File not found in storage');
            }

            // Get file information
            $fileInfo = pathinfo($filePath);
            $fileName = $fileInfo['basename'];
            $originalName = $this->getOriginalFileName($pengajuan, $field);

            // Return file download response
            return Storage::disk('public')->download($filePath, $originalName);

        } catch (\Exception $e) {
            \Log::error('File download error: ' . $e->getMessage());
            abort(500, 'Error downloading file');
        }
    }

    /**
     * Check if user can access the file
     */
    private function canAccessFile($user, $pengajuan)
    {
        // Admin can access all files
        if ($user->hasRole('admin')) {
            return true;
        }

        // Pengurus can access all files
        if ($user->hasRole('pengurus')) {
            return true;
        }

        // Anggota can only access their own files
        if ($user->hasRole('anggota')) {
            $anggota = $user->anggota;
            return $anggota && $anggota->id === $pengajuan->anggota_id;
        }

        return false;
    }

    /**
     * Get original filename for download
     */
    private function getOriginalFileName($pengajuan, $field)
    {
        $fieldNames = [
            'ktp_file' => 'KTP_' . $pengajuan->anggota->nama_lengkap,
            'kk_file' => 'KK_' . $pengajuan->anggota->nama_lengkap,
            'slip_gaji_file' => 'Slip_Gaji_' . $pengajuan->anggota->nama_lengkap,
            'proposal_file' => 'Proposal_' . $pengajuan->anggota->nama_lengkap,
            'jaminan_file' => 'Jaminan_1_' . $pengajuan->anggota->nama_lengkap,
            'jaminan_file_2' => 'Jaminan_2_' . $pengajuan->anggota->nama_lengkap,
            'jaminan_file_3' => 'Jaminan_3_' . $pengajuan->anggota->nama_lengkap,
            'dokumen_lainnya_1' => 'Dokumen_Lainnya_1_' . $pengajuan->anggota->nama_lengkap,
            'dokumen_lainnya_2' => 'Dokumen_Lainnya_2_' . $pengajuan->anggota->nama_lengkap,
            'dokumen_lainnya_3' => 'Dokumen_Lainnya_3_' . $pengajuan->anggota->nama_lengkap,
            'dokumen_lainnya_4' => 'Dokumen_Lainnya_4_' . $pengajuan->anggota->nama_lengkap,
            'dokumen_lainnya_5' => 'Dokumen_Lainnya_5_' . $pengajuan->anggota->nama_lengkap,
            'bukti_transfer' => 'Bukti_Transfer_' . $pengajuan->kode_pengajuan,
            'bukti_pencairan' => 'Bukti_Pencairan_' . $pengajuan->kode_pengajuan
        ];

        $originalName = $fieldNames[$field] ?? 'document';
        $filePath = $pengajuan->$field;
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return $originalName . '.' . $extension;
    }

    /**
     * Stream file for preview
     */
    public function previewPengajuanFile($pengajuanId, $field)
    {
        try {
            // Get current user
            $user = Auth::user();

            // Find pengajuan
            $pengajuan = PengajuanPembiayaan::findOrFail($pengajuanId);

            // Check authorization
            if (!$this->canAccessFile($user, $pengajuan)) {
                abort(403, 'Unauthorized access');
            }

            // Validate field name
            $allowedFields = [
                'ktp_file',
                'kk_file',
                'slip_gaji_file',
                'proposal_file',
                'jaminan_file',
                'jaminan_file_2',
                'jaminan_file_3',
                'dokumen_lainnya_1',
                'dokumen_lainnya_2',
                'dokumen_lainnya_3',
                'dokumen_lainnya_4',
                'dokumen_lainnya_5'
            ];

            if (!in_array($field, $allowedFields)) {
                abort(400, 'Invalid file field for preview');
            }

            // Check if file exists
            if (!$pengajuan->$field) {
                abort(404, 'File not found');
            }

            $filePath = $pengajuan->$field;

            // Check if file exists in storage
            if (!Storage::disk('public')->exists($filePath)) {
                abort(404, 'File not found in storage');
            }

            // Get file mime type
            $mimeType = Storage::disk('public')->mimeType($filePath);

            // Only allow preview for certain file types
            $allowedMimeTypes = [
                'application/pdf',
                'image/jpeg',
                'image/png',
                'image/gif',
                'text/plain'
            ];

            if (!in_array($mimeType, $allowedMimeTypes)) {
                abort(400, 'Preview not supported for this file type');
            }

            // Stream the file
            return response(Storage::disk('public')->get($filePath))
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . basename($filePath) . '"');

        } catch (\Exception $e) {
            \Log::error('File preview error: ' . $e->getMessage());
            abort(500, 'Error previewing file');
        }
    }
}