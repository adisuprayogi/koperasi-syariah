<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DataKoperasiController;
use App\Http\Controllers\PengurusController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Anggota\PengajuanPembiayaanController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\Admin\KartuAnggotaController;
use App\Http\Controllers\Admin\AnggotaImportController;
use App\Http\Controllers\Admin\SimpananImportController;
use App\Http\Controllers\Admin\PembiayaanImportController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\ManualPreviewController;
use App\Models\Koperasi;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Landing Page dengan redirect logic
Route::get('/', function () {
    if (auth()->check()) {
        // User sudah login, redirect ke dashboard sesuai role
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pengurus':
                return redirect()->route('pengurus.dashboard');
            case 'anggota':
                return redirect()->route('anggota.dashboard');
            default:
                return view('welcome');
        }
    }

    // Load data koperasi untuk guest
    $koperasi = Koperasi::first();

    // Hitung jumlah anggota aktif
    $totalAnggotaAktif = Anggota::aktif()->count();

    return view('welcome', compact('koperasi', 'totalAnggotaAktif'));
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/ubah-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/ubah-password', [AuthController::class, 'changePassword'])->name('password.update');

// TEMPORARY: Migration Routes - HAPUS SETELAH SELESAI
Route::get('/run-migrate', [MigrateController::class, 'run']);
Route::get('/migrate-status', [MigrateController::class, 'status']);

// Admin Routes (Admin only)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/pengurus', [AdminController::class, 'pengurusIndex'])->name('pengurus.index');
    Route::get('/pengurus/create', [AdminController::class, 'pengurusCreate'])->name('pengurus.create');
    Route::post('/pengurus', [AdminController::class, 'pengurusStore'])->name('pengurus.store');
    Route::get('/pengurus/{id}/edit', [AdminController::class, 'pengurusEdit'])->name('pengurus.edit');
    Route::put('/pengurus/{id}', [AdminController::class, 'pengurusUpdate'])->name('pengurus.update');
    Route::delete('/pengurus/{id}', [AdminController::class, 'pengurusDestroy'])->name('pengurus.destroy');
    Route::post('/pengurus/{id}/restore', [AdminController::class, 'pengurusRestore'])->name('pengurus.restore');

    // Koperasi Management
    Route::get('/koperasi', [AdminController::class, 'koperasiIndex'])->name('koperasi.index');
    Route::get('/koperasi/edit', [AdminController::class, 'koperasiEdit'])->name('koperasi.edit');
    Route::put('/koperasi', [AdminController::class, 'koperasiUpdate'])->name('koperasi.update');

    // Data Koperasi Management
    Route::resource('data-koperasi', DataKoperasiController::class)->parameters([
        'data-koperasi' => 'id'
    ]);
    Route::post('/data-koperasi/{id}/toggle-status', [DataKoperasiController::class, 'toggleStatus'])->name('data-koperasi.toggle-status');

    
    // Jenis Simpanan
    Route::get('/jenis-simpanan', [AdminController::class, 'jenisSimpananIndex'])->name('jenis-simpanan.index');
    Route::get('/jenis-simpanan/create', [AdminController::class, 'jenisSimpananCreate'])->name('jenis-simpanan.create');
    Route::post('/jenis-simpanan', [AdminController::class, 'jenisSimpananStore'])->name('jenis-simpanan.store');
    Route::get('/jenis-simpanan/{id}/edit', [AdminController::class, 'jenisSimpananEdit'])->name('jenis-simpanan.edit');
    Route::put('/jenis-simpanan/{id}', [AdminController::class, 'jenisSimpananUpdate'])->name('jenis-simpanan.update');
    Route::delete('/jenis-simpanan/{id}', [AdminController::class, 'jenisSimpananDestroy'])->name('jenis-simpanan.destroy');

    // Jenis Pembiayaan
    Route::get('/jenis-pembiayaan', [AdminController::class, 'jenisPembiayaanIndex'])->name('jenis-pembiayaan.index');
    Route::get('/jenis-pembiayaan/create', [AdminController::class, 'jenisPembiayaanCreate'])->name('jenis-pembiayaan.create');
    Route::post('/jenis-pembiayaan', [AdminController::class, 'jenisPembiayaanStore'])->name('jenis-pembiayaan.store');
    Route::get('/jenis-pembiayaan/{id}/edit', [AdminController::class, 'jenisPembiayaanEdit'])->name('jenis-pembiayaan.edit');
    Route::put('/jenis-pembiayaan/{id}', [AdminController::class, 'jenisPembiayaanUpdate'])->name('jenis-pembiayaan.update');
    Route::delete('/jenis-pembiayaan/{id}', [AdminController::class, 'jenisPembiayaanDestroy'])->name('jenis-pembiayaan.destroy');

    // Import Data Routes
    Route::prefix('import')->name('import.')->group(function() {
        // Simpanan Import
        Route::get('/simpanan', [AdminController::class, 'simpananImport'])->name('simpanan');
        Route::post('/simpanan', [AdminController::class, 'simpananImportProcess'])->name('simpanan.process');
        Route::get('/simpanan/template', [AdminController::class, 'simpananDownloadTemplate'])->name('simpanan.template');

        // Pembiayaan Import
        Route::get('/pembiayaan', [AdminController::class, 'pembiayaanImport'])->name('pembiayaan');
        Route::post('/pembiayaan', [AdminController::class, 'pembiayaanImportProcess'])->name('pembiayaan.process');
        Route::get('/pembiayaan/template', [AdminController::class, 'pembiayaanDownloadTemplate'])->name('pembiayaan.template');

        // Pembayaran Angsuran Import
        Route::get('/pembayaran-angsuran', [AdminController::class, 'pembayaranAngsuranImport'])->name('pembayaran-angsuran');
        Route::post('/pembayaran-angsuran', [AdminController::class, 'pembayaranAngsuranImportProcess'])->name('pembayaran-angsuran.process');
        Route::get('/pembayaran-angsuran/template', [AdminController::class, 'pembayaranAngsuranDownloadTemplate'])->name('pembayaran-angsuran.template');
    });

    // Kartu Anggota Management
    Route::prefix('kartu-anggota')->name('kartu-anggota.')->group(function() {
        Route::get('/settings', [KartuAnggotaController::class, 'settings'])->name('settings');
        Route::post('/settings', [KartuAnggotaController::class, 'updateSettings'])->name('settings.update');
        Route::get('/anggota-list', [KartuAnggotaController::class, 'anggotaList'])->name('anggota-list');
        Route::get('/preview/{id}', [KartuAnggotaController::class, 'preview'])->name('preview');
        Route::get('/html/{id}', [KartuAnggotaController::class, 'generateHTML'])->name('html');
        Route::get('/download/{id}', [KartuAnggotaController::class, 'downloadPDF'])->name('download');

        // File uploads
        Route::post('/upload-logo', [KartuAnggotaController::class, 'uploadLogo'])->name('upload-logo');
        Route::post('/upload-signature', [KartuAnggotaController::class, 'uploadSignature'])->name('upload-signature');
        Route::post('/upload-background-front', [KartuAnggotaController::class, 'uploadBackgroundFront'])->name('upload-background-front');
        Route::post('/upload-background-back', [KartuAnggotaController::class, 'uploadBackgroundBack'])->name('upload-background-back');
    });
});

