<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Anggota;
use App\Services\ExcelDateParser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnggotaImportErrorExport;
use Maatwebsite\Excel\Concerns\ToArray;

class AnggotaImportController extends Controller
{
    /**
     * Display anggota list
     */
    public function index()
    {
        $anggota = Anggota::with('user')->latest()->paginate(10);
        return view('admin.anggota.index', compact('anggota'));
    }

    /**
     * Display import form
     */
    public function create()
    {
        return view('admin.anggota.import-tailwind');
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\AnggotaTemplateExport, 'template_import_anggota.xlsx');
    }

    /**
     * Process Excel import
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls|max:10240' // Max 10MB
        ]);

        try {
            $import = new class implements ToArray {
                public $data = [];
                public $errors = [];
                public $successCount = 0;
                public $errorCount = 0;

                public function array(array $array)
                {
                    $this->data = $array;
                }
            };

            Excel::import($import, $request->file('excel_file'));

            $data = $import->data;
            $errors = [];
            $successCount = 0;
            $errorCount = 0;

            // Skip header row
            $dataRows = array_slice($data, 1);

            DB::beginTransaction();

            try {
                foreach ($dataRows as $index => $row) {
                    $rowIndex = $index + 2; // +2 because Excel starts at 1 and we skip header

                    // Validasi required fields (dengan fallback untuk row yang mungkin kosong)
                    $nama = trim($row[0] ?? '');
                    $email = trim($row[8] ?? '');
                    $password = trim($row[9] ?? '');
                    $nik = trim($row[11] ?? '');
                    $tanggalGabung = trim($row[14] ?? '');

                    if (empty($nama) || empty($email) || empty($password) || empty($nik) || empty($tanggalGabung)) {
                        $errors[] = [
                            'row' => $rowIndex,
                            'error' => 'Nama lengkap, email, password, NIK, dan tanggal gabung wajib diisi. Data: ' . json_encode($row),
                            'data' => $row
                        ];
                        $errorCount++;
                        continue;
                    }

                    // Validasi format
                    $errors_in_row = [];

                    $jenisKelamin = $row[1] ?? 'L';
                    $tanggalLahir = $row[3] ?? '';
                    $statusKeanggotaan = $row[13] ?? 'aktif';
                    $tanggalKeluar = $row[15] ?? '';
                    $alasanKeluar = $row[16] ?? '';
                    $penghasilan = $row[7] ?? '0';
                    $noAnggota = $row[10] ?? '';
                    $noNpwp = $row[12] ?? '';

                    if (!in_array($jenisKelamin, ['L', 'P'])) {
                        $errors_in_row[] = 'Jenis kelamin harus L atau P';
                    }

                    // Parse tanggal dengan ExcelDateParser
                    $tanggalLahirParsed = ExcelDateParser::parseDate($tanggalLahir);
                    $tanggalGabungParsed = ExcelDateParser::parseDate($tanggalGabung);
                    $tanggalKeluarParsed = ExcelDateParser::parseDate($tanggalKeluar);

                    // Validasi tanggal lahir
                    if (!empty($tanggalLahir) && !$tanggalLahirParsed) {
                        $errors_in_row[] = 'Format tanggal lahir tidak valid (gunakan YYYY-MM-DD atau format Excel)';
                    }

                    // Validasi tanggal gabung
                    if (!$tanggalGabungParsed) {
                        $errors_in_row[] = 'Format tanggal gabung tidak valid (gunakan YYYY-MM-DD atau format Excel)';
                    }

                    // Validasi status keanggotaan
                    if (!in_array($statusKeanggotaan, ['aktif', 'tidak_aktif', 'keluar'])) {
                        $errors_in_row[] = 'Status keanggotaan harus: aktif, tidak_aktif, atau keluar';
                    }

                    // Validasi tanggal keluar (harus ada jika status keluar)
                    if ($statusKeanggotaan === 'keluar') {
                        if (empty($tanggalKeluar)) {
                            $errors_in_row[] = 'Tanggal keluar wajib diisi jika status keluar';
                        } elseif (!$tanggalKeluarParsed) {
                            $errors_in_row[] = 'Format tanggal keluar tidak valid (gunakan YYYY-MM-DD atau format Excel)';
                        }

                        // Validasi alasan keluar (harus ada jika status keluar)
                        if (empty($alasanKeluar)) {
                            $errors_in_row[] = 'Alasan keluar wajib diisi jika status keluar';
                        }
                    }

                    // Validasi penghasilan
                    if (!empty($penghasilan) && !is_numeric($penghasilan)) {
                        $errors_in_row[] = 'Penghasilan harus angka';
                    }

                    // Validasi email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors_in_row[] = 'Format email tidak valid';
                    }

                    // Cek email sudah ada
                    if (User::where('email', $email)->exists()) {
                        $errors_in_row[] = 'Email sudah terdaftar';
                    }

                    // Validasi format nomor anggota (jika diisi manual)
                    if (!empty($noAnggota)) {
                        // Validasi format manual: YYMM.00001
                        if (!preg_match('/^\d{4}\.\d{5}$/', $noAnggota)) {
                            $errors_in_row[] = 'Format nomor anggota manual harus YYMM.00001 (contoh: 2512.00001)';
                        } else {
                            // Validasi YY (tahun) harus valid
                            $yy = substr($noAnggota, 0, 2);
                            $current_yy = date('y');
                            if ($yy < $current_yy - 5 || $yy > $current_yy + 5) {
                                $errors_in_row[] = 'Tahun pada nomor anggota tidak valid (YY harus sekitar tahun sekarang)';
                            }

                            // Cek nomor anggota sudah ada
                            if (Anggota::where('no_anggota', $noAnggota)->exists()) {
                                $errors_in_row[] = 'Nomor anggota sudah terdaftar';
                            }
                        }
                    }

                    // Cek NIK sudah ada
                    if (Anggota::where('nik', $nik)->exists()) {
                        $errors_in_row[] = 'NIK sudah terdaftar';
                    }

                    // Validasi format NIK (16 digit)
                    if (!is_numeric($nik) || strlen($nik) != 16) {
                        $errors_in_row[] = 'NIK harus 16 digit angka';
                    }

                    if (!empty($errors_in_row)) {
                        $errors[] = [
                            'row' => $rowIndex,
                            'error' => implode(', ', $errors_in_row),
                            'data' => $row
                        ];
                        $errorCount++;
                        continue;
                    }

                    // Generate nomor anggota (manual atau auto) terlebih dahulu
                    if (empty($noAnggota)) {
                        $finalNoAnggota = Anggota::generateNoAnggota(); // Auto-generate
                    } else {
                        $finalNoAnggota = $noAnggota; // Gunakan manual input (sudah divalidasi)
                    }

                    // Username sama dengan nomor anggota (tanpa cleaning)
                    $username = $finalNoAnggota;

                    // Create user dengan username sama dengan nomor anggota
                    $user = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'username' => $username,
                        'password' => Hash::make($password),
                        'role' => 'anggota',
                        'email_verified_at' => now(),
                        'first_login' => true // Flag untuk first login
                    ]);

                    // Jika status keluar, soft delete user account
                    if ($statusKeanggotaan === 'keluar') {
                        $user->delete();
                    }

                    // Create anggota
                    $anggotaData = [
                        'user_id' => $user->id,
                        'no_anggota' => $finalNoAnggota,
                        'nama_lengkap' => $nama,
                        'nik' => $nik,
                        'jenis_kelamin' => $jenisKelamin,
                        'tempat_lahir' => $row[2] ?? '',
                        'tanggal_lahir' => $tanggalLahirParsed,
                        'alamat_lengkap' => $row[4] ?? '',
                        'no_hp' => $row[5] ?? '',
                        'email' => $email,
                        'pekerjaan' => $row[6] ?? '',
                        'penghasilan' => !empty($penghasilan) ? (string)$penghasilan : '0',
                        'no_npwp' => $noNpwp,
                        'status_keanggotaan' => $statusKeanggotaan,
                        'jenis_anggota' => 'biasa',
                        'tanggal_gabung' => $tanggalGabungParsed,
                        'tanggal_keluar' => ($statusKeanggotaan === 'keluar') ? $tanggalKeluarParsed : null,
                        'alasan_keluar' => ($statusKeanggotaan === 'keluar' && !empty($alasanKeluar)) ? $alasanKeluar : null
                    ];

                    Anggota::create($anggotaData);
                    $successCount++;
                }

                DB::commit();

                // Return to view with results
                return redirect()->route('pengurus.anggota.import')
                    ->with('success', "Berhasil mengimport $successCount data anggota")
                    ->with('error_count', $errorCount)
                    ->with('import_errors', $errors);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download error report
     */
    public function downloadErrorReport(Request $request)
    {
        $errors = session('import_errors', []);

        if (empty($errors)) {
            return redirect()->back()->with('error', 'Tidak ada error untuk didownload');
        }

        $filename = 'error_report_import_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new AnggotaImportErrorExport($errors), $filename);
    }
}