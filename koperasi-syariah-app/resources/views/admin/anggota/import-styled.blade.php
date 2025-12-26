@extends('layouts.app')

@section('title', 'Import Data Anggota')

@section('styles')
<style>
/* Custom Styles for Import Page */
.import-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.step-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.step-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
}

.feature-box {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 1rem;
}

.info-box {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 15px;
    border-left: 5px solid rgba(255,255,255,0.3);
}

.table-responsive {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.table-custom {
    margin-bottom: 0;
}

.table-custom thead th {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    padding: 1rem;
}

.table-custom tbody tr:nth-child(even) {
    background-color: #f8f9fc;
}

.table-custom td {
    vertical-align: middle;
    padding: 0.75rem;
    border-color: #e3e6f0;
}

.upload-area {
    border: 2px dashed #e3e6f0;
    border-radius: 15px;
    padding: 3rem;
    text-align: center;
    background: #f8f9fc;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #4e73df;
    background: #fff;
}

.upload-icon {
    font-size: 3rem;
    color: #4e73df;
    margin-bottom: 1rem;
}

.template-btn {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(17, 153, 142, 0.3);
}

.template-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(17, 153, 142, 0.4);
}

.import-btn {
    background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(252, 74, 26, 0.3);
}

.import-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(252, 74, 26, 0.4);
}

.cancel-btn {
    background: #e3e6f0;
    color: #5a5c69;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.cancel-btn:hover {
    background: #d1d3e2;
    color: #3a3d48;
}

.stat-card {
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.stat-card.primary {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
}

.stat-card.success {
    background: linear-gradient(135deg, #1cc88a 0%, #28a745 100%);
    color: white;
}

.stat-card.warning {
    background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
    color: white;
}

.badge-custom {
    font-size: 0.75rem;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-weight: 600;
    text-transform: uppercase;
}

.example-data {
    background: #f8f9fc;
    border-radius: 10px;
    padding: 1rem;
    border-left: 4px solid #4e73df;
}

.guide-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.floating-label {
    position: absolute;
    top: -10px;
    left: 20px;
    background: white;
    padding: 0 10px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #4e73df;
    border-radius: 5px;
    z-index: 1;
}

.form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    border-radius: 10px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

@media (max-width: 768px) {
    .import-header {
        padding: 1.5rem;
    }

    .step-number {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }

    .template-btn, .import-btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.85rem;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="import-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">
                    <i class="fas fa-file-excel me-3"></i>
                    Import Data Anggota Massal
                </h1>
                <p class="lead mb-0 opacity-90">
                    Import data anggota dalam jumlah besar dengan format Excel yang sudah disediakan
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('pengurus.anggota.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="fas fa-check-circle me-2"></i>
                Import Berhasil!
            </h5>
            <p class="mb-0">{{ session('success') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-circle me-2"></i>
                Terjadi Error!
            </h5>
            <p class="mb-0">{{ session('error') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Steps Overview -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card step-card">
                <div class="card-body text-center">
                    <div class="step-number mx-auto mb-3">1</div>
                    <h6 class="card-title">Download Template</h6>
                    <p class="card-text text-muted small">Unduh format Excel yang sudah disediakan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card step-card">
                <div class="card-body text-center">
                    <div class="step-number mx-auto mb-3">2</div>
                    <h6 class="card-title">Isi Data</h6>
                    <p class="card-text text-muted small">Lengkapi data anggota sesuai format</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card step-card">
                <div class="card-body text-center">
                    <div class="step-number mx-auto mb-3">3</div>
                    <h6 class="card-title">Upload File</h6>
                    <p class="card-text text-muted small">Upload file Excel yang sudah diisi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card step-card">
                <div class="card-body text-center">
                    <div class="step-number mx-auto mb-3">4</div>
                    <h6 class="card-title">Review Hasil</h6>
                    <p class="card-text text-muted small">Cek data yang berhasil diimport</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Nomor Anggota Guide -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="feature-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-3">
                            <i class="fas fa-id-card me-2"></i>
                            Panduan Nomor Anggota
                        </h4>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-white">
                                    <i class="fas fa-sync-alt me-2"></i>
                                    Auto-Generate (Rekomendasi)
                                </h6>
                                <ul class="text-white mb-0">
                                    <li><strong>Kosongkan</strong> kolom K (no_anggota)</li>
                                    <li><strong>Format</strong>: YYYY + 4 digit</li>
                                    <li><strong>Contoh</strong>: 20250001, 20250002</li>
                                    <li><strong>Keuntungan</strong>: Konsisten & otomatis</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-white">
                                    <i class="fas fa-edit me-2"></i>
                                    Manual Input (Opsional)
                                </h6>
                                <ul class="text-white mb-0">
                                    <li><strong>Isi sendiri</strong> nomor di kolom K</li>
                                    <li><strong>Format</strong>: Bebas (A001, ANG-001)</li>
                                    <li><strong>Syarat</strong>: Harus UNIQUE</li>
                                    <li><strong>Keuntungan</strong>: Format kustom</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="guide-icon mx-auto">
                            <i class="fas fa-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Download Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-body text-center">
                    <div class="upload-icon mb-3">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <h5>Download Template Excel</h5>
                    <p class="text-muted mb-4">Template dengan 11 kolom lengkap</p>
                    <a href="{{ route('pengurus.anggota.import.template') }}" class="btn template-btn">
                        <i class="fas fa-download me-2"></i>
                        Download Template
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">
                        <i class="fas fa-chart-line me-2 text-primary"></i>
                        Statistik Import
                    </h5>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-card primary">
                                <h3>0</h3>
                                <small>Total Upload</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-card success">
                                <h3>0</h3>
                                <small>Berhasil</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-card warning">
                                <h3>0</h3>
                                <small>Gagal</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-cloud-upload-alt me-2"></i>
                        Upload File Excel
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pengurus.anggota.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="upload-area mb-4">
                                    <div class="upload-icon">
                                        <i class="fas fa-file-upload"></i>
                                    </div>
                                    <h5>Pilih File Excel</h5>
                                    <p class="text-muted mb-3">Format: .xlsx atau .xls (Maksimal 10MB)</p>
                                    <div class="position-relative">
                                        <span class="floating-label">Pilih File</span>
                                        <input type="file"
                                               class="form-control form-control-lg @error('excel_file') is-invalid @enderror"
                                               id="excel_file"
                                               name="excel_file"
                                               accept=".xlsx,.xls"
                                               required>
                                        @error('excel_file')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn import-btn me-3">
                                        <i class="fas fa-file-import me-2"></i>
                                        Import Data
                                    </button>
                                    <a href="{{ route('pengurus.anggota.index') }}" class="btn cancel-btn">
                                        <i class="fas fa-times me-2"></i>
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Format Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-table me-2"></i>
                        Format Excel Lengkap
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom">
                            <thead>
                                <tr>
                                    <th class="text-center">Kolom</th>
                                    <th>Field Name</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Required</th>
                                    <th>Example</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-warning">
                                    <td class="text-center fw-bold">A</td>
                                    <td><code>nama_lengkap</code></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge badge-danger">Required</span>
                                    </td>
                                    <td>Ahmad Rizki</td>
                                    <td>Nama lengkap anggota</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">B</td>
                                    <td><code>jenis_kelamin</code></td>
                                    <td class="text-center">Option</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">Optional</span>
                                    </td>
                                    <td><code>L</code> atau <code>P</code></td>
                                    <td>L = Laki-laki, P = Perempuan</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">C</td>
                                    <td><code>tempat_lahir</code></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">Optional</span>
                                    </td>
                                    <td>Jakarta</td>
                                    <td>Tempat lahir</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">D</td>
                                    <td><code>tanggal_lahir</code></td>
                                    <td class="text-center">Date</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">Optional</span>
                                    </td>
                                    <td><code>1990-01-15</code></td>
                                    <td>Format: YYYY-MM-DD</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">E</td>
                                    <td><code>alamat_lengkap</code></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">Optional</span>
                                    </td>
                                    <td>Jl. Merdeka No. 123</td>
                                    <td>Alamat lengkap</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">F</td>
                                    <td><code>no_hp</code></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">Optional</span>
                                    </td>
                                    <td><code>08123456789</code></td>
                                    <td>Nomor HP/WA</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">G</td>
                                    <td><code>pekerjaan</code></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">Optional</span>
                                    </td>
                                    <td>Pegawai Swasta</td>
                                    <td>Pekerjaan</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">H</td>
                                    <td><code>penghasilan</code></td>
                                    <td class="text-center">Number</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">Optional</span>
                                    </td>
                                    <td><code>5000000</code></td>
                                    <td>Dalam Rupiah</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">I</td>
                                    <td><code>email</code></td>
                                    <td class="text-center">Email</td>
                                    <td class="text-center">
                                        <span class="badge badge-danger">Required</span>
                                    </td>
                                    <td>user@example.com</td>
                                    <td>Unique email for login</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">J</td>
                                    <td><code>password</code></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge badge-danger">Required</span>
                                    </td>
                                    <td><code>password123</code></td>
                                    <td>Login password</td>
                                </tr>
                                <tr class="table-info">
                                    <td class="text-center fw-bold">K</td>
                                    <td><code>no_anggota</code></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge badge-warning">Auto</span>
                                    </td>
                                    <td><em>Kosongkan</em></td>
                                    <td>Auto-generate nomor</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Example Data -->
                    <div class="example-data mt-4">
                        <h6 class="mb-3">
                            <i class="fas fa-lightbulb me-2"></i>
                            Contoh Data Excel
                        </h6>
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
    </div>

    <!-- Tips Section -->
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Tips & Trik
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Selalu gunakan template yang disediakan
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Kosongkan kolom K untuk auto-generate nomor
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Pastikan email unik untuk setiap anggota
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Format tanggal: YYYY-MM-DD
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Penghasilan dalam angka (tanpa titik)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        Yang Perlu Diperhatikan
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-times text-danger me-2"></i>
                            Email harus <strong>uniques</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times text-danger me-2"></i>
                            Nomor anggota harus <strong>unik</strong> (jika manual)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times text-danger me-2"></i>
                            Maksimal file size: 10MB
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-times text-danger me-2"></i>
                            Format file: <strong>.xlsx</strong> atau <strong>.xls</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Report (if any) -->
    @if(session('error_count') > 0 && session('errors'))
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="card-title text-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ session('error_count') }} Data Gagal Diimport
                                </h5>
                                <p class="card-text">
                                    Data gagal diimport karena tidak sesuai format atau ada duplikasi data.
                                    Download laporan error untuk melihat detail kesalahan.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('pengurus.anggota.import.error-report') }}" class="btn btn-warning">
                                    <i class="fas fa-download me-2"></i>
                                    Download Error Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Custom Script for Preview -->
<script>
// Preview file name when selected
document.getElementById('excel_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        const fileExt = fileName.split('.').pop();
        const allowedExts = ['xlsx', 'xls'];

        if (allowedExts.includes(fileExt)) {
            document.querySelector('.upload-area h5').textContent = fileName;
            document.querySelector('.upload-area p').classList.add('text-success');
            document.querySelector('.upload-area p').textContent = 'File terpilih dan siap diimport';
        } else {
            document.querySelector('.upload-area h5').textContent = 'Format File Tidak Valid';
            document.querySelector('.upload-area p').classList.add('text-danger');
            document.querySelector('.upload-area p').textContent = 'Harus .xlsx atau .xls';
            e.target.value = '';
        }
    }
});

// Animate stats on scroll
window.addEventListener('scroll', () => {
    const stats = document.querySelectorAll('.stat-card');
    stats.forEach((stat, index) => {
        const rect = stat.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            stat.style.animation = 'fadeInUp 0.5s ease forwards';
            stat.style.animationDelay = `${index * 0.1}s`;
        }
    });
});

// Add animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection