@extends('layouts.app')

@section('title', 'Laporan Tunggakan Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Tunggakan Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Daftar pembiayaan anggota yang menunggak angsuran</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('pengurus.laporan.tunggakan-pembiayaan-export', request()->query()) }}"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
            <a href="{{ route('pengurus.laporan.tunggakan-pembiayaan-print', request()->query()) }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition-colors">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </a>
            <a href="{{ route('pengurus.laporan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4"><i class="fas fa-filter mr-2"></i>Filter Data</h2>
        <form action="{{ route('pengurus.laporan.tunggakan-pembiayaan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Anggota</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama/No. Anggota..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            <!-- Jenis Pembiayaan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembiayaan</label>
                <select name="jenis_pembiayaan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisPembiayaanList as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('jenis_pembiayaan_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_pembiayaan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Bulan Jatuh Tempo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan Jatuh Tempo</label>
                <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Bulan</option>
                    @php
                        $bulanList = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun Jatuh Tempo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Jatuh Tempo</label>
                <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Tahun</option>
                    @php
                        $currentYear = now()->year;
                        $startYear = $currentYear - 3;
                    @endphp
                    @for($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-md transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('pengurus.laporan.tunggakan-pembiayaan') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-md transition-colors">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Sisa Angsuran</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalSisaAngsuran) }} periode</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Bulan Menunggak</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($totalBulanMenunggak) }} bulan</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Jumlah Menunggak</p>
                    <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($totalJumlahMenunggak, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tunggakan Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pembiayaan Menunggak</h2>
            <span class="text-sm text-gray-500">{{ count($tunggakanData) }} pembiayaan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pembiayaan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Angsuran</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan Menunggak</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Menunggak</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tunggakanData as $index => $data)
                        <tr class="hover:bg-gray-50 {{ $data->bulan_menunggak >= 3 ? 'bg-red-50' : ($data->bulan_menunggak >= 1 ? 'bg-yellow-50' : '') }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $data->kode_pengajuan }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $data->nama_anggota }}</div>
                                <div class="text-xs text-gray-500">{{ $data->no_anggota }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $data->jenis_pembiayaan }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">{{ $data->sisa_angsuran }} periode</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right">
                                @if($data->bulan_menunggak >= 3)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $data->bulan_menunggak }} bulan
                                    </span>
                                @elseif($data->bulan_menunggak >= 1)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $data->bulan_menunggak }} bulan
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-semibold text-red-600">
                                Rp {{ number_format($data->jumlah_menunggak, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <a href="{{ route('pengurus.pembiayaan.show', $data->id) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-check-circle text-green-500 text-4xl mb-2"></i>
                                <p>Tidak ada data tunggakan pembiayaan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($tunggakanData) > 0)
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right text-gray-900">TOTAL</td>
                        <td class="px-4 py-3 text-right text-gray-900">{{ number_format($totalSisaAngsuran) }} periode</td>
                        <td class="px-4 py-3 text-right text-gray-900">{{ number_format($totalBulanMenunggak) }} bulan</td>
                        <td class="px-4 py-3 text-right text-red-600">Rp {{ number_format($totalJumlahMenunggak, 0, ',', '.') }}</td>
                        <td class="px-4 py-3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Info Box -->
    @if(count($tunggakanData) > 0)
    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-yellow-600 text-xl mr-3 mt-1"></i>
            <div>
                <h3 class="text-lg font-medium text-yellow-800">Informasi Tunggakan</h3>
                <ul class="text-sm text-yellow-700 mt-2 list-disc list-inside">
                    <li>Data menunjukkan pembiayaan dengan angsuran yang sudah melewati jatuh tempo</li>
                    <li>Warna merah menunjukkan tunggakan >= 3 bulan (perlu perhatian khusus)</li>
                    <li>Warna kuning menunjukkan tunggakan 1-2 bulan</li>
                    <li>Klik "Detail" untuk melihat rincian angsuran dan melakukan pembayaran</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