// Pengurus Routes (Pengurus only)
Route::prefix('pengurus')->name('pengurus.')->middleware(['auth', 'pengurus'])->group(function () {
    Route::get('/dashboard', [PengurusController::class, 'dashboard'])->name('dashboard');

    // Anggota Management
    Route::get('/anggota', [PengurusController::class, 'anggotaIndex'])->name('anggota.index');
    Route::get('/anggota/create', [PengurusController::class, 'anggotaCreate'])->name('anggota.create');
    Route::post('/anggota', [PengurusController::class, 'anggotaStore'])->name('anggota.store');
    Route::get('/anggota/{id}/edit', [PengurusController::class, 'anggotaEdit'])->name('anggota.edit');
    Route::put('/anggota/{id}', [PengurusController::class, 'anggotaUpdate'])->name('anggota.update');
    Route::delete('/anggota/{id}', [PengurusController::class, 'anggotaDestroy'])->name('anggota.destroy');

    // Status Management
    Route::get('/anggota/{id}/keluar', [PengurusController::class, 'anggotaKeluar'])->name('anggota.keluar');
    Route::post('/anggota/{id}/keluar', [PengurusController::class, 'anggotaProcessKeluar'])->name('anggota.process.keluar');
    Route::get('/anggota/{id}/reaktif', [PengurusController::class, 'anggotaReaktif'])->name('anggota.reaktif');

    // Anggota Import (Pengurus only)
    Route::get('/anggota/import', [AnggotaImportController::class, 'create'])->name('anggota.import');
    Route::post('/anggota/import-process', [AnggotaImportController::class, 'import'])->name('anggota.import.store');
    Route::get('/anggota/import/template', [AnggotaImportController::class, 'downloadTemplate'])->name('anggota.import.template');
    Route::get('/anggota/import/error-report', [AnggotaImportController::class, 'downloadErrorReport'])->name('anggota.import.error-report');

    // Transaksi Simpanan
    Route::get('/simpanan', [PengurusController::class, 'simpananIndex'])->name('simpanan.index');
    Route::get('/simpanan/create', [PengurusController::class, 'simpananCreate'])->name('simpanan.create');
    Route::post('/simpanan', [PengurusController::class, 'simpananStore'])->name('simpanan.store');
    Route::get('/simpanan/{id}', [PengurusController::class, 'simpananShow'])->name('simpanan.show');
    Route::get('/simpanan/{id}/print', [PengurusController::class, 'simpananPrint'])->name('simpanan.print');
    Route::get('/api/get-saldo', [PengurusController::class, 'getSaldo'])->name('api.get-saldo');

    // Pengajuan Pembiayaan
    Route::get('/pengajuan', [PengurusController::class, 'pengajuanIndex'])->name('pengajuan.index');
    Route::get('/pengajuan/{id}', [PengurusController::class, 'pengajuanShow'])->name('pengajuan.show');
    Route::post('/pengajuan/{id}/verifikasi', [PengurusController::class, 'pengajuanVerifikasi'])->name('pengajuan.verifikasi');
    Route::post('/pengajuan/{id}/reject', [PengurusController::class, 'pengajuanReject'])->name('pengajuan.reject');
    Route::post('/pengajuan/{id}/cairkan', [PengurusController::class, 'pengajuanCairkan'])->name('pengajuan.cairkan');

    // Manajemen Pembiayaan
    Route::get('/pembiayaan', [PengurusController::class, 'pembiayaanIndex'])->name('pembiayaan.index');
    Route::get('/pembiayaan/{id}', [PengurusController::class, 'pembiayaanShow'])->name('pembiayaan.show');
    Route::get('/pembiayaan/{id}/bayar/{angsuranId}', [PengurusController::class, 'pembiayaanBayar'])->name('pembiayaan.bayar');
    Route::post('/pembiayaan/{id}/bayar/{angsuranId}', [PengurusController::class, 'pembiayaanBayarStore'])->name('pembiayaan.bayar.store');
    // Lunas Lebih Cepat - AJAX (tanpa form, langsung lunas)
    Route::post('/pembiayaan/{id}/lunas-cepat', [PengurusController::class, 'lunasLebihCepat'])->name('pembiayaan.lunas-cepat');
    // Lunas Lebih Cepat - dengan form (perlu bukti pembayaran)
    Route::get('/pembiayaan/{id}/lunas-lebih-cepat', [PengurusController::class, 'lunasLebihCepatForm'])->name('pembiayaan.lunas_lebih_cepat');
    Route::post('/pembiayaan/{id}/lunas-lebih-cepat', [PengurusController::class, 'lunasLebihCepatStore'])->name('pembiayaan.lunas_lebih_cepat.store');
    Route::post('/pembiayaan/{id}/generate-jadwal', [PengurusController::class, 'generateJadwalAngsuran'])->name('pembiayaan.generate-jadwal');
    Route::get('/pembiayaan/{id}/print/{angsuranId}', [PengurusController::class, 'printBuktiBayar'])->name('pembiayaan.print-bukti');

    // Jadwal Angsuran (Sistem Baru)
    Route::post('/pembiayaan/{id}/jadwal/{periode}/bayar', [PengurusController::class, 'bayarJadwalAngsuran'])->name('pembiayaan.jadwal.bayar');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::get('/laporan/mingguan', [LaporanController::class, 'mingguan'])->name('laporan.mingguan');
    Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::get('/laporan/simpanan-wajib', [LaporanController::class, 'simpananWajib'])->name('laporan.simpanan-wajib');
    Route::get('/laporan/rekap-simpanan', [LaporanController::class, 'rekapSimpananAnggota'])->name('laporan.rekap-simpanan');
    Route::get('/laporan/simpanan-per-anggota', [LaporanController::class, 'simpananPerAnggota'])->name('laporan.simpanan-per-anggota');
    Route::get('/laporan/pembiayaan-per-anggota', [LaporanController::class, 'pembiayaanPerAnggota'])->name('laporan.pembiayaan-per-anggota');
    Route::get('/laporan/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');
    Route::get('/laporan/neraca', [LaporanController::class, 'neraca'])->name('laporan.neraca');
    Route::get('/laporan/print/{type}', [LaporanController::class, 'print'])->name('laporan.print');

    // Excel Export Routes - MUST be defined BEFORE the generic /{type} route
    Route::get('/laporan/export/simpanan-per-anggota', [LaporanController::class, 'exportSimpananPerAnggota'])->name('laporan.export-simpanan-per-anggota');
    Route::get('/laporan/export/rekap-simpanan', [LaporanController::class, 'exportRekapSimpanan'])->name('laporan.export-rekap-simpanan');
    Route::get('/laporan/rekap-simpanan/export', [LaporanController::class, 'exportRekapSimpananAnggota'])->name('laporan.rekap-simpanan-export');
    Route::get('/laporan/rekap-simpanan/print', [LaporanController::class, 'printRekapSimpananAnggota'])->name('laporan.rekap-simpanan-print');
    Route::get('/laporan/export/pembiayaan-per-anggota', [LaporanController::class, 'exportPembiayaanPerAnggota'])->name('laporan.export-pembiayaan-per-anggota');
    Route::get('/laporan/export/laba-rugi', [LaporanController::class, 'exportLabaRugi'])->name('laporan.export-laba-rugi');
    Route::get('/laporan/export/neraca', [LaporanController::class, 'exportNeraca'])->name('laporan.export-neraca');

    // Additional Export Routes for Laporan Lainnya
    Route::get('/laporan/export/tunggakan', [LaporanController::class, 'exportTunggakan'])->name('laporan.export-tunggakan');
    Route::get('/laporan/export/periode-transaksi', [LaporanController::class, 'exportPeriodeTransaksi'])->name('laporan.export-periode-transaksi');
    Route::get('/laporan/export/angsuran', [LaporanController::class, 'exportAngsuran'])->name('laporan.export-angsuran');

    // Generic export route - MUST be defined AFTER all specific export routes
    Route::get('/laporan/export/{type}', [LaporanController::class, 'export'])->name('laporan.export');
});

