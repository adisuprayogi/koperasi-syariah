@extends('layouts.app')

@section('title', 'Dashboard Pengurus')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Pengurus</h1>
        <p class="text-gray-600 mt-2">Selamat datang di halaman Pengurus Koperasi Syariah</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-user-friends text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Anggota</h3>
                    <p class="text-2xl font-bold text-green-600">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-piggy-bank text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Simpanan</h3>
                    <p class="text-2xl font-bold text-blue-600">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-hand-holding-usd text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Pembiayaan</h3>
                    <p class="text-2xl font-bold text-purple-600">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-file-invoice text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pending</h3>
                    <p class="text-2xl font-bold text-yellow-600">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('pengurus.anggota.create') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-user-plus text-green-600 mr-3"></i>
                <span class="font-medium text-gray-900">Tambah Anggota</span>
            </a>
            <a href="{{ route('pengurus.simpanan.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                <span class="font-medium text-gray-900">Input Simpanan</span>
            </a>
            <a href="{{ route('pengurus.pengajuan.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-clipboard-check text-purple-600 mr-3"></i>
                <span class="font-medium text-gray-900">Verifikasi Pengajuan</span>
            </a>
            <a href="{{ route('pengurus.laporan.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-chart-bar text-yellow-600 mr-3"></i>
                <span class="font-medium text-gray-900">Lihat Laporan</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pengajuan Terbaru -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengajuan Terbaru</h3>
            <div class="space-y-3">
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Belum ada pengajuan</p>
                </div>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Terbaru</h3>
            <div class="space-y-3">
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-exchange-alt text-4xl mb-2"></i>
                    <p>Belum ada transaksi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Informasi
        </h3>
        <p class="text-blue-700 text-sm">
            Sebagai Pengurus Koperasi, Anda dapat mengelola anggota, transaksi simpanan, verifikasi pengajuan pembiayaan, dan melihat laporan keuangan.
        </p>
    </div>
</div>
@endsection