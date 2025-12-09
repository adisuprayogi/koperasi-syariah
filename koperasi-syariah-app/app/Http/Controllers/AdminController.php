<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengurus;
use App\Models\Koperasi;
use App\Models\JenisSimpanan;
use App\Models\JenisPembiayaan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
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
        $pengurus = Pengurus::with('user')->latest()->get();
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
        $jenisSimpanan = JenisSimpanan::latest()->get();
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
        $jenisPembiayaan = JenisPembiayaan::latest()->get();
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
            'tipe_pembiayaan' => 'required|in:murabahah,mudharabah,musyarakah,qardh',
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
            'tipe_pembiayaan' => 'required|in:murabahah,mudharabah,musyarakah,qardh',
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
}