// Anggota Routes (All authenticated users)
Route::prefix('anggota')->name('anggota.')->middleware(['auth', 'anggota'])->group(function () {
    Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AnggotaController::class, 'profile'])->name('profile');
    Route::put('/profile', [AnggotaController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/download-kartu', [AnggotaController::class, 'downloadKartu'])->name('download-kartu');

    // Simpanan (View Only - Anggota cannot create)
    Route::get('/simpanan', [AnggotaController::class, 'simpananIndex'])->name('simpanan.index');
    Route::get('/simpanan/{id}', [AnggotaController::class, 'simpananShow'])->name('simpanan.show');
    Route::get('/simpanan/{id}/print', [AnggotaController::class, 'simpananPrint'])->name('simpanan.print');

    // Pengajuan Pembiayaan
    Route::get('/pengajuan-test', function() {
        return 'Test route works';
    })->name('pengajuan.test');

    // Using fully qualified namespace
    Route::resource('pengajuan', \App\Http\Controllers\Anggota\PengajuanPembiayaanController::class)->parameters([
        'pengajuan' => 'id'
    ]);
    Route::post('/pengajuan/{id}/submit', [\App\Http\Controllers\Anggota\PengajuanPembiayaanController::class, 'submit'])->name('pengajuan.submit');

    // Pembiayaan
    Route::get('/pembiayaan', [AnggotaController::class, 'pembiayaanIndex'])->name('pembiayaan.index');
    Route::get('/pembiayaan/{id}', [AnggotaController::class, 'pembiayaanShow'])->name('pembiayaan.show');
});

