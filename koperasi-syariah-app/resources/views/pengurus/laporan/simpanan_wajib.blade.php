@extends('layouts.app')

@section('title', 'Laporan Simpanan Wajib - ' . \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') . ' ' . $tahun)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Simpanan Wajib</h1>
            <p class="text-gray-600 mt-2">Status pembayaran simpanan wajib {{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }} {{ $tahun }}</p>
        </div>
        <div class="flex space-x-3">
            <form action="{{ route('pengurus.laporan.simpanan-wajib') }}" method="GET" class="flex">
                <select name="bulan" class="px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
                <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="2030"
                       class="px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500">
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-r-md transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <a href="{{ route('pengurus.laporan.print', 'simpanan-wajib') }}?bulan={{ $bulan }}&tahun={{ $tahun }}"
               target="_blank"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                <i class="fas fa-print mr-2"></i>Cetak
            </a>
            <a href="{{ route('pengurus.laporan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Anggota</p>
                    <p class="text-2xl font-bold text-gray-900">{{ count($reportData) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Sudah Lunas</p>
                    <p class="text-2xl font-bold text-green-600">{{ $jumlahLunas }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Belum Lunas</p>
                    <p class="text-2xl font-bold text-red-600">{{ $jumlahBelumLunas }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-percentage text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Persentase</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($persentasePembayaran, 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Terhutang</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalTerhutang, 0, ',', '.') }}</p>
                </div>
                <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Terbayar</p>
                    <p class="text-xl font-bold text-green-600">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</p>
                </div>
                <i class="fas fa-check-square text-green-400 text-2xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Tunggakan</p>
                    <p class="text-xl font-bold text-red-600">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                </div>
                <i class="fas fa-exclamation-circle text-red-400 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow mb-8 p-6">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700">Progress Pembayaran</span>
            <span class="text-sm text-gray-500">{{ number_format($persentasePembayaran, 1) }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-4 rounded-full transition-all duration-300"
                 style="width: {{ $persentasePembayaran }}%"></div>
        </div>
    </div>

    <!-- Member List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pembayaran per Anggota</h2>
            <span class="text-sm text-gray-500">{{ count($reportData) }} anggota</span>
        </div>
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full divide-y divide-gray-200" style="table-layout: fixed;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Informasi Anggota
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Terhutang
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Terbayar
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tunggakan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reportData as $index => $data)
                        <tr class="hover:bg-gray-50 {{ $data['tunggakan'] > 0 ? 'bg-red-50' : 'bg-green-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $data['anggota']->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">{{ $data['anggota']->no_anggota }}</div>
                                <div class="text-xs text-gray-400">{{ $data['anggota']->no_hp }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($data['terhutang'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $data['terbayar'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                @if($data['terbayar'] > 0)
                                    Rp {{ number_format($data['terbayar'], 0, ',', '.') }}
                                @else
                                    <span class="text-red-500">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $data['tunggakan'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                @if($data['tunggakan'] > 0)
                                    Rp {{ number_format($data['tunggakan'], 0, ',', '.') }}
                                @else
                                    <span class="text-green-500">Lunas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $data['status'] == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $data['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($data['transaksi'])
                                    <a href="{{ route('pengurus.simpanan.show', $data['transaksi']->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pengurus.simpanan.print', $data['transaksi']->id) }}?preview=1"
                                       target="_blank"
                                       class="text-green-600 hover:text-green-900 ml-2"
                                       title="Cetak Bukti">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @else
                                    <a href="{{ route('pengurus.simpanan.create') }}?anggota_id={{ $data['anggota']->id }}&jenis_simpanan_id={{ $jenisWajib->id }}"
                                       class="text-blue-600 hover:text-blue-900"
                                       title="Input Pembayaran">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Action Buttons -->
    @if($jumlahBelumLunas > 0)
    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Masih ada {{ $jumlahBelumLunas }} anggota yang belum lunas simpanan wajib
                </h3>
                <p class="text-sm text-yellow-600 mt-1">
                    Total tunggakan: Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                </p>
            </div>
            <div class="space-x-3">
                <a href="{{ route('pengurus.simpanan.create') }}?jenis_simpanan_id={{ $jenisWajib->id }}"
                   class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md transition-colors">
                    <i class="fas fa-plus mr-2"></i>Input Pembayaran
                </a>
                <button onclick="if(confirm('Apakah Anda yakin ingin generate simpanan wajib untuk semua anggota?')) { window.location.href = '{{ route('pengurus.laporan.index') }}' }"
                        class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-md transition-colors">
                    <i class="fas fa-sync mr-2"></i>Generate Otomatis
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection