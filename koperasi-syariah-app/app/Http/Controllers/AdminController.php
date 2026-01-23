<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Koperasi;
use App\Models\JenisSimpanan;
use App\Models\JenisPembiayaan;
use App\Models\TransaksiSimpanan;
use App\Models\Anggota;
use App\Models\PengajuanPembiayaan;
use App\Models\Angsuran;
use App\Services\ExcelDateParser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Helper function to parse Excel date with fallback
     */
    private function parseDate($dateValue)
    {
        if (empty($dateValue)) {
            return null;
        }

        try {
            // Try using ExcelDateParser if available
            if (class_exists('App\Services\ExcelDateParser')) {
                return ExcelDateParser::parseDate($dateValue);
            }

            // Fallback to manual parsing
            if (is_string($dateValue) && strpos($dateValue, '-') !== false) {
                return date('Y-m-d', strtotime($dateValue));
            }

            // If it's numeric (Excel date format)
            if (is_numeric($dateValue)) {
                try {
                    // Use Carbon's built-in Excel date conversion
                    return Carbon::createFromFormat('Y-m-d', '1900-01-01')
                                  ->addDays($dateValue - 2)
                                  ->format('Y-m-d');
                } catch (\Exception $e) {
                    return null;
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        // Statistik Pengurus
        $totalPengurus = Pengurus::count();
        $pengurusAktif = Pengurus::where('status', 'aktif')->count();
        $pengurusByPosisi = Pengurus::select('posisi', DB::raw('count(*) as total'))
                                ->groupBy('posisi')
                                ->get();

        // Statistik Anggota
        $totalAnggota = \App\Models\Anggota::count();
        $anggotaAktif = \App\Models\Anggota::where('status_keanggotaan', 'aktif')->count();
        $anggotaByJenis = \App\Models\Anggota::select('jenis_anggota', DB::raw('count(*) as total'))
                              ->groupBy('jenis_anggota')
                              ->get();

        // Statistik Master Data
        $totalJenisSimpanan = JenisSimpanan::count();
        $totalJenisPembiayaan = JenisPembiayaan::count();
        $simpananAktif = JenisSimpanan::where('status', 1)->count();
        $pembiayaanAktif = JenisPembiayaan::where('status', 1)->count();

        // Data Koperasi
        $koperasi = Koperasi::first();

        // Aktivitas terkini (dapat ditambahkan log system nantinya)
        $recentActivities = collect([
            (object) ['activity' => 'Login Administrator', 'time' => now()->format('H:i')],
            (object) ['activity' => 'System Check', 'time' => now()->subMinutes(5)->format('H:i')],
        ]);

        return view('admin.dashboard', compact(
            'totalPengurus',
            'pengurusAktif',
            'pengurusByPosisi',
            'totalAnggota',
            'anggotaAktif',
            'anggotaByJenis',
            'totalJenisSimpanan',
            'totalJenisPembiayaan',
            'simpananAktif',
            'pembiayaanAktif',
            'koperasi',
            'recentActivities'
        ));
    }

    /**
     * Index Pengurus
     */
    public function pengurusIndex()
    {
        // Get only active pengurus (not soft-deleted)
        $pengurus = Pengurus::with('user')->latest()->paginate(10);
        return view('admin.pengurus.index', compact('pengurus'));
    }

    /**
     * Create Pengurus
     */
    public function pengurusCreate()
    {
        return view('admin.pengurus.create');
    }

    /**
     * Store Pengurus
     */
    public function pengurusStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'nullable|string',
            'posisi' => 'required|in:ketua,sekretaris,bendahara,pengurus_lainnya',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'tanggal_menjabat' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pengurus',
                'first_login' => true,
            ]);

            // Create pengurus data
            Pengurus::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->name,
                'email' => $request->email,
                'posisi' => $request->posisi,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'tanggal_menjabat' => $request->tanggal_menjabat,
                'status' => 'aktif',
            ]);

            DB::commit();

            return redirect()->route('admin.pengurus.index')
                ->with('success', 'Pengurus berhasil ditambahkan. Akun telah dibuat dengan email: ' . $request->email);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menambahkan pengurus: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit Pengurus
     */
    public function pengurusEdit($id)
    {
        $pengurus = Pengurus::with('user')->findOrFail($id);

        // Check if user exists, if not redirect with error
        if (!$pengurus->user) {
            return redirect()->route('admin.pengurus.index')
                ->with('error', 'Data user untuk pengurus ini tidak ditemukan. Silakan hubungi administrator.');
        }

        return view('admin.pengurus.edit', compact('pengurus'));
    }

    /**
     * Update Pengurus
     */
    public function pengurusUpdate(Request $request, $id)
    {
        $pengurus = Pengurus::with('user')->findOrFail($id);

        // Check if user exists, if not return error
        if (!$pengurus->user) {
            return back()->with('error', 'Data user untuk pengurus ini tidak ditemukan. Tidak dapat melakukan update.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $pengurus->user_id,
            'posisi' => 'required|in:ketua,sekretaris,bendahara,pengurus_lainnya',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'tanggal_menjabat' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // Update user
            $pengurus->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'required|string|min:8|confirmed',
                ]);
                $pengurus->user->update([
                    'password' => Hash::make($request->password),
                    'first_login' => true, // Force password change on next login
                ]);
            }

            // Update pengurus data
            $pengurus->update([
                'nama_lengkap' => $request->name,
                'email' => $request->email,
                'posisi' => $request->posisi,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'tanggal_menjabat' => $request->tanggal_menjabat,
            ]);

            DB::commit();

            return redirect()->route('admin.pengurus.index')
                ->with('success', 'Data pengurus berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengupdate pengurus: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Destroy Pengurus
     */
    public function pengurusDestroy($id)
    {
        $pengurus = Pengurus::findOrFail($id);

        // Prevent deletion if the pengurus is the current user
        if ($pengurus->user_id == auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri');
        }

        try {
            DB::beginTransaction();

            // Soft delete pengurus
            $pengurus->delete();

            // Also soft delete the associated user account
            $pengurus->user->delete();

            DB::commit();

            return redirect()->route('admin.pengurus.index')
                ->with('success', 'Pengurus berhasil dihapus (soft delete). Data dapat dipulihkan kembali.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus pengurus: ' . $e->getMessage());
        }
    }

    /**
     * Restore Pengurus
     */
    public function pengurusRestore($id)
    {
        try {
            DB::beginTransaction();

            // Find the soft-deleted pengurus
            $pengurus = Pengurus::withTrashed()->findOrFail($id);

            if (!$pengurus->trashed()) {
                return back()->with('error', 'Data pengurus tidak dalam status dihapus');
            }

            // Restore pengurus
            $pengurus->restore();

            // Also restore the associated user account
            if ($pengurus->user && $pengurus->user->trashed()) {
                $pengurus->user->restore();
            }

            DB::commit();

            return redirect()->route('admin.pengurus.index')
                ->with('success', 'Pengurus berhasil dipulihkan kembali.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memulihkan pengurus: ' . $e->getMessage());
        }
    }

    /**
     * Index Koperasi
     */
    public function koperasiIndex()
    {
        $koperasi = Koperasi::first();
        return view('admin.koperasi.index', compact('koperasi'));
    }

    /**
     * Edit Koperasi
     */
    public function koperasiEdit()
    {
        return view('admin.koperasi.edit');
    }

    /**
     * Update Koperasi
     */
    public function koperasiUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_koperasi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'no_koperasi' => 'required|string|max:50',
            'tanggal_berdiri' => 'required|date|before:today',
            'no_akta_notaris' => 'required|string|max:100',
            'tanggal_akta' => 'required|date|before_or_equal:today',
            'nama_notaris' => 'required|string|max:100',
            'ketua_nama' => 'required|string|max:100',
            'ketua_nik' => 'required|string|max:20',
            'sekretaris_nama' => 'required|string|max:100',
            'sekretaris_nik' => 'required|string|max:20',
            'bendahara_nama' => 'required|string|max:100',
            'bendahara_nik' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get or create koperasi data
            $koperasi = Koperasi::first();

            if (!$koperasi) {
                // Create new if not exists
                $koperasi = new Koperasi();
            }

            // Handle logo upload
            $data = $request->except('logo');

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($koperasi->logo && Storage::exists('public/' . $koperasi->logo)) {
                    Storage::delete('public/' . $koperasi->logo);
                }

                $logo = $request->file('logo');
                $logoName = time() . '_' . $logo->getClientOriginalName();
                $logo->storeAs('public/koperasi/logo', $logoName);
                $data['logo'] = 'koperasi/logo/' . $logoName;
            }

            $koperasi->fill($data);
            $koperasi->save();

            return redirect()
                ->route('admin.koperasi.index')
                ->with('success', 'Data koperasi berhasil diupdate');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal mengupdate data koperasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Index Jenis Simpanan
     */
    public function jenisSimpananIndex()
    {
        $jenisSimpanan = JenisSimpanan::latest()->paginate(10);
        return view('admin.jenis-simpanan.index', compact('jenisSimpanan'));
    }

    /**
     * Create Jenis Simpanan
     */
    public function jenisSimpananCreate()
    {
        return view('admin.jenis-simpanan.create');
    }

    /**
     * Store Jenis Simpanan
     */
    public function jenisSimpananStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jenis' => 'required|string|max:10|unique:jenis_simpanan,kode_jenis',
            'nama_simpanan' => 'required|string|max:255',
            'tipe_simpanan' => 'required|in:modal,pokok,wajib,sukarela',
            'nisbah' => 'required|numeric|min:0|max:100',
            'minimal_setor' => 'required|numeric|min:0',
            'maksimal_setor' => 'nullable|numeric|min:0',
            'bisa_ditarik' => 'required|boolean',
            'aturan_penarikan' => 'nullable|string',
            'periode_hitung_bunga' => 'required|in:bulanan,tahunan,otomatis,manual',
            'status' => 'required|boolean',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            JenisSimpanan::create($request->all());

            return redirect()
                ->route('admin.jenis-simpanan.index')
                ->with('success', 'Jenis simpanan berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menambahkan jenis simpanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit Jenis Simpanan
     */
    public function jenisSimpananEdit($id)
    {
        $jenisSimpanan = JenisSimpanan::findOrFail($id);
        return view('admin.jenis-simpanan.edit', compact('jenisSimpanan'));
    }

    /**
     * Update Jenis Simpanan
     */
    public function jenisSimpananUpdate(Request $request, $id)
    {
        $jenisSimpanan = JenisSimpanan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_jenis' => 'required|string|max:10|unique:jenis_simpanan,kode_jenis,' . $id,
            'nama_simpanan' => 'required|string|max:255',
            'tipe_simpanan' => 'required|in:modal,pokok,wajib,sukarela',
            'nisbah' => 'required|numeric|min:0|max:100',
            'minimal_setor' => 'required|numeric|min:0',
            'maksimal_setor' => 'nullable|numeric|min:0',
            'bisa_ditarik' => 'required|boolean',
            'aturan_penarikan' => 'nullable|string',
            'periode_hitung_bunga' => 'required|in:bulanan,tahunan,otomatis,manual',
            'status' => 'required|boolean',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $jenisSimpanan->update($request->all());

            return redirect()
                ->route('admin.jenis-simpanan.index')
                ->with('success', 'Jenis simpanan berhasil diupdate');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal mengupdate jenis simpanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Destroy Jenis Simpanan
     */
    public function jenisSimpananDestroy($id)
    {
        $jenisSimpanan = JenisSimpanan::findOrFail($id);

        try {
            $jenisSimpanan->delete();

            return redirect()
                ->route('admin.jenis-simpanan.index')
                ->with('success', 'Jenis simpanan berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus jenis simpanan: ' . $e->getMessage());
        }
    }

    /**
     * Index Jenis Pembiayaan
     */
    public function jenisPembiayaanIndex()
    {
        $jenisPembiayaan = JenisPembiayaan::latest()->paginate(10);
        return view('admin.jenis-pembiayaan.index', compact('jenisPembiayaan'));
    }

    /**
     * Create Jenis Pembiayaan
     */
    public function jenisPembiayaanCreate()
    {
        return view('admin.jenis-pembiayaan.create');
    }

    /**
     * Store Jenis Pembiayaan
     */
    public function jenisPembiayaanStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_jenis' => 'required|string|max:10|unique:jenis_pembiayaans,kode_jenis',
            'nama_pembiayaan' => 'required|string|max:255',
            'tipe_pembiayaan' => 'required|in:murabahah,mudharabah,musyarakah,qardh,ijarah',
            'margin' => 'required|numeric|min:0|max:100',
            'bagi_hasil' => 'required|numeric|min:0|max:100',
            'periode_hitung' => 'required|in:bulanan,tahunan,otomatis,jtempo',
            'minimal_pembiayaan' => 'required|numeric|min:0',
            'maksimal_pembiayaan' => 'nullable|numeric|min:0',
            'jangka_waktu_min' => 'required|integer|min:1',
            'jangka_waktu_max' => 'required|integer|min:1',
            'syarat_dukung' => 'nullable|string',
            'status' => 'required|boolean',
            'keterangan' => 'nullable|string',
        ]);

        // Custom validation: minimal tidak boleh lebih besar dari maksimal
        $validator->after(function ($validator) use ($request) {
            $min = $request->input('minimal_pembiayaan');
            $max = $request->input('maksimal_pembiayaan');

            // Jika maksimal diisi, minimal harus <= maksimal
            if (!is_null($max) && $max > 0 && floatval($min) > floatval($max)) {
                $validator->errors()->add('minimal_pembiayaan', 'Minimal pembiayaan tidak boleh lebih besar dari maksimal pembiayaan');
                $validator->errors()->add('maksimal_pembiayaan', 'Maksimal pembiayaan tidak boleh lebih kecil dari minimal pembiayaan');
            }

            // Jangka waktu min tidak boleh lebih besar dari max
            $tenorMin = intval($request->input('jangka_waktu_min'));
            $tenorMax = intval($request->input('jangka_waktu_max'));

            if ($tenorMin > $tenorMax) {
                $validator->errors()->add('jangka_waktu_min', 'Jangka waktu minimal tidak boleh lebih besar dari maksimal');
                $validator->errors()->add('jangka_waktu_max', 'Jangka waktu maksimal tidak boleh lebih kecil dari minimal');
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            JenisPembiayaan::create($request->all());

            return redirect()
                ->route('admin.jenis-pembiayaan.index')
                ->with('success', 'Jenis pembiayaan berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menambahkan jenis pembiayaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit Jenis Pembiayaan
     */
    public function jenisPembiayaanEdit($id)
    {
        $jenisPembiayaan = JenisPembiayaan::findOrFail($id);
        return view('admin.jenis-pembiayaan.edit', compact('jenisPembiayaan'));
    }

    /**
     * Update Jenis Pembiayaan
     */
    public function jenisPembiayaanUpdate(Request $request, $id)
    {
        $jenisPembiayaan = JenisPembiayaan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_jenis' => 'required|string|max:10|unique:jenis_pembiayaans,kode_jenis,' . $id,
            'nama_pembiayaan' => 'required|string|max:255',
            'tipe_pembiayaan' => 'required|in:murabahah,mudharabah,musyarakah,qardh,ijarah',
            'margin' => 'required|numeric|min:0|max:100',
            'bagi_hasil' => 'required|numeric|min:0|max:100',
            'periode_hitung' => 'required|in:bulanan,tahunan,otomatis,jtempo',
            'minimal_pembiayaan' => 'required|numeric|min:0',
            'maksimal_pembiayaan' => 'nullable|numeric|min:0',
            'jangka_waktu_min' => 'required|integer|min:1',
            'jangka_waktu_max' => 'required|integer|min:1',
            'syarat_dukung' => 'nullable|string',
            'status' => 'required|boolean',
            'keterangan' => 'nullable|string',
        ]);

        // Custom validation: minimal tidak boleh lebih besar dari maksimal
        $validator->after(function ($validator) use ($request) {
            $min = $request->input('minimal_pembiayaan');
            $max = $request->input('maksimal_pembiayaan');

            // Jika maksimal diisi, minimal harus <= maksimal
            if (!is_null($max) && $max > 0 && floatval($min) > floatval($max)) {
                $validator->errors()->add('minimal_pembiayaan', 'Minimal pembiayaan tidak boleh lebih besar dari maksimal pembiayaan');
                $validator->errors()->add('maksimal_pembiayaan', 'Maksimal pembiayaan tidak boleh lebih kecil dari minimal pembiayaan');
            }

            // Jangka waktu min tidak boleh lebih besar dari max
            $tenorMin = intval($request->input('jangka_waktu_min'));
            $tenorMax = intval($request->input('jangka_waktu_max'));

            if ($tenorMin > $tenorMax) {
                $validator->errors()->add('jangka_waktu_min', 'Jangka waktu minimal tidak boleh lebih besar dari maksimal');
                $validator->errors()->add('jangka_waktu_max', 'Jangka waktu maksimal tidak boleh lebih kecil dari minimal');
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $jenisPembiayaan->update($request->all());

            return redirect()
                ->route('admin.jenis-pembiayaan.index')
                ->with('success', 'Jenis pembiayaan berhasil diupdate');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal mengupdate jenis pembiayaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Destroy Jenis Pembiayaan
     */
    public function jenisPembiayaanDestroy($id)
    {
        $jenisPembiayaan = JenisPembiayaan::findOrFail($id);

        try {
            $jenisPembiayaan->delete();

            return redirect()
                ->route('admin.jenis-pembiayaan.index')
                ->with('success', 'Jenis pembiayaan berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus jenis pembiayaan: ' . $e->getMessage());
        }
    }

    // ===== IMPORT FUNCTIONALITY =====

    /**
     * Show Simpanan Import Form
     */
    public function simpananImport()
    {
        return view('admin.simpanan.import');
    }

    /**
     * Download Simpanan Template
     */
    public function simpananDownloadTemplate()
    {
        // Create proper CSV template
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_simpanan.csv"',
        ];

        // CSV content with BOM for Excel compatibility
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        $csvContent .= "No Anggota,Jenis Simpanan,Jenis Transaksi,Jumlah,Tanggal Transaksi,Bulan,Tahun,Keterangan\n";
        $csvContent .= "2512.00001,Simpanan Pokok,setor,500000,2024-12-01,12,2024,Simpanan pokok anggota baru\n";
        $csvContent .= "2512.00002,Simpanan Wajib,setor,100000,2024-12-01,12,2024,Simpanan wajib bulan Desember\n";
        $csvContent .= "2512.00001,Simpanan Wajib,setor,100000,2025-01-05,12,2024,Pembayaran terlambat bulan Desember\n";
        $csvContent .= "2512.00001,Simpanan Sukarela,tarik,200000,2024-12-15,12,2024,Penarikan simpanan sukarela";

        return response($csvContent, 200, $headers);
    }

    /**
     * Process Simpanan Import
     */
    public function simpananImportProcess(Request $request)
    {
        // \Log::info('Simpanan import request received');

        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            \Log::info('Starting simpanan import process');
            // Debug: log file info
            \Log::info('Processing simpanan import, file: ' . $request->file('excel_file')->getClientOriginalName());

            $import = new class implements ToArray {
                public $data = [];
                public function array(array $array) {
                    $this->data = $array;
                }
            };

            // Simple import for now - handle with default reader first
            \Log::info('Starting Excel import');
            try {
                \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('excel_file'));
                \Log::info('Excel import completed');
            } catch (\Exception $e) {
                \Log::error('Excel import failed: ' . $e->getMessage());
                throw $e;
            }
            $data = $import->data;

            // Debug: log data count
            \Log::info('Import data count: ' . count($data));

            // Skip header
            $dataRows = array_slice($data, 1);
            $errors = [];
            $successCount = 0;

            \Log::info('Data rows after skipping header: ' . count($dataRows));

            DB::beginTransaction();
            try {
                foreach ($dataRows as $index => $row) {
                    $rowIndex = $index + 2;

                    // Debug: log row data
                    \Log::info("Processing row $rowIndex: " . json_encode($row));

                    $noAnggota = trim($row[0] ?? '');
                    $jenisSimpanan = trim($row[1] ?? '');
                    $jenisTransaksi = strtolower(trim($row[2] ?? ''));
                    $jumlah = trim($row[3] ?? '');
                    $tanggalTransaksi = trim($row[4] ?? '');
                    $bulan = trim($row[5] ?? '');
                    $tahun = trim($row[6] ?? '');

                    \Log::info("Parsed data - NoAnggota: $noAnggota, Jenis: $jenisSimpanan, Transaksi: $jenisTransaksi, Jumlah: $jumlah, Tanggal: $tanggalTransaksi, Bulan: $bulan, Tahun: $tahun");

                    if (empty($noAnggota) || empty($jenisSimpanan) || empty($jenisTransaksi) || empty($jumlah) || empty($tanggalTransaksi) || empty($bulan) || empty($tahun)) {
                        $errors[] = "Baris $rowIndex: Field wajib kosong";
                        \Log::warning("Empty field in row $rowIndex");
                        continue;
                    }

                    // Validate data
                    $anggota = Anggota::where('no_anggota', $noAnggota)->first();
                    \Log::info("Looking for anggota with no_anggota: $noAnggota, found: " . ($anggota ? $anggota->id : 'null'));
                    if (!$anggota) {
                        $errors[] = "Baris $rowIndex: No Anggota tidak ditemukan";
                        \Log::warning("Anggota not found for $noAnggota");
                        continue;
                    }

                    $jenisSimpananModel = JenisSimpanan::where('nama_simpanan', $jenisSimpanan)->first();
                    \Log::info("Looking for jenis simpanan: $jenisSimpanan, found: " . ($jenisSimpananModel ? $jenisSimpananModel->id : 'null'));
                    if (!$jenisSimpananModel) {
                        $errors[] = "Baris $rowIndex: Jenis Simpanan tidak ditemukan";
                        \Log::warning("Jenis simpanan not found for $jenisSimpanan");
                        continue;
                    }

                    if (!in_array($jenisTransaksi, ['setor', 'tarik'])) {
                        $errors[] = "Baris $rowIndex: Jenis Transaksi harus 'setor' atau 'tarik'";
                        continue;
                    }

                    if (!is_numeric($jumlah) || $jumlah <= 0) {
                        $errors[] = "Baris $rowIndex: Jumlah harus angka positif";
                        continue;
                    }

                    $tanggalParsed = $this->parseDate($tanggalTransaksi);
                    if (!$tanggalParsed) {
                        $errors[] = "Baris $rowIndex: Format tanggal tidak valid";
                        continue;
                    }

                    // Validate bulan
                    if (!is_numeric($bulan) || $bulan < 1 || $bulan > 12) {
                        $errors[] = "Baris $rowIndex: Bulan harus angka antara 1-12";
                        continue;
                    }

                    // Validate tahun
                    if (!is_numeric($tahun) || $tahun < 2020 || $tahun > (date('Y') + 1)) {
                        $errors[] = "Baris $rowIndex: Tahun tidak valid (harus 2020-" . (date('Y') + 1) . ")";
                        continue;
                    }

                    // Calculate saldo
                    $saldo = TransaksiSimpanan::calculateSaldo(
                        $anggota->id,
                        $jenisSimpananModel->id,
                        $jenisTransaksi,
                        (float)$jumlah
                    );

                    // Create transaksi
                    try {
                        \Log::info("Creating transaksi for anggota_id: {$anggota->id}, jenis_simpanan_id: {$jenisSimpananModel->id}");

                        $transaksiData = [
                            'kode_transaksi' => TransaksiSimpanan::generateKodeTransaksi($jenisTransaksi),
                            'anggota_id' => $anggota->id,
                            'jenis_simpanan_id' => $jenisSimpananModel->id,
                            'pengurus_id' => Pengurus::where('user_id', auth()->id())->first()?->id,
                            'jenis_transaksi' => $jenisTransaksi,
                            'jumlah' => (float)$jumlah,
                            'tanggal_transaksi' => $tanggalParsed,
                            'bulan' => (int)$bulan,
                            'tahun' => (int)$tahun,
                            'keterangan' => $row[7] ?? 'Import dari CSV',
                            'saldo_sebelumnya' => $saldo['saldo_sebelumnya'],
                            'saldo_setelahnya' => $saldo['saldo_setelahnya'],
                            'status' => 'verified',
                            'verified_at' => now(),
                            'verified_by' => auth()->id(),
                        ];

                        \Log::info("Transaksi data: " . json_encode($transaksiData));

                        $transaksi = TransaksiSimpanan::create($transaksiData);
                        \Log::info("Transaksi created with ID: " . $transaksi->id);

                        $successCount++;
                    } catch (\Exception $e) {
                        \Log::error("Error creating transaksi: " . $e->getMessage());
                        \Log::error("Stack trace: " . $e->getTraceAsString());
                        $errors[] = "Baris $rowIndex: Error creating transaksi - " . $e->getMessage();
                        continue;
                    }
                }

                \Log::info("Committing transaction with $successCount records");
                DB::commit();
                \Log::info("Transaction committed successfully");

                $message = "Berhasil import $successCount data simpanan";
                if (!empty($errors)) {
                    $message .= ". Error: " . implode('; ', array_slice($errors, 0, 3));
                    if (count($errors) > 3) {
                        $message .= " ... dan " . (count($errors) - 3) . " error lainnya";
                    }
                }

                \Log::info("Import completed successfully with $successCount records");
                return back()->with('success', $message);

            } catch (\Exception $e) {
                DB::rollback();
                \Log::error("Transaction failed: " . $e->getMessage());
                \Log::error("Stack trace: " . $e->getTraceAsString());
                return back()->with('error', 'Gagal memproses import: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show Pembiayaan Import Form
     */
    public function pembiayaanImport()
    {
        return view('admin.pembiayaan.import');
    }

    /**
     * Download Pembiayaan Template
     */
    public function pembiayaanDownloadTemplate()
    {
        // Create proper CSV template
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_pembiayaan.csv"',
        ];

        // CSV content with BOM for Excel compatibility
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        $csvContent .= "No Pembiayaan,No Anggota,Jenis Pembiayaan,Jumlah Pengajuan,Tenor,Tipe Angsuran,Tujuan Pembiayaan,Tanggal Pengajuan,Tanggal Pencairan,Deskripsi,No Rekening,Atas Nama Rekening\n";
        $csvContent .= "PM001,2512.00001,Pembiayaan Murabahah Motor,15000000,24,flat,Konsumtif,2024-12-01,2024-12-05,Pembiayaan pembelian motor Honda Beat,1234567890,Ahmad Yani\n";
        $csvContent .= "MM002,2512.00002,Modal Kerja Mudharabah,50000000,36,flat,Modal Kerja,2024-12-01,,Modal kerja untuk tambah stok barang,0987654321,Siti Nurhaliza";

        return response($csvContent, 200, $headers);
    }

    /**
     * Process Pembiayaan Import
     */
    public function pembiayaanImportProcess(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $import = new class implements ToArray {
                public $data = [];
                public function array(array $array) {
                    $this->data = $array;
                }
            };

            Excel::import($import, $request->file('excel_file'));
            $data = $import->data;

            // Skip header
            $dataRows = array_slice($data, 1);
            $errors = [];
            $successCount = 0;

            DB::beginTransaction();
            try {
                foreach ($dataRows as $index => $row) {
                    $rowIndex = $index + 2;

                    $noPembiayaan = trim($row[0] ?? '');
                    $noAnggota = trim($row[1] ?? '');
                    $jenisPembiayaan = trim($row[2] ?? '');
                    $jumlah = trim($row[3] ?? '');
                    $tenor = trim($row[4] ?? '');
                    $tipeAngsuran = trim($row[5] ?? 'flat'); // Default flat
                    $tujuan = trim($row[6] ?? '');
                    $tanggal = trim($row[7] ?? '');
                    $tanggalPencairan = trim($row[8] ?? '');
                    $deskripsi = trim($row[9] ?? '');
                    $noRekening = trim($row[10] ?? '');
                    $atasNama = trim($row[11] ?? '');

                    if (empty($noPembiayaan) || empty($noAnggota) || empty($jenisPembiayaan) || empty($jumlah) || empty($tenor) || empty($tujuan) || empty($tanggal)) {
                        $errors[] = "Baris $rowIndex: Field wajib kosong (No Pembiayaan, No Anggota, Jenis, Jumlah, Tenor, Tujuan, Tanggal)";
                        continue;
                    }

                    // Validations
                    // Check if no pembiayaan already exists
                    if (PengajuanPembiayaan::where('kode_pengajuan', $noPembiayaan)->exists()) {
                        $errors[] = "Baris $rowIndex: No Pembiayaan '$noPembiayaan' sudah ada di database";
                        continue;
                    }

                    $anggota = Anggota::where('no_anggota', $noAnggota)->first();
                    if (!$anggota) {
                        $errors[] = "Baris $rowIndex: No Anggota tidak ditemukan";
                        continue;
                    }

                    $jenisModel = JenisPembiayaan::where('nama_pembiayaan', $jenisPembiayaan)->first();
                    if (!$jenisModel) {
                        $errors[] = "Baris $rowIndex: Jenis Pembiayaan tidak ditemukan";
                        continue;
                    }


                    if (!is_numeric($jumlah) || $jumlah <= 0) {
                        $errors[] = "Baris $rowIndex: Jumlah harus angka positif";
                        continue;
                    }

                    if (!is_numeric($tenor) || $tenor <= 0 || $tenor > 60) {
                        $errors[] = "Baris $rowIndex: Tenor harus 1-60 bulan";
                        continue;
                    }

                    // Validasi tipe angsuran
                    $tipeAngsuranMap = [
                        'flat' => 'flat',
                        'menurun' => 'menurun',
                        'menaik' => 'menaik'
                    ];
                    $tipeAngsuranKey = $tipeAngsuranMap[strtolower($tipeAngsuran)] ?? 'flat';
                    if (!in_array($tipeAngsuranKey, ['flat', 'menurun', 'menaik'])) {
                        $errors[] = "Baris $rowIndex: Tipe Angsuran tidak valid. Pilihan: flat, menurun, menaik";
                        continue;
                    }

                    // Map tujuan
                    $tujuanMap = [
                        'modal kerja' => 'modal_kerja',
                        'investasi' => 'investasi',
                        'konsumtif' => 'konsumtif',
                        'pendidikan' => 'pendidikan',
                        'renovasi' => 'renovasi',
                        'lainnya' => 'lainnya'
                    ];
                    $tujuanKey = $tujuanMap[strtolower($tujuan)] ?? null;
                    if (!$tujuanKey) {
                        $errors[] = "Baris $rowIndex: Tujuan tidak valid";
                        continue;
                    }

                    $tanggalParsed = $this->parseDate($tanggal);
                    if (!$tanggalParsed) {
                        $errors[] = "Baris $rowIndex: Format tanggal tidak valid";
                        continue;
                    }

                    // Create pembiayaan
                    $marginPercent = $jenisModel->margin ?? 0;
                    // Rumus BARU: Margin per bulan dikalikan tenor
                    // Semakin lama tenor, semakin besar total margin
                    $marginPerBulan = (float)$jumlah * ($marginPercent / 100);
                    $jumlahMargin = $marginPerBulan * (int)$tenor;
                    $totalPembiayaan = (float)$jumlah + $jumlahMargin;
                    $angsuranPokok = (float)$jumlah / (int)$tenor;
                    $angsuranMargin = $marginPerBulan;

                    // Parse tanggal pencairan jika ada
                    $tanggalPencairanParsed = null;
                    if (!empty($tanggalPencairan)) {
                        $tanggalPencairanParsed = $this->parseDate($tanggalPencairan);
                    }

                    try {
                        $pembiayaan = PengajuanPembiayaan::create([
                            'kode_pengajuan' => $noPembiayaan, // Gunakan nomor dari CSV
                            'anggota_id' => $anggota->id,
                            'jenis_pembiayaan_id' => $jenisModel->id,
                            'jumlah_pengajuan' => (float)$jumlah,
                            'tenor' => (int)$tenor,
                            'tipe_angsuran' => $tipeAngsuranKey, // Gunakan dari CSV
                            'margin_percent' => $marginPercent,
                            'jumlah_margin' => $jumlahMargin,
                            'angsuran_pokok' => $angsuranPokok,
                            'angsuran_margin' => $angsuranMargin,
                            'total_angsuran' => $angsuranPokok + $angsuranMargin,
                            'tujuan_pembiayaan' => $tujuanKey,
                            'deskripsi' => $deskripsi ?: 'Import dari CSV',
                            'status' => 'approved',
                            'verified_by' => auth()->user()->pengurus ? auth()->user()->pengurus->id : null,
                            'verified_at' => $tanggalParsed,
                            'approved_by' => auth()->user()->pengurus ? auth()->user()->pengurus->id : null,
                            'approved_at' => $tanggalParsed,
                            'no_rekening' => $noRekening,
                            'atas_nama' => $atasNama ?: $anggota->nama_lengkap,
                            'tanggal_jatuh_tempo' => Carbon::parse($tanggalParsed)->addMonths((int)$tenor),
                            'created_at' => $tanggalParsed,
                            'tanggal_cair' => $tanggalPencairanParsed ?: $tanggalParsed, // Gunakan tanggal pencairan atau tanggal pengajuan
                        ]);

                        // Create angsuran using the existing method with error handling
                        try {
                            Angsuran::generateJadwalAngsuran($pembiayaan);
                        } catch (\Illuminate\Database\QueryException $e) {
                            // Handle duplicate constraint violation
                            if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'kode_angsuran') !== false) {
                                // Continue without failing - log the issue but continue import
                                \Log::warning("Duplicate angsuran codes detected for pembiayaan: {$pembiayaan->kode_pengajuan}. Skipping angsuran generation.");
                                $errors[] = "Baris $rowIndex: Anggota dengan nomor $noAnggota sudah memiliki pembiayaan aktif. Pembiayaan dibuat tapi jadwal angsuran dilewati.";
                            } else {
                                throw $e; // Re-throw if it's not a duplicate constraint error
                            }
                        }

                        $successCount++;
                    } catch (\Illuminate\Database\QueryException $e) {
                        // Handle unique constraint violations for pembiayaan
                        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                            $errors[] = "Baris $rowIndex: Anggota dengan nomor $noAnggota sudah memiliki pembiayaan dengan jenis yang sama atau sedang aktif";
                            continue;
                        } else {
                            throw $e; // Re-throw if it's not a duplicate constraint error
                        }
                    }
                }

                DB::commit();

                $message = "Berhasil import $successCount data pembiayaan";
                if (!empty($errors)) {
                    $message .= ". Error: " . implode('; ', array_slice($errors, 0, 3));
                    if (count($errors) > 3) {
                        $message .= " ... dan " . (count($errors) - 3) . " error lainnya";
                    }
                }

                return back()->with('success', $message);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show Pembayaran Angsuran Import Form
     */
    public function pembayaranAngsuranImport()
    {
        return view('admin.pembayaran-angsuran.import');
    }

    /**
     * Download Pembayaran Angsuran Template
     */
    public function pembayaranAngsuranDownloadTemplate()
    {
        // Create proper CSV template
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_pembayaran_angsuran.csv"',
        ];

        // CSV content with BOM for Excel compatibility
        $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        $csvContent .= "No Anggota,Kode Pembiayaan,Angsuran Ke,Tanggal Bayar,Jumlah Bayar,Keterangan\n";
        $csvContent .= "2512.00001,2512PM0001.001,1,2024-12-15,1325000,Pembayaran angsuran pertama\n";
        $csvContent .= "2512.00001,2512PM0001.002,2,2024-12-15,1325000,Pembayaran angsuran kedua\n";
        $csvContent .= "2512.00002,2512MM0001.001,1,2024-12-15,2500000,Pembayaran angsuran modal kerja";

        return response($csvContent, 200, $headers);
    }

    /**
     * Process Pembayaran Angsuran Import
     */
    public function pembayaranAngsuranImportProcess(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $import = new class implements ToArray {
                public $data = [];
                public function array(array $array) {
                    $this->data = $array;
                }
            };

            Excel::import($import, $request->file('excel_file'));
            $data = $import->data;

            // Skip header
            $dataRows = array_slice($data, 1);
            $errors = [];
            $successCount = 0;

            DB::beginTransaction();
            try {
                foreach ($dataRows as $index => $row) {
                    $rowIndex = $index + 2;

                    $noAnggota = trim($row[0] ?? '');
                    $kodePembiayaan = trim($row[1] ?? '');
                    $angsuranKe = trim($row[2] ?? '');
                    $tanggalBayar = trim($row[3] ?? '');
                    $jumlahBayar = trim($row[4] ?? '');

                    if (empty($noAnggota) || empty($kodePembiayaan) || empty($angsuranKe) || empty($tanggalBayar) || empty($jumlahBayar)) {
                        $errors[] = "Baris $rowIndex: Field wajib kosong";
                        continue;
                    }

                    // Validations
                    $anggota = Anggota::where('no_anggota', $noAnggota)->first();
                    if (!$anggota) {
                        $errors[] = "Baris $rowIndex: No Anggota tidak ditemukan";
                        continue;
                    }

                    // Find pembiayaan
                    $pembiayaan = PengajuanPembiayaan::where('kode_pengajuan', $kodePembiayaan)->first();
                    if (!$pembiayaan) {
                        $errors[] = "Baris $rowIndex: Kode Pembiayaan tidak ditemukan";
                        continue;
                    }

                    // Validate angsuran exists
                    $angsuran = Angsuran::where('pengajuan_pembiayaan_id', $pembiayaan->id)
                                    ->where('angsuran_ke', $angsuranKe)
                                    ->first();
                    if (!$angsuran) {
                        $errors[] = "Baris $rowIndex: Angsuran ke-$angsuranKe tidak ditemukan";
                        continue;
                    }

                    // Check if already paid
                    if ($angsuran->status === 'terbayar') {
                        $errors[] = "Baris $rowIndex: Angsuran ke-$angsuranKe sudah lunas";
                        continue;
                    }

                    if (!is_numeric($jumlahBayar) || $jumlahBayar <= 0) {
                        $errors[] = "Baris $rowIndex: Jumlah bayar harus angka positif";
                        continue;
                    }

                    $tanggalParsed = $this->parseDate($tanggalBayar);
                    if (!$tanggalParsed) {
                        $errors[] = "Baris $rowIndex: Format tanggal tidak valid";
                        continue;
                    }

                    // Check if pembayaran exceeds total
                    $sisaTagihan = $angsuran->jumlah_angsuran - ($angsuran->jumlah_bayar ?? 0);
                    if ((float)$jumlahBayar > $sisaTagihan) {
                        $errors[] = "Baris $rowIndex: Jumlah bayar melebihi tagihan. Sisa tagihan: Rp " . number_format($sisaTagihan, 0, ',', '.');
                        continue;
                    }

                    // Update angsuran
                    $jumlahBayarSebelumnya = $angsuran->jumlah_bayar ?? 0;
                    $totalBayar = $jumlahBayarSebelumnya + (float)$jumlahBayar;

                    $angsuran->update([
                        'jumlah_bayar' => $totalBayar,
                        'tanggal_bayar' => $tanggalParsed,
                        'status' => $totalBayar >= $angsuran->jumlah_angsuran ? 'terbayar' : 'pending',
                        'keterangan' => ($angsuran->keterangan ?? '') . "\n" . ($row[5] ?? 'Import dari CSV'),
                        'updated_at' => now()
                    ]);

                    $successCount++;
                }

                DB::commit();

                $message = "Berhasil import $successCount pembayaran angsuran";
                if (!empty($errors)) {
                    $message .= ". Error: " . implode('; ', array_slice($errors, 0, 3));
                    if (count($errors) > 3) {
                        $message .= " ... dan " . (count($errors) - 3) . " error lainnya";
                    }
                }

                return back()->with('success', $message);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
