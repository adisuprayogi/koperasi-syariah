@extends('layouts.app')

@section('title', 'Preview Kartu Anggota')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Preview Kartu Anggota</h1>
        <p class="text-gray-600 mt-2">Preview kartu untuk: {{ $anggota->nama_lengkap }}</p>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.kartu-anggota.anggota-list') }}"
               class="text-blue-500 hover:text-blue-700">
                ← Kembali ke Daftar Anggota
            </a>
        </div>
        <div class="space-x-4">
            <a href="{{ route('admin.kartu-anggota.html', $anggota->id) }}"
               target="_blank"
               class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Cetak Kartu
            </a>
            <a href="{{ route('admin.kartu-anggota.settings') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Edit Pengaturan
            </a>
        </div>
    </div>

    <!-- ATM Card Size Preview (85.6mm x 53.98mm) -->
    <div class="flex justify-center space-x-8">
        <!-- Front Card -->
        <div class="text-center">
            <h3 class="text-lg font-semibold mb-4">Tampilan Depan</h3>
            <div class="relative bg-white rounded-lg shadow-2xl"
                 style="width: 340px; height: 216px; overflow: hidden;">
                @include('admin.kartu.parts.card-front', ['preview' => true])
            </div>
        </div>

        <!-- Back Card -->
        <div class="text-center">
            <h3 class="text-lg font-semibold mb-4">Tampilan Belakang</h3>
            <div class="relative bg-white rounded-lg shadow-2xl"
                 style="width: 340px; height: 216px; overflow: hidden;">
                @include('admin.kartu.parts.card-back', ['preview' => true])
            </div>
        </div>
    </div>
</div>
@endsection