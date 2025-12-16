@extends('layouts.app')

@section('title', 'Import Data Anggota Lengkap')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-excel"></i> Import Data Anggota
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('pengurus.anggota.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Panduan Lengkap Import Data Anggota</h5>
                        <ol>
                            <li><strong>Download Template Excel</strong> - Gunakan format yang sudah disediakan</li>
                            <li><strong>Isi Data Anggota</strong> - Lengkapi semua kolom yang diperlukan</li>
                            <li><strong>Nomor Anggota</strong> - Kosongkan untuk auto-generate atau isi manual</li>
                            <li><strong>Upload File</strong> - Pilih file Excel yang sudah diisi</li>
                            <li><strong>Review Hasil</strong> - Cek data yang berhasil dan gagal diimport</li>
                        </ol>
                    </div>

                    <!-- Nomor Anggota Guide -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-primary">
                                <div class="card-body text-white">
                                    <h5><i class="fas fa-id-card"></i> Panduan Nomor Anggota</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>🔄 Auto-Generate (Rekomendasi)</h6>
                                            <ul>
                                                <li>Kosongkan kolom <strong>K (no_anggota)</strong></li>
                                                <li>System akan generate otomatis: <code>20250001, 20250002, dst</code></li>
                                                <li>Format: <strong>YYYY + 4 digit</strong></li>
                                                <li>Contoh: <code>20250001</code> (anggota pertama tahun 2025)</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>✏️ Manual Input</h6>
                                            <ul>
                                                <li>Isi sendiri nomor anggota di kolom K</li>
                                                <li>Bisa gunakan format kustom</li>
                                                <li>Contoh: <code>A001, B002, ANG-001</code></li>
                                                <li>Harus <strong>UNIQUE</strong> - tidak boleh duplikat</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Template -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-success">
                                <div class="card-body text-center">
                                    <h5>📥 Template Excel Lengkap</h5>
                                    <p class="mb-2">Template dengan 11 kolom termasuk nomor anggota</p>
                                    <a href="{{ route('pengurus.anggota.import.template') }}"
                                       class="btn btn-light">
                                        <i class="fas fa-download"></i> Download Template Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-upload"></i> Upload File Excel</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('pengurus.anggota.import.store') }}"
                                          method="POST"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">
                                            <label for="excel_file">
                                                File Excel <span class="text-danger">*</span>
                                            </label>
                                            <input type="file"
                                                   class="form-control @error('excel_file') is-invalid @enderror"
                                                   id="excel_file"
                                                   name="excel_file"
                                                   accept=".xlsx,.xls"
                                                   required>
                                            @error('excel_file')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Format file: .xlsx atau .xls (Maksimal 10MB)
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-file-import"></i> Import Data
                                            </button>
                                            <a href="{{ route('pengurus.anggota.index') }}"
                                               class="btn btn-secondary ml-2">
                                                <i class="fas fa-times"></i> Batal
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Format Excel Table -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-table"></i> Format Excel Lengkap</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Kolom</th>
                                                    <th>Nama Field</th>
                                                    <th>Tipe</th>
                                                    <th>Wajib</th>
                                                    <th>Contoh</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="table-warning">
                                                    <td>A</td>
                                                    <td>nama_lengkap</td>
                                                    <td>Text</td>
                                                    <td><span class="badge badge-danger">Ya</span></td>
                                                    <td>Ahmad Rizki</td>
                                                    <td>Nama lengkap anggota</td>
                                                </tr>
                                                <tr>
                                                    <td>B</td>
                                                    <td>jenis_kelamin</td>
                                                    <td>Pilihan</td>
                                                    <td><span class="badge badge-success">Tidak</span></td>
                                                    <td>L atau P</td>
                                                    <td>L = Laki-laki, P = Perempuan</td>
                                                </tr>
                                                <tr>
                                                    <td>C</td>
                                                    <td>tempat_lahir</td>
                                                    <td>Text</td>
                                                    <td><span class="badge badge-success">Tidak</span></td>
                                                    <td>Jakarta</td>
                                                    <td>Tempat lahir</td>
                                                </tr>
                                                <tr>
                                                    <td>D</td>
                                                    <td>tanggal_lahir</td>
                                                    <td>Date</td>
                                                    <td><span class="badge badge-success">Tidak</span></td>
                                                    <td>1990-01-15</td>
                                                    <td>Format YYYY-MM-DD</td>
                                                </tr>
                                                <tr>
                                                    <td>E</td>
                                                    <td>alamat_lengkap</td>
                                                    <td>Text</td>
                                                    <td><span class="badge badge-success">Tidak</span></td>
                                                    <td>Jl. Merdeka No. 123</td>
                                                    <td>Alamat lengkap</td>
                                                </tr>
                                                <tr>
                                                    <td>F</td>
                                                    <td>no_hp</td>
                                                    <td>Text</td>
                                                    <td><span class="badge badge-success">Tidak</span></td>
                                                    <td>08123456789</td>
                                                    <td>Nomor HP/WA</td>
                                                </tr>
                                                <tr>
                                                    <td>G</td>
                                                    <td>pekerjaan</td>
                                                    <td>Text</td>
                                                    <td><span class="badge badge-success">Tidak</span></td>
                                                    <td>Pegawai Swasta</td>
                                                    <td>Pekerjaan</td>
                                                </tr>
                                                <tr>
                                                    <td>H</td>
                                                    <td>penghasilan</td>
                                                    <td>Number</td>
                                                    <td><span class="badge badge-success">Tidak</span></td>
                                                    <td>5000000</td>
                                                    <td>Dalam Rupiah (angka saja)</td>
                                                </tr>
                                                <tr>
                                                    <td>I</td>
                                                    <td>email</td>
                                                    <td>Email</td>
                                                    <td><span class="badge badge-danger">Ya</span></td>
                                                    <td>email@example.com</td>
                                                    <td>Email login (harus unique)</td>
                                                </tr>
                                                <tr>
                                                    <td>J</td>
                                                    <td>password</td>
                                                    <td>Text</td>
                                                    <td><span class="badge badge-danger">Ya</span></td>
                                                    <td>password123</td>
                                                    <td>Password login</td>
                                                </tr>
                                                <tr class="table-info">
                                                    <td>K</td>
                                                    <td>no_anggota</td>
                                                    <td>Text</td>
                                                    <td><span class="badge badge-warning">Auto</span></td>
                                                    <td><em>Kosongkan</em> atau 20250001</td>
                                                    <td>Kosongkan untuk auto-generate</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Contoh Data -->
                                    <h6 class="mt-4">📝 Contoh Data Excel:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>A</th><th>B</th><th>C</th><th>D</th><th>E</th><th>F</th><th>G</th><th>H</th><th>I</th><th>J</th><th>K</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Ahmad Rizki</td>
                                                    <td>L</td>
                                                    <td>Jakarta</td>
                                                    <td>1990-01-15</td>
                                                    <td>Jl. Merdeka 123</td>
                                                    <td>08123456789</td>
                                                    <td>Pegawai</td>
                                                    <td>5000000</td>
                                                    <td>ahmad@email.com</td>
                                                    <td>pass123</td>
                                                    <td class="text-muted font-italic">[kosongkan]</td>
                                                </tr>
                                                <tr>
                                                    <td>Siti Nurhaliza</td>
                                                    <td>P</td>
                                                    <td>Surabaya</td>
                                                    <td>1992-05-20</td>
                                                    <td>Jl. Sudirman 456</td>
                                                    <td>08234567890</td>
                                                    <td>Wiraswasta</td>
                                                    <td>7500000</td>
                                                    <td>siti@email.com</td>
                                                    <td>pass456</td>
                                                    <td class="text-muted font-italic">[kosongkan]</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Report -->
                    @if(session('error_count') > 0 && session('errors'))
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-exclamation-triangle"></i>
                                        {{ session('error_count') }} data gagal diimport
                                    </h5>
                                    <p>Data gagal diimport karena tidak sesuai format atau ada duplikasi data.</p>
                                    <a href="{{ route('pengurus.anggota.import.error-report') }}"
                                       class="btn btn-warning">
                                        <i class="fas fa-download"></i> Download Laporan Error
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection