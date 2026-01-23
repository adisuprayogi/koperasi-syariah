@extends('layouts.app')

@section('title', 'Laporan Rekap Simpanan Anggota')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Rekap Simpanan Anggota</h1>
            <p class="text-gray-600 mt-2">Rekapitulasi simpanan seluruh anggota koperasi</p>
        </div>
        <div class="flex space-x-3">
            <form action="{{ route('pengurus.laporan.rekap-simpanan') }}" method="GET" class="flex">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/no anggota..."
                       class="px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-r-md transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <a href="{{ route('pengurus.laporan.rekap-simpanan-export', ['search' => request('search')]) }}"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </a>
            <a href="{{ route('pengurus.laporan.rekap-simpanan-print', ['search' => request('search')]) }}"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition-colors">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
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
                    <p class="text-2xl font-bold text-gray-900">{{ count($rekapData) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-wallet text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Simpanan</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalAllSimpanan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-hand-holding-usd text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Simpanan Wajib</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalWajib, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Tagihan Wajib</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalTagihanWajib, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekap Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Detail Simpanan per Anggota</h2>
            <span class="text-sm text-gray-500">{{ count($rekapData) }} anggota</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Anggota</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Simpanan Pokok</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Simpanan Wajib</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Simpanan Modal</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Simpanan Sukarela</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Simpanan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tagihan Wajib</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rekapData as $index => $data)
                        <tr class="hover:bg-gray-50 {{ $data->tagihan_wajib > 0 ? 'bg-red-50' : '' }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $data->no_anggota }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data->nama }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">
                                Rp {{ number_format($data->simpanan_pokok, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">
                                Rp {{ number_format($data->simpanan_wajib, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">
                                Rp {{ number_format($data->simpanan_modal, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900">
                                Rp {{ number_format($data->simpanan_sukarela, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-semibold text-green-600">
                                Rp {{ number_format($data->total_simpanan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-semibold {{ $data->tagihan_wajib > 0 ? 'text-red-600' : 'text-green-600' }}">
                                @if($data->tagihan_wajib > 0)
                                    Rp {{ number_format($data->tagihan_wajib, 0, ',', '.') }}
                                    <span class="text-xs block text-gray-500">({{ $data->bulan_nunggak }} bln)</span>
                                @else
                                    Lunas
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-right text-gray-900">TOTAL</td>
                        <td class="px-4 py-3 text-right text-gray-900">Rp {{ number_format($totalPokok, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">Rp {{ number_format($totalWajib, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-gray-900">Rp {{ number_format($totalSukarela, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-600">Rp {{ number_format($totalAllSimpanan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-red-600">Rp {{ number_format($totalTagihanWajib, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Info Box -->
    @if($totalTagihanWajib > 0)
    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Masih ada tagihan simpanan wajib
                </h3>
                <p class="text-sm text-yellow-600 mt-1">
                    Total tagihan: Rp {{ number_format($totalTagihanWajib, 0, ',', '.') }}
                </p>
            </div>
            <a href="{{ route('pengurus.simpanan.create') }}"
               class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md transition-colors">
                <i class="fas fa-plus mr-2"></i>Input Pembayaran
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
