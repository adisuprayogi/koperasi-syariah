@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-gray-800">
                        <i class="fas fa-file-excel text-success mr-2"></i>
                        Import Data Transaksi Simpanan
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    
                    <!-- Alert Instructions -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle mr-2"></i>Petunjuk Import:</h6>
                        <ol class="mb-0">
                            <li>Download template CSV terlebih dahulu (bisa dibuka dengan Excel)</li>
                            <li>Isi data sesuai format yang ada di template</li>
                            <li>Pastikan No Anggota sudah terdaftar di sistem</li>
                            <li>Jenis Simpanan harus sesuai dengan data di sistem</li>
                            <li>Jenis Transaksi: <strong>setor</strong> atau <strong>tarik</strong></li>
                            <li>Jumlah tulis tanpa format (mis: 500000 untuk Rp 500.000)</li>
                            <li>Tanggal format: YYYY-MM-DD (mis: 2024-12-01)</li>
                            <li><strong>Bulan:</strong> Angka 1-12 (1=Januari, 12=Desember)</li>
                            <li><strong>Tahun:</strong> Tahun simpanan (mis: 2024)</li>
                            <li>Bulan dan Tahun adalah bulan/tahun simpanan, bukan tanggal pembayaran</li>
                            <li>Contoh: Simpanan bulan Desember 2024 yang dibayar Januari 2025 → Bulan=12, Tahun=2024</li>
                            <li>Simpan sebagai CSV atau Excel (.xlsx) untuk diimport</li>
                        </ol>
                    </div>

                    <!-- Download Template Button -->
                    <div class="text-center mb-4">
                        <a href="{{ route('admin.import.simpanan.template') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-download mr-2"></i>
                            Download Template CSV
                        </a>
                    </div>

                    <!-- Import Form -->
                    <form action="{{ route('admin.import.simpanan.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excel_file" class="form-label font-weight-bold">
                                <i class="fas fa-file-excel mr-2"></i>Pilih File
                            </label>
                            <input type="file" class="form-control-file" id="excel_file" name="excel_file"
                                   accept=".xlsx,.xls,.csv" required>
                            <small class="form-text text-muted">Format file: .xlsx, .xls, atau .csv (Maks. 10MB)</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload mr-2"></i>Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection