<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Koperasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DataKoperasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $koperasi = Koperasi::latest()->get();
        return view('admin.koperasi.index', compact('koperasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.koperasi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_koperasi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'no_koperasi' => 'required|string|max:50|unique:koperasi,no_koperasi',
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
            $data = $request->except('logo');

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . $logo->getClientOriginalName();
                $logoPath = 'koperasi/logo/' . $logoName;
                $logo->storeAs('public/koperasi/logo', $logoName);
                $data['logo'] = $logoPath;

                // Sync to public/storage for direct access
                \App\Helpers\StorageSyncHelper::syncToPublic($logoPath);
            }

            Koperasi::create($data);

            return redirect()
                ->route('admin.koperasi.index')
                ->with('success', 'Data koperasi berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menambahkan data koperasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $koperasi = Koperasi::findOrFail($id);
        return view('admin.koperasi.show', compact('koperasi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $koperasi = Koperasi::findOrFail($id);
        return view('admin.koperasi.edit', compact('koperasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $koperasi = Koperasi::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_koperasi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'no_koperasi' => 'required|string|max:50|unique:koperasi,no_koperasi,' . $id,
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
            $data = $request->except('logo');

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($koperasi->logo) {
                    \App\Helpers\StorageSyncHelper::deleteFromBoth($koperasi->logo);
                }

                $logo = $request->file('logo');
                $logoName = time() . '_' . $logo->getClientOriginalName();
                $logoPath = 'koperasi/logo/' . $logoName;
                $logo->storeAs('public/koperasi/logo', $logoName);
                $data['logo'] = $logoPath;

                // Sync to public/storage for direct access
                \App\Helpers\StorageSyncHelper::syncToPublic($logoPath);
            }

            $koperasi->update($data);

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $koperasi = Koperasi::findOrFail($id);

        try {
            // Delete logo if exists
            if ($koperasi->logo) {
                \App\Helpers\StorageSyncHelper::deleteFromBoth($koperasi->logo);
            }

            $koperasi->delete();

            return redirect()
                ->route('admin.koperasi.index')
                ->with('success', 'Data koperasi berhasil dihapus');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus data koperasi: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status koperasi
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        $koperasi = Koperasi::findOrFail($id);

        try {
            $koperasi->update([
                'status' => $koperasi->status == 'aktif' ? 'tidak_aktif' : 'aktif'
            ]);

            $status = $koperasi->status == 'aktif' ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()
                ->route('admin.koperasi.index')
                ->with('success', "Data koperasi berhasil {$status}");
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal mengubah status koperasi: ' . $e->getMessage());
        }
    }
}
