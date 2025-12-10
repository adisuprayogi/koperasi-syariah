<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KartuAnggotaSetting;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\PDF;

class KartuAnggotaController extends Controller
{
    public function index()
    {
        return view('admin.kartu.index');
    }

    public function create()
    {
        return view('admin.kartu.create');
    }

    public function settings()
    {
        $settings = KartuAnggotaSetting::getSettings();
        return view('admin.kartu.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'nama_koperasi' => 'required|string|max:255',
            'alamat_koperasi' => 'nullable|string',
            'telepon_koperasi' => 'nullable|string|max:20',
            'email_koperasi' => 'nullable|email',
            'website_koperasi' => 'nullable|string',
            'nama_ketua' => 'required|string|max:255',
            'jabatan_ketua' => 'required|string|max:255',
            'syarat_ketentuan' => 'nullable|string',
            'custom_text_back' => 'nullable|string',
            'custom_text_front' => 'nullable|string',
            'font_color_front' => 'nullable|string',
            'font_color_back' => 'nullable|string',
        ]);

        try {
            $settings = KartuAnggotaSetting::first() ?: new KartuAnggotaSetting();

            $settings->nama_koperasi = $request->nama_koperasi;
            $settings->alamat_koperasi = $request->alamat_koperasi;
            $settings->telepon_koperasi = $request->telepon_koperasi;
            $settings->email_koperasi = $request->email_koperasi;
            $settings->website_koperasi = $request->website_koperasi;
            $settings->nama_ketua = $request->nama_ketua;
            $settings->jabatan_ketua = $request->jabatan_ketua;

            // Front card settings
            $settings->background_front = $request->background_front ?? 'gradient-blue';
            $settings->primary_color_front = $request->primary_color_front ?? '#1e40af';
            $settings->secondary_color_front = $request->secondary_color_front ?? '#3b82f6';
            $settings->text_color_front = $request->text_color_front ?? '#ffffff';
            $settings->show_logo_front = $request->boolean('show_logo_front', true);
            $settings->show_nomor_anggota_front = $request->boolean('show_nomor_anggota_front', true);
            $settings->show_nama_anggota_front = $request->boolean('show_nama_anggota_front', true);
            $settings->show_foto_anggota_front = $request->boolean('show_foto_anggota_front', true);
            $settings->show_tanggal_masuk_front = $request->boolean('show_tanggal_masuk_front', false);
            $settings->show_barcode_front = $request->boolean('show_barcode_front', true);
            $settings->show_custom_text_front = $request->boolean('show_custom_text_front', true);
            $settings->custom_text_front = $request->custom_text_front;

            // Back card settings
            $settings->background_back = $request->background_back ?? 'gradient-blue';
            $settings->primary_color_back = $request->primary_color_back ?? '#1e40af';
            $settings->secondary_color_back = $request->secondary_color_back ?? '#3b82f6';
            $settings->text_color_back = $request->text_color_back ?? '#ffffff';
            $settings->show_nama_ketua_back = $request->boolean('show_nama_ketua_back', true);
            $settings->show_tanda_tangan_back = $request->boolean('show_tanda_tangan_back', true);
            $settings->show_syarat_ketentuan_back = $request->boolean('show_syarat_ketentuan_back', true);
            $settings->syarat_ketentuan = $request->syarat_ketentuan;
            $settings->custom_text_back = $request->custom_text_back;

            // Font color settings
            $settings->font_color_front = $request->font_color_front ?? '#ffffff';
            $settings->font_color_back = $request->font_color_back ?? '#ffffff';

            // Save settings
            $settings->save();

            return redirect()->route('admin.kartu.settings')
                ->with('success', 'Pengaturan kartu anggota berhasil diperbarui');

        } catch (\Exception $e) {
            \Log::error('Card settings update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengaturan kartu')
                ->withInput();
        }
    }

    public function preview($id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $settings = KartuAnggotaSetting::getSettings();

            return view('admin.kartu.preview', compact('anggota', 'settings'));

        } catch (\Exception $e) {
            \Log::error('Card preview error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Anggota tidak ditemukan');
        }
    }

    public function generateHTML($id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $settings = KartuAnggotaSetting::getSettings();

            return view('admin.kartu.html', compact('anggota', 'settings'));

        } catch (\Exception $e) {
            \Log::error('HTML generation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate HTML kartu anggota');
        }
    }

    public function anggotaList()
    {
        $anggotas = Anggota::where('status_keanggotaan', 'aktif')
                        ->orderBy('nama_lengkap')
                        ->get();

        return view('admin.kartu.list', compact('anggotas'));
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'koperasi_logo.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kartu', $filename, 'public');

                // Save logo path to settings
                $settings = KartuAnggotaSetting::first() ?: new KartuAnggotaSetting();
                $settings->logo_path = $path;
                $settings->save();
            }

            return response()->json(['success' => true, 'logo_url' => asset('storage/' . $path)]);

        } catch (\Exception $e) {
            \Log::error('Logo upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal upload logo'], 500);
        }
    }

    public function uploadSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('signature')) {
                $file = $request->file('signature');
                $filename = 'ketua_signature.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kartu', $filename, 'public');

                // Save signature path to settings
                $settings = KartuAnggotaSetting::first() ?: new KartuAnggotaSetting();
                $settings->signature_path = $path;
                $settings->save();
            }

            return response()->json(['success' => true, 'signature_url' => asset('storage/' . $path)]);

        } catch (\Exception $e) {
            \Log::error('Signature upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal upload tanda tangan'], 500);
        }
    }

    public function uploadBackgroundFront(Request $request)
    {
        $request->validate([
            'background_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('background_front')) {
                $file = $request->file('background_front');
                $filename = 'background_front.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kartu', $filename, 'public');

                // Save background path to settings
                $settings = KartuAnggotaSetting::first() ?: new KartuAnggotaSetting();
                $settings->background_image_front = $path;
                $settings->save();
            }

            return response()->json(['success' => true, 'background_url' => asset('storage/' . $path)]);

        } catch (\Exception $e) {
            \Log::error('Background front upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal upload background depan'], 500);
        }
    }

    public function uploadBackgroundBack(Request $request)
    {
        $request->validate([
            'background_back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('background_back')) {
                $file = $request->file('background_back');
                $filename = 'background_back.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kartu', $filename, 'public');

                // Save background path to settings
                $settings = KartuAnggotaSetting::first() ?: new KartuAnggotaSetting();
                $settings->background_image_back = $path;
                $settings->save();
            }

            return response()->json(['success' => true, 'background_url' => asset('storage/' . $path)]);

        } catch (\Exception $e) {
            \Log::error('Background back upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal upload background belakang'], 500);
        }
    }

    public function downloadPDF($id)
    {
        try {
            $anggota = Anggota::findOrFail($id);
            $settings = KartuAnggotaSetting::getSettings();

            // Set longer execution time and memory limit for PDF generation with base64 images
            set_time_limit(300);
            ini_set('memory_limit', '512M');

            // Generate PDF using DomPDF
            $pdf = PDF::loadView('admin.kartu.pdf', compact('anggota', 'settings'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions(['defaultFont' => 'Arial']);

            return $pdf->download('kartu-anggota-' . str_replace(' ', '-', strtolower($anggota->nama_lengkap)) . '.pdf');

        } catch (\Exception $e) {
            \Log::error('PDF download error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal download kartu anggota: ' . $e->getMessage());
        }
    }
}