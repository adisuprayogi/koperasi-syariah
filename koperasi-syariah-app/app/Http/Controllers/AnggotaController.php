<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Dashboard Anggota
     */
    public function dashboard()
    {
        return view('anggota.dashboard');
    }

    /**
     * Profile
     */
    public function profile()
    {
        return view('anggota.profile');
    }

    /**
     * Update Profile
     */
    public function profileUpdate(Request $request)
    {
        // TODO: Implement update profile logic
        return redirect()->route('anggota.profile')->with('success', 'Profil berhasil diupdate');
    }

    /**
     * Index Simpanan
     */
    public function simpananIndex()
    {
        return view('anggota.simpanan.index');
    }

    /**
     * Show Simpanan
     */
    public function simpananShow($id)
    {
        return view('anggota.simpanan.show', compact('id'));
    }

    /**
     * Index Pengajuan
     */
    public function pengajuanIndex()
    {
        return view('anggota.pengajuan.index');
    }

    /**
     * Create Pengajuan
     */
    public function pengajuanCreate()
    {
        return view('anggota.pengajuan.create');
    }

    /**
     * Store Pengajuan
     */
    public function pengajuanStore(Request $request)
    {
        // TODO: Implement store logic
        return redirect()->route('anggota.pengajuan.index')->with('success', 'Pengajuan berhasil dikirim');
    }

    /**
     * Show Pengajuan
     */
    public function pengajuanShow($id)
    {
        return view('anggota.pengajuan.show', compact('id'));
    }

    /**
     * Index Pembiayaan
     */
    public function pembiayaanIndex()
    {
        return view('anggota.pembiayaan.index');
    }

    /**
     * Show Pembiayaan
     */
    public function pembiayaanShow($id)
    {
        return view('anggota.pembiayaan.show', compact('id'));
    }
}
