<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class MigrateController extends Controller
{
    /**
     * Run migrations via browser
     * Hapus file ini setelah migration selesai!
     */
    public function run()
    {
        try {
            // Security check - hanya boleh diakses dari localhost atau dengan token khusus
            // Hapus baris ini jika sudah tidak needed
            // if (request()->ip() !== '127.0.0.1' && request()->ip() !== '::1') {
            //     abort(403, 'Akses ditolak');
            // }

            // Jalankan migration
            Artisan::call('migrate');

            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'Migration berhasil dijalankan',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            Log::error('Migration error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Migration gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek status migration
     */
    public function status()
    {
        try {
            Artisan::call('migrate:status');

            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'output' => $output
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
