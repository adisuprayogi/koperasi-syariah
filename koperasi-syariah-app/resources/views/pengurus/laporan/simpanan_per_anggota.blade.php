@extends('layouts.app')

@section('title', 'Laporan Simpanan per Anggota')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Simpanan per Anggota</h1>
        <p class="text-gray-600 mt-2">Laporan detail simpanan untuk setiap anggota</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('pengurus.laporan.simpanan-per-anggota') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Anggota</label>
                    <select name="anggota_id" id="anggota_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Anggota --</option>
                        @foreach($listAnggota as $a)
                            <option value="{{ $a->id }}" {{ $anggota && $anggota->id == $a->id ? 'selected' : '' }}>
                                {{ $a->nomor_anggota }} - {{ $a->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Tampilkan Laporan
                    </button>
                </div>
                @if($reportData && count($reportData) > 0)
                <div class="flex items-end">
                    <a href="{{ route('pengurus.laporan.print', 'simpanan-per-anggota') }}?{{ http_build_query(request()->query()) }}"
                       target="_blank"
                       class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>

    @if($anggota)
    <!-- Anggota Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Informasi Anggota</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-blue-700">Nomor Anggota</p>
                <p class="font-semibold text-blue-900">{{ $anggota->nomor_anggota }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-700">Nama Lengkap</p>
                <p class="font-semibold text-blue-900">{{ $anggota->nama_lengkap }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-700">Status Keanggotaan</p>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    {{ $anggota->status_keanggotaan == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($anggota->status_keanggotaan) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Simpanan Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        @foreach($reportData as $data)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ $data['jenis']->nama_simpanan }}</h3>
                <div class="px-3 py-1 {{ $data['jenis']->tipe_simpanan == 'modal' ? 'bg-indigo-100' : ($data['jenis']->tipe_simpanan == 'pokok' ? 'bg-blue-100' : ($data['jenis']->tipe_simpanan == 'wajib' ? 'bg-yellow-100' : 'bg-green-100')) }} rounded-full">
                    <span class="text-sm font-medium {{ $data['jenis']->tipe_simpanan == 'modal' ? 'text-indigo-800' : ($data['jenis']->tipe_simpanan == 'pokok' ? 'text-blue-800' : ($data['jenis']->tipe_simpanan == 'wajib' ? 'text-yellow-800' : 'text-green-800')) }}">
                        {{ $data['jenis']->tipe_simpanan }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600">Total Setor</p>
                    <p class="text-lg font-bold text-green-600">{{ number_format($data['total_setor'], 0, ',', '.') }}</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600">Total Tarik</p>
                    <p class="text-lg font-bold text-red-600">{{ number_format($data['total_tarik'], 0, ',', '.') }}</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600">Saldo</p>
                    <p class="text-lg font-bold {{ $data['saldo'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        {{ number_format($data['saldo'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            @if($data['recent_transaksi']->count() > 0)
            <div class="border-t pt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Transaksi Terkini</h4>
                <div class="space-y-2">
                    @foreach($data['recent_transaksi'] as $transaksi)
                    <div class="flex items-center justify-between text-sm p-2 bg-gray-50 rounded">
                        <div class="flex items-center">
                            <i class="fas {{ $transaksi->jenis_transaksi == 'setor' ? 'fa-arrow-up text-green-500' : 'fa-arrow-down text-red-500' }} mr-2"></i>
                            <span>{{ $transaksi->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($transaksi->pengurus)
                                <span class="text-gray-500">{{ $transaksi->pengurus->nama_lengkap }}</span>
                            @endif
                            <span class="font-semibold {{ $transaksi->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Total Summary -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
        <h3 class="text-lg font-semibold mb-4">Ringkasan Total Simpanan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <p class="text-blue-100 text-sm">Total Setoran</p>
                <p class="text-2xl font-bold">{{ number_format(collect($reportData)->sum('total_setor'), 0, ',', '.') }}</p>
            </div>
            <div class="text-center">
                <p class="text-blue-100 text-sm">Total Penarikan</p>
                <p class="text-2xl font-bold">{{ number_format(collect($reportData)->sum('total_tarik'), 0, ',', '.') }}</p>
            </div>
            <div class="text-center">
                <p class="text-blue-100 text-sm">Total Saldo</p>
                <p class="text-2xl font-bold">{{ number_format(collect($reportData)->sum('saldo'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    @elseif(request('anggota_id'))
    <!-- No Data Message -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
        <p class="text-yellow-700">Pilih anggota terlebih dahulu untuk menampilkan laporan</p>
    </div>
    @else
    <!-- Instructions -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
        <i class="fas fa-info-circle text-gray-500 text-2xl mb-2"></i>
        <p class="text-gray-700">Pilih anggota dari dropdown di atas untuk melihat laporan simpanan detail</p>
    </div>
    @endif
</div>
@endsection