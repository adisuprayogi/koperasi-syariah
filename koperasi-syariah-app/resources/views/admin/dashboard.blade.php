@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600 mt-2">Selamat datang di halaman Administrator Koperasi Syariah</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-primary-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-primary-100 rounded-lg">
                    <i class="fas fa-users text-primary-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Pengurus</h3>
                    <p class="text-2xl font-bold text-primary-600">{{ $totalPengurus }}</p>
                    <p class="text-sm text-gray-600">{{ $pengurusAktif }} aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-secondary-500 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="p-3 bg-secondary-100 rounded-lg">
                    <i class="fas fa-user-friends text-secondary-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Anggota</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $totalAnggota }}</p>
                    <p class="text-sm text-gray-600">{{ $anggotaAktif }} aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-piggy-bank text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Jenis Simpanan</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalJenisSimpanan }}</p>
                    <p class="text-sm text-gray-600">{{ $simpananAktif }} aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-hand-holding-usd text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Jenis Pembiayaan</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $totalJenisPembiayaan }}</p>
                    <p class="text-sm text-gray-600">{{ $pembiayaanAktif }} aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Pengurus by Position -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Pengurus</h3>
            <div class="space-y-3">
                @foreach($pengurusByPosisi as $posisi)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($posisi->posisi) }}</span>
                    <span class="text-sm font-bold text-blue-600">{{ $posisi->total }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($posisi->total / $totalPengurus * 100) }}%"></div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Anggota by Type -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Anggota</h3>
            <div class="space-y-3">
                @foreach($anggotaByJenis as $jenis)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">{{ ucfirst($jenis->jenis_anggota) }}</span>
                    <span class="text-sm font-bold text-green-600">{{ $jenis->total }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($jenis->total / $totalAnggota * 100) }}%"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Koperasi Info & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Koperasi Info -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Koperasi</h3>
            @if($koperasi)
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Koperasi</p>
                        <p class="font-semibold">{{ $koperasi->nama_koperasi }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Koperasi</p>
                        <p class="font-semibold">{{ $koperasi->no_koperasi }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Telepon</p>
                        <p class="font-semibold">{{ $koperasi->telepon }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $koperasi->email }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-semibold">{{ $koperasi->alamat }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-600">Data koperasi belum diatur.
                    <a href="{{ route('admin.koperasi.edit') }}" class="text-blue-600 hover:underline">Atur sekarang</a>
                </p>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terkini</h3>
            <div class="space-y-2">
                @foreach($recentActivities as $activity)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <span class="text-sm text-gray-700">{{ $activity->activity }}</span>
                    <span class="text-xs text-gray-500">{{ $activity->time }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('admin.pengurus.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-users text-blue-600 mr-3"></i>
                <span class="font-medium text-gray-900">Manajemen Pengurus</span>
            </a>
            <a href="{{ route('admin.koperasi.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-building text-green-600 mr-3"></i>
                <span class="font-medium text-gray-900">Data Koperasi</span>
            </a>
            <a href="{{ route('admin.jenis-simpanan.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-piggy-bank text-purple-600 mr-3"></i>
                <span class="font-medium text-gray-900">Jenis Simpanan</span>
            </a>
            <a href="{{ route('admin.import.simpanan') }}" class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                <i class="fas fa-file-excel text-indigo-600 mr-3"></i>
                <span class="font-medium text-gray-900">Import Simpanan</span>
            </a>
            <a href="{{ route('admin.import.pembiayaan') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <i class="fas fa-file-import text-orange-600 mr-3"></i>
                <span class="font-medium text-gray-900">Import Pembiayaan</span>
            </a>
            <a href="{{ route('admin.import.pembayaran-angsuran') }}" class="flex items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors">
                <i class="fas fa-money-check-alt text-pink-600 mr-3"></i>
                <span class="font-medium text-gray-900">Import Pembayaran</span>
            </a>
            <a href="{{ route('pengurus.anggota.import') }}" class="flex items-center p-4 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                <i class="fas fa-users-cog text-teal-600 mr-3"></i>
                <span class="font-medium text-gray-900">Import Anggota</span>
            </a>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-yellow-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Informasi
        </h3>
        <p class="text-yellow-700 text-sm">
            Selamat datang di sistem manajemen Koperasi Syariah. Sebagai Administrator, Anda memiliki akses penuh untuk mengelola:
        </p>
        <ul class="list-disc list-inside text-yellow-700 text-sm mt-2">
            <li>Data pengurus koperasi</li>
            <li>Profil koperasi</li>
            <li>Master data jenis simpanan</li>
            <li>Master data jenis pembiayaan</li>
            <li>Import data anggota dari Excel</li>
            <li>Import transaksi simpanan dari Excel</li>
            <li>Import data pembiayaan dari Excel</li>
            <li>Import pembayaran angsuran dari Excel</li>
        </ul>
        <p class="text-yellow-600 text-xs mt-3">
            <i class="fas fa-lightbulb mr-1"></i>
            Fitur import memudahkan migrasi data dari sistem lama atau input data masal dengan cepat.
        </p>
    </div>
</div>
@endsection