// Secure File Download Routes
Route::prefix('files')->name('files.')->middleware(['auth'])->group(function () {
    Route::get('/pengajuan/{pengajuanId}/{field}/download', [FileDownloadController::class, 'downloadPengajuanFile'])
        ->name('pengajuan.download');
    Route::get('/pengajuan/{pengajuanId}/{field}/preview', [FileDownloadController::class, 'previewPengajuanFile'])
        ->name('pengajuan.preview');
});



// Default route untuk authenticated users
Route::get('/dashboard', function () {
    $user = auth()->user();
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'pengurus':
            return redirect()->route('pengurus.dashboard');
        case 'anggota':
            return redirect()->route('anggota.dashboard');
        default:
            return redirect('/');
    }
})->middleware('auth')->name('dashboard');

// Documentation Routes
Route::prefix('documentation')->name('documentation.')->group(function () {
    Route::get('/user-manual-pdf', [DocumentationController::class, 'generateUserManualPDF'])->name('user-manual-pdf');
    Route::get('/user-manual-preview', [DocumentationController::class, 'previewUserManualPDF'])->name('user-manual-preview');
    Route::get('/user-manual-anggota-pdf', [DocumentationController::class, 'generateUserManualAnggotaPDF'])->name('user-manual-anggota-pdf');
    Route::get('/user-manual-admin-pdf', [DocumentationController::class, 'generateUserManualAdminPDF'])->name('user-manual-admin-pdf');
    Route::get('/user-manual-pengurus-pdf', [DocumentationController::class, 'generateUserManualPengurusPDF'])->name('user-manual-pengurus-pdf');
    Route::post('/upload-screenshot', [DocumentationController::class, 'uploadScreenshot'])->name('upload-screenshot');
});

// Manual Preview Routes
Route::prefix('manual-preview')->name('manual-preview.')->group(function () {
    Route::get('/', [ManualPreviewController::class, 'index'])->name('index');
    Route::get('/api/manual/{role}', [ManualPreviewController::class, 'getManualData'])->name('data');
    Route::get('/api/manuals', [ManualPreviewController::class, 'getAllManuals'])->name('all');
});

// Manual Landing Page
Route::get('/manual', [ManualPreviewController::class, 'index'])->name('manual.landing');

// Temporary route untuk buat user pengurus (development only)
Route::get('/create-pengurus', function() {
    // Cek apakah sudah ada
    $existing = User::where('email', 'pengurus@koperasi.local')->first();
    if ($existing) {
        return 'User pengurus sudah ada! Email: pengurus@koperasi.local, Password: password123';
    }

    $user = User::create([
        'name' => 'Pengurus Koperasi',
        'email' => 'pengurus@koperasi.local',
        'password' => Hash::make('password123'),
        'role' => 'pengurus',
        'email_verified_at' => now()
    ]);

    return 'User pengurus berhasil dibuat!<br>Email: pengurus@koperasi.local<br>Password: password123<br><a href="/login">Klik Login</a>';
});

// Route khusus untuk menjalankan recalculate saldo (hanya admin yang bisa akses)
Route::prefix('admin-system')->name('admin-system.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/recalculate-saldo', function() {
        // Cek parameter secret key untuk keamanan
        $secretKey = request('key');
        $validKey = 'koperasi-syariah-2025'; // Ganti dengan key yang aman

        if ($secretKey !== $validKey) {
            return response('Unauthorized: Invalid secret key', 403);
        }

        try {
            $totalUpdated = 0;
            $anggotaProcessed = 0;

            // Ambil semua anggota aktif
            $anggotas = \App\Models\Anggota::where('status_keanggotaan', 'aktif')->get();
            $jenisSimpanans = \App\Models\JenisSimpanan::where('status', 1)->get();

            foreach ($anggotas as $anggota) {
                $hasUpdate = false;

                foreach ($jenisSimpanans as $jenisSimpanan) {
                    // Ambil semua transaksi verified untuk anggota dan jenis simpanan ini
                    // Urutkan ASCENDING (dari terlama ke terbaru) untuk kalkulasi saldo yang benar
                    $transaksi = \App\Models\TransaksiSimpanan::where('anggota_id', $anggota->id)
                        ->where('jenis_simpanan_id', $jenisSimpanan->id)
                        ->where('status', 'verified')
                        ->orderBy('tahun')
                        ->orderBy('bulan')
                        ->orderBy('tanggal_transaksi')
                        ->orderBy('created_at')
                        ->orderBy('id')
                        ->get();

                    if ($transaksi->isEmpty()) {
                        continue;
                    }

                    $runningSaldo = 0;

                    foreach ($transaksi as $trx) {
                        $saldoSebelumnya = $runningSaldo;

                        // Hitung saldo setelahnya
                        if ($trx->jenis_transaksi == 'setor') {
                            $runningSaldo += $trx->jumlah;
                        } elseif ($trx->jenis_transaksi == 'tarik') {
                            $runningSaldo -= $trx->jumlah;
                        }

                        $saldoSetelahnya = $runningSaldo;

                        // Update jika berbeda
                        if ($trx->saldo_sebelumnya != $saldoSebelumnya || $trx->saldo_setelahnya != $saldoSetelahnya) {
                            $trx->saldo_sebelumnya = $saldoSebelumnya;
                            $trx->saldo_setelahnya = $saldoSetelahnya;
                            $trx->save();
                            $totalUpdated++;
                            $hasUpdate = true;
                        }
                    }
                }

                if ($hasUpdate) {
                    $anggotaProcessed++;
                }
            }

            $output = "<html><head><title>Recalculate Saldo</title><style>body{font-family:Arial,sans-serif;padding:20px;max-width:600px;margin:0 auto;} .success{color:green;} .info{color:blue;}</style></head><body>";
            $output .= "<h1>Hasil Recalculate Saldo</h1>";
            $output .= "<p class='success'><strong>Status:</strong> Sukses</p>";
            $output .= "<p><strong>Total Anggota Processed:</strong> " . $anggotaProcessed . "</p>";
            $output .= "<p><strong>Total Records Updated:</strong> " . $totalUpdated . "</p>";
            $output .= "<hr><p class='info'>Saldo simpanan telah berhasil dihitung ulang.</p>";
            $output .= "<p><a href='/admin/dashboard'>Kembali ke Dashboard</a></p>";
            $output .= "</body></html>";

            return $output;

        } catch (\Exception $e) {
            return "<html><body><h1>Error</h1><p style='color:red'>" . $e->getMessage() . "</p><p>File: " . $e->getFile() . "</p><p>Line: " . $e->getLine() . "</p></body></html>";
        }
    })->name('recalculate-saldo');
});
