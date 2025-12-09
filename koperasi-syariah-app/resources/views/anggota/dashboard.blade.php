@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Anggota</h1>
        <p class="text-gray-600 mt-2">Selamat datang di halaman Anggota Koperasi Syariah</p>
    </div>

    <!-- Info Card -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-green-100">Terima kasih telah menjadi anggota Koperasi Syariah kami</p>
            </div>
            <div class="text-6xl opacity-20">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
    </div>

    <!-- Savings Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <i class="fas fa-coins text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Simpanan Modal</h3>
                    <p class="text-xl font-bold text-gray-900">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-lock text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Simpanan Pokok</h3>
                    <p class="text-xl font-bold text-gray-900">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Simpanan Wajib</h3>
                    <p class="text-xl font-bold text-gray-900">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-hand-holding-heart text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Simpanan Sukarela</h3>
                    <p class="text-xl font-bold text-gray-900">Rp 0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Financing Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembiayaan</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Total Pembiayaan</p>
                    <p class="text-xl font-bold text-gray-900">Rp 0</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sisa Hutang</p>
                    <p class="text-xl font-bold text-red-600">Rp 0</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Angsuran Berjalan</p>
                    <p class="text-xl font-bold text-blue-600">0</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pengajuan</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pengajuan Pending</span>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">0</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pengajuan Disetujui</span>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">0</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pengajuan Ditolak</span>
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('anggota.profile') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                <span class="font-medium text-gray-900">Edit Profile</span>
            </a>
            <a href="{{ route('anggota.simpanan.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-piggy-bank text-green-600 mr-3"></i>
                <span class="font-medium text-gray-900">Lihat Simpanan</span>
            </a>
            <a href="{{ route('anggota.pengajuan.create') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-plus-circle text-purple-600 mr-3"></i>
                <span class="font-medium text-gray-900">Ajukan Pembiayaan</span>
            </a>
            <a href="{{ route('anggota.pembiayaan.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-hand-holding-usd text-yellow-600 mr-3"></i>
                <span class="font-medium text-gray-900">Lihat Pembiayaan</span>
            </a>
        </div>
    </div>

    <!-- Information Panel -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Informasi Penting
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-1">Manfaat Menjadi Anggota:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Akses pembiayaan dengan margin syariah</li>
                    <li>Hasil bagi (nisbah) dari simpanan sukarela</li>
                    <li>Fasilitas koperasi lainnya</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-1">Kewajiban Anggota:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Membayar simpanan pokok</li>
                    <li>Membayar simpanan wajib bulanan</li>
                    <li>Mematuhi peraturan AD/ART koperasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection