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
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Pengurus</h3>
                    <p class="text-2xl font-bold text-blue-600">0</p>
                </div>
            </div>
        </div>

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
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-hand-holding-usd text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Simpanan</h3>
                    <p class="text-2xl font-bold text-purple-600">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-file-invoice-dollar text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Pembiayaan</h3>
                    <p class="text-2xl font-bold text-red-600">Rp 0</p>
                </div>
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
        </ul>
    </div>
</div>
@endsection