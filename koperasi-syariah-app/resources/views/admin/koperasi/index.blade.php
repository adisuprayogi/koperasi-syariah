@extends('layouts.app')

@section('title', 'Data Koperasi')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Data Koperasi</h1>
            <p class="text-gray-600 mt-2">Kelola profil data koperasi</p>
        </div>
        @if($koperasi)
            <a href="{{ route('admin.koperasi.edit') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i>
                Edit Data Koperasi
            </a>
        @else
            <a href="{{ route('admin.data-koperasi.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Input Data Koperasi
            </a>
        @endif
    </div>

    @if($koperasi)
        <!-- Data Koperasi Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Profil Koperasi</h2>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($koperasi->status == 'aktif')
                            bg-green-100 text-green-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($koperasi->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <!-- Logo dan Nama -->
                <div class="flex items-center mb-6 pb-6 border-b border-gray-200">
                    @if($koperasi->logo)
                        <img class="h-20 w-20 rounded-lg object-cover shadow-lg"
                             src="{{ asset('storage/' . $koperasi->logo) }}"
                             alt="{{ $koperasi->nama_koperasi }}">
                    @else
                        <div class="h-20 w-20 rounded-lg bg-indigo-100 flex items-center justify-center shadow-lg">
                            <i class="fas fa-building text-indigo-600 text-2xl"></i>
                        </div>
                    @endif
                    <div class="ml-6">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $koperasi->nama_koperasi }}</h3>
                        <p class="text-gray-600">{{ $koperasi->no_koperasi }}</p>
                        <div class="flex items-center mt-2 text-sm text-gray-500">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Berdiri sejak {{ $koperasi->tanggal_berdiri->format('d F Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $koperasi->usia_koperasi }} tahun</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Detail -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informasi Umum -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Informasi Kontak</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $koperasi->alamat }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $koperasi->telepon }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $koperasi->email }}</dd>
                            </div>
                            @if($koperasi->website)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Website</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    <a href="{{ $koperasi->website }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                        {{ $koperasi->website }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Informasi Legalitas -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Informasi Legalitas</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">No. Akta Notaris</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $koperasi->no_akta_notaris }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Akta</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $koperasi->tanggal_akta->format('d F Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Notaris</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $koperasi->nama_notaris }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Data Pengurus -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Data Pengurus</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Ketua</h5>
                            <p class="text-sm text-gray-900 font-medium">{{ $koperasi->ketua_nama }}</p>
                            <p class="text-xs text-gray-500">NIK: {{ $koperasi->ketua_nik }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Sekretaris</h5>
                            <p class="text-sm text-gray-900 font-medium">{{ $koperasi->sekretaris_nama }}</p>
                            <p class="text-xs text-gray-500">NIK: {{ $koperasi->sekretaris_nik }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Bendahara</h5>
                            <p class="text-sm text-gray-900 font-medium">{{ $koperasi->bendahara_nama }}</p>
                            <p class="text-xs text-gray-500">NIK: {{ $koperasi->bendahara_nik }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-building text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Data Koperasi</h3>
            <p class="text-gray-500 mb-6">Silakan input data koperasi untuk memulai menggunakan aplikasi</p>
            <a href="{{ route('admin.data-koperasi.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Input Data Koperasi
            </a>
        </div>
    @endif

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Data Koperasi
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Penting:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Data koperasi akan ditampilkan di halaman login</li>
                    <li>Logo koperasi akan muncul di form login</li>
                    <li>Nama koperasi akan digunakan di header aplikasi</li>
                    <li>Data hanya ada satu untuk setiap instalasi aplikasi</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Fitur yang tersedia:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>• Upload logo koperasi (format: JPG, PNG maks. 2MB)</li>
                    <li>• Management data pengurus</li>
                    <li>• Tracking usia koperasi otomatis</li>
                    <li>• Status aktif/tidak aktif</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection