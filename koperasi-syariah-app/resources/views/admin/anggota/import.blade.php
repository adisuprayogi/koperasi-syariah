@extends('layouts.app')

@section('title', 'Import Data Anggota')

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
                        <h5><i class="fas fa-info-circle"></i> Panduan Import Data Anggota</h5>
                        <ol>
                            <li>Download template Excel yang sudah disediakan</li>
                            <li>Isi data anggota sesuai format kolom</li>
                            <li>Kolom bertanda <span class="text-danger">*</span> wajib diisi</li>
                            <li>Upload file Excel yang sudah diisi</li>
                        </ol>
                    </div>

                    <!-- Download Template -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5>📥 Template Excel</h5>
                                    <p class="mb-2">Download template Excel untuk format yang benar</p>
                                    <a href="{{ route('pengurus.anggota.import.template') }}"
                                       class="btn btn-success">
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

                    <!-- Format Excel Guide -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-table"></i> Format Excel</h5>
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
                                                <tr>
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
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Report (if any) -->
                    @if(session('error_count') > 0 && session('errors'))
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-exclamation-triangle"></i>
                                        Ada {{ session('error_count') }} data gagal diimport
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