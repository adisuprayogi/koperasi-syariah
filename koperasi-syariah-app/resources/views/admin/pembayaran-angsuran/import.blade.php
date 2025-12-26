@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-gray-800">
                        <i class="fas fa-file-excel text-success mr-2"></i>
                        Import Pembayaran Angsuran
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
                            <li>Kode Pembiayaan adalah kode unik untuk setiap pengajuan pembiayaan</li>
                            <li>Jumlah Bayar adalah total yang dibayar (boleh cicilan)</li>
                            <li>Status akan otomatis berubah menjadi "lunas" jika pembayaran penuh</li>
                            <li>Duplikasi pembayaran untuk angsuran yang sama tidak diperbolehkan</li>
                            <li>Simpan sebagai CSV atau Excel (.xlsx) untuk diimport</li>
                        </ol>
                    </div>

                    <!-- Download Template Button -->
                    <div class="text-center mb-4">
                        <a href="{{ route('admin.import.pembayaran-angsuran.template') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-download mr-2"></i>
                            Download Template CSV
                        </a>
                    </div>

                    <!-- Import Form -->
                    <form action="{{ route('admin.import.pembayaran-angsuran.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excel_file" class="form-label font-weight-bold">
                                <i class="fas fa-file-excel mr-2"></i>Pilih File CSV
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