@extends('layouts.app')

@section('title', 'Import Data Anggota')

@section('styles')
<style>
/* Premium Import Styles */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.premium-container {
    background: transparent;
    padding: 0;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 3rem;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 20s infinite linear;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.hero-subtitle {
    font-size: 1.1rem;
    font-weight: 300;
    opacity: 0.9;
    margin-bottom: 2rem;
}

/* Card Styles */
.premium-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.premium-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.15);
}

.card-header-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: none;
}

.card-header-gradient h5 {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
}

/* Step Cards */
.step-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.step-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.step-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.step-number {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    margin: 0 auto 1.5rem;
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.step-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.step-description {
    color: #718096;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

/* Guide Box */
.guide-box {
    background: linear-gradient(135deg, #56ccf2 0%, #2f80ed 100%);
    color: white;
    border-radius: 20px;
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

.guide-box::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
}

.guide-icon {
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 1rem;
    backdrop-filter: blur(10px);
}

.guide-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.guide-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.guide-list li {
    padding: 0.5rem 0;
    display: flex;
    align-items: center;
}

.guide-list li i {
    margin-right: 1rem;
    font-size: 1.1rem;
    width: 24px;
}

/* Upload Area */
.upload-zone {
    border: 3px dashed #cbd5e0;
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.upload-zone:hover {
    border-color: #667eea;
    background: white;
    transform: scale(1.02);
}

.upload-icon {
    font-size: 4rem;
    color: #667eea;
    margin-bottom: 1.5rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.upload-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.upload-description {
    font-size: 1rem;
    color: #718096;
    margin-bottom: 2rem;
}

.file-input-wrapper {
    position: relative;
    display: inline-block;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-input-label {
    display: inline-block;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(78, 115, 223, 0.3);
}

.file-input-label:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(78, 115, 223, 0.4);
}

/* Buttons */
.btn-premium {
    padding: 1rem 2.5rem;
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 50px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.3);
}

.btn-primary-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-success-gradient {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.btn-danger-gradient {
    background: linear-gradient(135deg, #ee5a24 0%, #f57c00 100%);
    color: white;
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
    color: #2d3748;
}

/* Table Styles */
.table-premium {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.table-premium thead th {
    background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 1px;
    padding: 1rem;
    border: none;
}

.table-premium tbody tr {
    transition: all 0.3s ease;
}

.table-premium tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.01);
}

.table-premium td {
    vertical-align: middle;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.9rem;
}

/* Badges */
.badge-premium {
    padding: 0.4rem 0.8rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-required {
    background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    color: white;
}

.badge-optional {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.badge-auto {
    background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
    color: white;
}

/* Code Blocks */
.code-block {
    background: #1e293b;
    color: #e2e8f0;
    padding: 0.2rem 0.5rem;
    border-radius: 5px;
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.stat-card.total::before { background: linear-gradient(90deg, #667eea, #764ba2); }
.stat-card.success::before { background: linear-gradient(90deg, #11998e, #38ef7d); }
.stat-card.error::before { background: linear-gradient(90deg, #f56565, #e53e3e); }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-label {
    font-size: 0.9rem;
    color: #718096;
    font-weight: 500;
}

/* Example Section */
.example-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(00,0,0.05);
}

.example-header {
    background: linear-gradient(135deg, #805ad5 0%, #676366 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 10px 10px 0 0;
    margin-bottom: 0;
}

.example-header h6 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
}

.example-content {
    background: #f8fafc;
    border-radius: 0 0 10px 10px;
    padding: 1.5rem;
}

/* Tips Cards */
.tips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.tip-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.tip-header {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    gap: 1rem;
}

.tip-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.tip-icon.success { background: linear-gradient(135deg, #11998e, #38ef7d); }
.tip-icon.danger { background: linear-gradient(135deg, #f56565, #e53e3e); }

.tip-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.tip-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tip-content li {
    padding: 0.5rem 0;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    color: #4a5568;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out forwards;
}

.animate-slideInLeft {
    animation: slideInLeft 0.6s ease-out forwards;
}

.animate-bounceIn {
    animation: bounceIn 0.8s ease-out forwards;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-section {
        padding: 2rem 1rem;
        text-align: center;
    }

    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1rem;
    }

    .step-card {
        padding: 1.5rem 1rem;
    }

    .step-number {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .guide-box {
        padding: 1.5rem;
    }

    .btn-premium {
        padding: 0.75rem 2rem;
        font-size: 0.9rem;
        flex-direction: column;
        margin: 0.5rem;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .tips-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

/* Loading State */
.loading-spinner {
    border: 3px solid rgba(255,255,255,0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* File Preview */
.file-preview {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 25px;
    color: white;
    font-size: 0.9rem;
    margin-left: 1rem;
}

/* Success Message */
.success-check {
    color: #38ef7d;
    font-size: 1.5rem;
    animation: checkmark 0.5s ease-in-out;
}

@keyframes checkmark {
    0% { transform: scale(0) rotate(45deg); }
    50% { transform: scale(1.2) rotate(45deg); }
    100% { transform: scale(1) rotate(45deg); }
}
</style>
@endsection

@section('content')
<div class="premium-container">
    <!-- Hero Section -->
    <div class="hero-section animate-fadeInUp">
        <div class="hero-content">
            <div class="text-center">
                <h1 class="hero-title">
                    <i class="fas fa-file-excel me-3"></i>
                    Import Data Anggota
                </h1>
                <p class="hero-subtitle">
                    Kelola keanggotaan koperasi Anda dengan mudah dan efisien melalui import data massal dari file Excel
                </p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 animate-slideInLeft" role="alert">
            <div class="d-flex align-items-center">
                <span class="success-check me-3"></span>
                <div>
                    <h5 class="mb-1">✨ Import Berhasil!</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 animate-slideInLeft" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3"></i>
                <div>
                    <h5 class="mb-1">⚠️ Terjadi Error!</h5>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="stats-grid mb-4">
        <div class="stat-card total animate-bounceIn" style="animation-delay: 0.1s">
            <div class="stat-number" id="totalUploads">0</div>
            <div class="stat-label">Total Upload</div>
        </div>
        <div class="stat-card success animate-bounceIn" style="animation-delay: 0.2s">
            <div class="stat-number" id="successCount">0</div>
            <div class="stat-label">Berhasil</div>
        </div>
        <div class="stat-card error animate-bounceIn" style="animation-delay: 0.3s">
            <div class="stat-number" id="errorCount">0</div>
            <div class="stat-label">Gagal</div>
        </div>
    </div>

    <!-- Process Steps -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="step-card animate-fadeInUp" style="animation-delay: 0.1s">
                <div class="step-number">1</div>
                <h6 class="step-title">Download Template</h6>
                <p class="step-description">
                    Unduh format Excel yang sudah disediakan
                </p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="step-card animate-fadeInUp" style="animation-delay: 0.2s">
                <div class="step-number">2</div>
                <h6 class="step-title">Isi Data</h6>
                <p class="step-description">
                    Lengkapi data anggota sesuai format
                </p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="step-card animate-fadeInUp" style="animation-delay: 0.3s">
                <div class="step-number">3</div>
                <h6 class="step-title">Upload File</h6>
                <p class="step-description">
                    Upload file Excel yang sudah diisi
                </p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="step-card animate-fadeInUp" style="animation-delay: 0.4s">
                <div class="step-number">4</div>
                <h6 class="step-title">Review Hasil</h6>
                <p class="step-description">
                    Cek data yang berhasil diimport
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column - Nomor Anggota Guide -->
        <div class="col-lg-4 mb-4">
            <div class="premium-card animate-slideInLeft" style="animation-delay: 0.5s">
                <div class="guide-box">
                    <div class="guide-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h3 class="guide-title">Panduan Nomor Anggota</h3>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 style="color: rgba(255,255,255,0.9); margin-bottom: 1rem;">
                                <i class="fas fa-sync-alt me-2"></i>
                                Auto-Generate (Rekomendasi)
                            </h6>
                            <ul class="guide-list">
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>Kosongkan</strong> kolom K</li>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>Format</strong>: YYYY + 4 digit</span>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>Contoh</strong>: 20250001</span>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>Keuntungan</strong>: Otomatis & konsisten</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 mt-4">
                            <h6 style="color: rgba(255,255,255,0.9); margin-bottom: 1rem;">
                                <i class="fas fa-edit me-2"></i>
                                Manual Input (Opsional)
                            </h6>
                            <ul class="guide-list">
                                <li>
                                    <i class="fas fa-pencil-alt"></i>
                                    <span><strong>Isi sendiri</strong> nomor</span>
                                </li>
                                <li>
                                    <i class="fas fa-pencil-alt"></i>
                                    <span><strong>Format</strong>: Bebas</span>
                                </li>
                                <li>
                                    <i class="fas fa-pencil-alt"></i>
                                    <span><strong>Contoh</strong>: A001</span>
                                </li>
                                <li>
                                    <i class="fas fa-pencil-alt"></i>
                                    <span><strong>Syarat</strong>: Harus UNIQUE</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Download -->
            <div class="premium-card mt-4 animate-slideInLeft" style="animation-delay: 0.6s">
                <div class="upload-zone">
                    <div class="upload-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <h5 class="upload-title">Download Template</h5>
                    <p class="upload-description">
                        Template Excel 11 kolom lengkap
                    </p>
                    <div class="file-input-wrapper">
                        <input type="file" class="file-input" id="templateFile" accept=".xlsx,.xls">
                        <label for="templateFile" class="file-input-label">
                            <i class="fas fa-download me-2"></i>
                            Download Template
                        </label>
                    </div>
                    <form action="{{ route('pengurus.anggota.import.template') }}" method="GET">
                        <button type="submit" class="btn btn-premium btn-success-gradient">
                            <i class="fas fa-file-download me-2"></i>
                            Download Template
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Upload Form -->
        <div class="col-lg-8 mb-4">
            <div class="premium-card animate-fadeInUp" style="animation-delay: 0.7s">
                <div class="card-header-gradient">
                    <h5>
                        <i class="fas fa-cloud-upload-alt me-2"></i>
                        Upload File Excel
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pengurus.anggota.import.store') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf

                        <div class="upload-zone" id="uploadZone">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h5 class="upload-title">Pilih File Excel</h5>
                            <p class="upload-description">
                                Format: .xlsx atau .xls (Maksimal 10MB)
                            </p>
                            <div class="file-input-wrapper">
                                <input type="file"
                                       class="file-input"
                                       id="excel_file"
                                       name="excel_file"
                                       accept=".xlsx,.xls"
                                       required>
                                <label for="excel_file" class="file-input-label">
                                    <i class="fas fa-folder-open me-2"></i>
                                    Pilih File Excel
                                </label>
                            </div>
                            <div id="filePreview" class="file-preview" style="display: none;">
                                <i class="fas fa-file-excel me-2"></i>
                                <span id="fileName"></span>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn-premium btn-primary-gradient" id="submitBtn">
                                <i class="fas fa-file-import me-2"></i>
                                Import Data Sekarang
                            </button>
                            <a href="{{ route('pengurus.anggota.index') }}" class="btn btn-premium btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Format Table -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="premium-card animate-fadeInUp" style="animation-delay: 0.8s">
                <div class="card-header-gradient">
                    <h5>
                        <i class="fas fa-table me-2"></i>
                        Format Excel Lengkap
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-premium">
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
                                <tr>
                                    <td class="text-center fw-bold text-primary">A</td>
                                    <td><span class="code-block">nama_lengkap</span></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-required">Required</span>
                                    </td>
                                    <td>Ahmad Rizki</td>
                                    <td>Nama lengkap anggota</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">B</td>
                                    <td><span class="code-block">jenis_kelamin</span></td>
                                    <td class="text-center">Option</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-optional">Optional</span>
                                    </td>
                                    <td><code>L</code> atau <code>P</code></td>
                                    <td>L = Laki-laki, P = Perempuan</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">C</td>
                                    <td><span class="code-block">tempat_lahir</span></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-optional">Optional</span>
                                    </td>
                                    <td>Jakarta</td>
                                    <td>Tempat lahir</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">D</td>
                                    <td><span class="code-block">tanggal_lahir</span></td>
                                    <td class="text-center">Date</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-optional">Optional</span>
                                    </td>
                                    <td><code>1990-01-15</code></td>
                                    <td>Format: YYYY-MM-DD</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">E</td>
                                    <td><span class="code-block">alamat_lengkap</span></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-optional">Optional</span>
                                    </td>
                                    <td>Jl. Merdeka No. 123</td>
                                    <td>Alamat lengkap</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">F</td>
                                    <td><span class="code-block">no_hp</span></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-optional">Optional</span>
                                    </td>
                                    <td><code>08123456789</code></td>
                                    <td>Nomor HP/WA</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">G</td>
                                    <td><span class="code-block">pekerjaan</span></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-optional">Optional</span>
                                    </td>
                                    <td>Pegawai Swasta</td>
                                    <td>Pekerjaan</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">H</td>
                                    <td><span class="code-block">penghasilan</span></td>
                                    <td class="text-center">Number</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-optional">Optional</span>
                                    </td>
                                    <td><code>5000000</code></td>
                                    <td>Dalam Rupiah</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">I</td>
                                    <td><span class="code-block">email</span></td>
                                    <td class="text-center">Email</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-required">Required</span>
                                    </td>
                                    <td>user@example.com</td>
                                    <td>Email login (unique)</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-primary">J</td>
                                    <td><span class="code-block">password</span></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-required">Required</span>
                                    </td>
                                    <td><code>password123</code></td>
                                    <td>Password login</td>
                                </tr>
                                <tr class="table-active">
                                    <td class="text-center fw-bold text-primary">K</td>
                                    <td><span class="code-block">no_anggota</span></td>
                                    <td class="text-center">Text</td>
                                    <td class="text-center">
                                        <span class="badge-premium badge-auto">Auto</span>
                                    </td>
                                    <td><em>Kosongkan</em></td>
                                    <td>Auto-generate</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Example Data Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="example-section animate-fadeInUp" style="animation-delay: 0.9s">
                <div class="example-header">
                    <h6 class="mb-0">
                        <i class="fas fa-code me-2"></i>
                        Contoh Data Excel
                    </h6>
                </div>
                <div class="example-content">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>A</th><th>B</th><th>C</th><th>D</th><th>E</th>
                                    <th>F</th><th>G</th><th>H</th><th>I</th><th>J</th><th>K</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-success">
                                    <td>Ahmad Rizki</td>
                                    <td>L</td>
                                    <td>Jakarta</td>
                                    <td>1990-01-15</td>
                                    <td>Jl. Merdeka No. 123</td>
                                    <td>08123456789</td>
                                    <td>Pegawai</td>
                                    <td>5000000</td>
                                    <td>ahmad@email.com</td>
                                    <td>pass123</td>
                                    <td class="text-muted font-italic">[Auto]</td>
                                </tr>
                                <tr class="table-warning">
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
                                    <td class="text-muted font-italic">[Auto]</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tips & Warnings -->
    <div class="tips-grid">
        <div class="tip-card animate-fadeInUp" style="animation-delay: 1s">
            <div class="tip-header">
                <div class="tip-icon success">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div>
                    <h6 class="tip-title">Tips & Trik</h6>
                </div>
            </div>
            <div class="tip-content">
                <ul>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Selalu gunakan template yang disediakan untuk format yang benar</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Kosongkan kolom K untuk auto-generate nomor anggota</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Pastikan email unik untuk setiap anggota (system akan validasi)</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Format tanggal: YYYY-MM-DD (contoh: 2024-01-15)</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Penghasilan dalam angka saja tanpa titik</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Test dengan 5-10 data sebelum import besar</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tip-card animate-fadeInUp" style="animation-delay: 1.1s">
            <div class="tip-header">
                <div class="tip-icon danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h6 class="tip-title">Perhatian Penting</h6>
                </div>
            </div>
            <div class="tip-content">
                <ul>
                    <li>
                        <i class="fas fa-times-circle"></i>
                        <span>Email harus <strong>unik</strong> untuk setiap anggota</span>
                    </li>
                    <li>
                        <i class="fas fa-times-circle"></i>
                        <strong>Nomor anggota manual</strong> harus <strong>unik</strong></li>
                    </li>
                    <li>
                        <i class="fas fa-times-circle"></i>
                        <strong>Maksimal file size:</strong> 10MB</li>
                    </li>
                    <li>
                        <i class="fas fa-times-circle"></i>
                        <strong>Format file:</strong> Hanya .xlsx atau .xls</li>
                    </li>
                    <li>
                        <i class="fas fa-times-circle"></i>
                        <strong>Jangan merge cells</strong> di Excel</li>
                    </li>
                    <li>
                        <i class="fas fa-times-circle"></i>
                        <strong>Hapus baris kosong</strong> yang tidak digunakan</li>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Error Report (if any) -->
    @if(session('error_count') > 0 && session('errors'))
        <div class="row mt-4">
            <div class="col-12">
                <div class="premium-card border-warning animate-fadeInUp" style="animation-delay: 1.2s">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="text-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ session('error_count') }} Data Gagal Diimport
                                </h5>
                                <p class="text-warning">
                                    Data gagal diimport karena format tidak sesuai atau ada duplikasi data. Download laporan error untuk melihat detail kesalahan.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="{{ route('pengurus.anggota.import.error-report') }}" class="btn-premium btn-danger-gradient">
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

<!-- JavaScript -->
<script>
// File preview when file is selected
document.getElementById('excel_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const submitBtn = document.getElementById('submitBtn');

    if (file) {
        const fileNameLower = file.name.toLowerCase();
        const validExtensions = ['xlsx', 'xls'];

        if (validExtensions.includes(fileNameLower.split('.').pop())) {
            // Show file preview
            preview.style.display = 'inline-flex';
            fileName.textContent = file.name;

            // Update upload zone
            const uploadZone = document.getElementById('uploadZone');
            uploadZone.style.borderColor = '#10b981';
            uploadZone.style.background = 'white';
            uploadZone.querySelector('.upload-title').textContent = 'File Terpilih';
            uploadZone.querySelector('.upload-description').textContent = 'File siap untuk diimport';

            // Enable submit button
            submitBtn.disabled = false;
        } else {
            // Show error
            uploadZone.style.borderColor = '#ef4444';
            uploadZone.style.background = '#fee';
            uploadZone.querySelector('.upload-title').textContent = 'Format File Tidak Valid';
            uploadZone.querySelector('.upload-description').textContent = 'Harus .xlsx atau .xls';

            preview.style.display = 'none';
            submitBtn.disabled = true;
            e.target.value = '';
        }
    }
});

// Form submission with loading state
document.getElementById('importForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const uploadZone = document.getElementById('uploadZone');

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading-spinner me-2"></span> Mengimport...';
    uploadZone.querySelector('.upload-icon').className = 'fas fa-spinner fa-spin';

    // Reset file preview after submission
    setTimeout(() => {
        document.getElementById('filePreview').style.display = 'none';
        document.getElementById('fileName').textContent = '';
        document.getElementById('excel_file').value = '';
        document.getElementById('uploadZone').style.borderColor = '#cbd5e0';
        document.getElementById('uploadZone').style.background = '#f8fafc';
        document.getElementById('uploadZone').querySelector('.upload-title').textContent = 'Pilih File Excel';
        document.getElementById('uploadZone').querySelector('.upload-description').textContent = 'Format: .xlsx atau .xls (Maksimal 10MB)';
        document.getElementById('uploadZone').querySelector('.upload-icon').className = 'fas fa-cloud-upload-alt';
    }, 1000);
});

// Add click event to template download form
document.getElementById('templateFile').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileName = file.name;
        console.log('Template selected:', fileName);
        // Auto submit template download
        document.querySelector('form[action*="template"]').submit();
    }
});

// Animate elements on scroll
window.addEventListener('scroll', () => {
    const animatedElements = document.querySelectorAll('.animate-fadeInUp, .animate-slideInLeft, .animate-bounceIn');

    animatedElements.forEach((element, index) => {
        const rect = element.getBoundingClientRect();
        const isVisible = rect.top < window.innerHeight && rect.bottom > 0;

        if (isVisible && !element.classList.contains('animated')) {
                            element.classList.add('animated');
                            element.style.animationDelay = `${index * 0.1}s`;
                        }
                    });
});

// Close error/success messages after 5 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        if (!alert.classList.contains('show')) {
            alert.remove();
        }
    });
}, 5000);
</script>
@endsection