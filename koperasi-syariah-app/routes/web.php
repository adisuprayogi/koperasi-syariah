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

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/ubah-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/ubah-password', [AuthController::class, 'changePassword'])->name('password.update');

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

// Pengurus Routes (Admin & Pengurus)
Route::prefix('pengurus')->name('pengurus.')->middleware(['auth', 'pengurus-or-admin'])->group(function () {
    Route::get('/dashboard', [PengurusController::class, 'dashboard'])->name('dashboard');

    // Anggota Management
    Route::get('/anggota', [PengurusController::class, 'anggotaIndex'])->name('anggota.index');
    Route::get('/anggota/create', [PengurusController::class, 'anggotaCreate'])->name('anggota.create');
    Route::post('/anggota', [PengurusController::class, 'anggotaStore'])->name('anggota.store');
    Route::get('/anggota/{id}/edit', [PengurusController::class, 'anggotaEdit'])->name('anggota.edit');
    Route::put('/anggota/{id}', [PengurusController::class, 'anggotaUpdate'])->name('anggota.update');
    Route::delete('/anggota/{id}', [PengurusController::class, 'anggotaDestroy'])->name('anggota.destroy');

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
    Route::post('/pembiayaan/{id}/generate-jadwal', [PengurusController::class, 'generateJadwalAngsuran'])->name('pembiayaan.generate-jadwal');
    Route::get('/pembiayaan/{id}/print/{angsuranId}', [PengurusController::class, 'printBuktiBayar'])->name('pembiayaan.print-bukti');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::get('/laporan/mingguan', [LaporanController::class, 'mingguan'])->name('laporan.mingguan');
    Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::get('/laporan/simpanan-wajib', [LaporanController::class, 'simpananWajib'])->name('laporan.simpanan-wajib');
    Route::get('/laporan/simpanan-per-anggota', [LaporanController::class, 'simpananPerAnggota'])->name('laporan.simpanan-per-anggota');
    Route::get('/laporan/pembiayaan-per-anggota', [LaporanController::class, 'pembiayaanPerAnggota'])->name('laporan.pembiayaan-per-anggota');
    Route::get('/laporan/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi');
    Route::get('/laporan/neraca', [LaporanController::class, 'neraca'])->name('laporan.neraca');
    Route::get('/laporan/print/{type}', [LaporanController::class, 'print'])->name('laporan.print');
    Route::get('/laporan/export/{type}', [LaporanController::class, 'export'])->name('laporan.export');
});

// Anggota Routes (All authenticated users)
Route::prefix('anggota')->name('anggota.')->middleware(['auth', 'anggota'])->group(function () {
    Route::get('/dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AnggotaController::class, 'profile'])->name('profile');
    Route::put('/profile', [AnggotaController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/download-kartu', [AnggotaController::class, 'downloadKartu'])->name('download-kartu');

    // Simpanan
    Route::get('/simpanan', [AnggotaController::class, 'simpananIndex'])->name('simpanan.index');
    Route::get('/simpanan/{id}', [AnggotaController::class, 'simpananShow'])->name('simpanan.show');

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
