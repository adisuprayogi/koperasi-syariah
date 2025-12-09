@extends('layouts.app')

@section('title', 'Laporan Neraca')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Neraca</h1>
        <p class="text-gray-600 mt-2">Laporan posisi keuangan koperasi pada tanggal tertentu</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('pengurus.laporan.neraca') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Neraca</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Tampilkan Laporan
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('pengurus.laporan.print', 'neraca') }}?{{ http_build_query(request()->query()) }}"
                       target="_blank"
                       class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Report Header -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 text-center border-b">
            <h2 class="text-2xl font-bold text-gray-900">NERACA</h2>
            <p class="text-lg text-gray-600 mt-1">Koperasi Syariah</p>
            <p class="text-md text-gray-500">Per Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
        </div>
    </div>

    <!-- Aset Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-blue-600 mb-4">
                <i class="fas fa-wallet mr-2"></i>ASET
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Kas di Bank</p>
                        <p class="text-sm text-gray-600">Total simpanan anggota yang dapat digunakan</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-blue-600">{{ number_format($totalSimpanan, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Piutang Anggota</p>
                        <p class="text-sm text-gray-600">Sisa pembiayaan yang belum dibayar oleh anggota</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-blue-600">{{ number_format($totalPiutang, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t-2 border-blue-200">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-900">TOTAL ASET</p>
                    <p class="text-lg font-bold text-blue-600">{{ number_format($totalAset, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kewajiban Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-orange-600 mb-4">
                <i class="fas fa-hand-holding-usd mr-2"></i>KEWAJIBAN
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Simpanan Anggota</p>
                        <p class="text-sm text-gray-600">Kewajiban koperasi kepada anggota</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-orange-600">{{ number_format($kewajibanSimpanan, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Kewajiban Lainnya</p>
                        <p class="text-sm text-gray-600">Kewajiban lainnya kepada pihak ketiga</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-orange-600">{{ number_format($kewajibanLainnya, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t-2 border-orange-200">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-900">TOTAL KEWAJIBAN</p>
                    <p class="text-lg font-bold text-orange-600">{{ number_format($totalKewajiban, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ekuitas Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-green-600 mb-4">
                <i class="fas fa-chart-pie mr-2"></i>EKUITAS
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Modal Awal</p>
                        <p class="text-sm text-gray-600">Modal awal koperasi</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">{{ number_format($modalAwal, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">SHU Tahun Berjalan</p>
                        <p class="text-sm text-gray-600">Sisa Hasil Usaha tahun berjalan</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">{{ number_format($shuBerjalan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t-2 border-green-200">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-900">TOTAL EKUITAS</p>
                    <p class="text-lg font-bold text-green-600">{{ number_format($totalEkuitas, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Verification -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-purple-600 mb-4">
                <i class="fas fa-balance-scale mr-2"></i>VERIFIKASI NERACA
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700 mb-1">TOTAL ASET</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($totalAset, 0, ',', '.') }}</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm text-purple-700 mb-1">TOTAL KEWAJIBAN + EKUITAS</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($totalKewajibanEkuitas, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-4 p-4 {{ abs($totalAset - $totalKewajibanEkuitas) < 1 ? 'bg-green-50' : 'bg-red-50' }} rounded-lg text-center">
                @if(abs($totalAset - $totalKewajibanEkuitas) < 1)
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <p class="text-green-700 font-semibold">NERACA SEIMBANG ✓</p>
                    <p class="text-green-600 text-sm">Selisih: {{ number_format(abs($totalAset - $totalKewajibanEkuitas), 0, ',', '.') }}</p>
                @else
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                    <p class="text-red-700 font-semibold">NERACA TIDAK SEIMBANG!</p>
                    <p class="text-red-600 text-sm">Selisih: {{ number_format(abs($totalAset - $totalKewajibanEkuitas), 0, ',', '.') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
            <div class="text-center">
                <i class="fas fa-wallet text-3xl opacity-50 mb-2"></i>
                <p class="text-blue-100 text-sm">Total Aset</p>
                <p class="text-2xl font-bold">{{ number_format($totalAset, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
            <div class="text-center">
                <i class="fas fa-hand-holding-usd text-3xl opacity-50 mb-2"></i>
                <p class="text-orange-100 text-sm">Total Kewajiban</p>
                <p class="text-2xl font-bold">{{ number_format($totalKewajiban, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
            <div class="text-center">
                <i class="fas fa-chart-pie text-3xl opacity-50 mb-2"></i>
                <p class="text-green-100 text-sm">Total Ekuitas</p>
                <p class="text-2xl font-bold">{{ number_format($totalEkuitas, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Financial Ratios -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 sm:p-6">
        <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Rasio Keuangan</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            <div>
                <p class="text-sm text-gray-600">Rasio Likuiditas</p>
                <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                    @if($totalKewajiban > 0)
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min(($totalSimpanan / $totalKewajiban) * 100, 100) }}%"></div>
                    @else
                        <div class="bg-blue-600 h-3 rounded-full" style="width: 100%"></div>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    @if($totalKewajiban > 0)
                        {{ number_format(($totalSimpanan / $totalKewajiban), 2) }}
                    @else
                        ∞
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Rasio Solvabilitas</p>
                <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                    @if($totalAset > 0)
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ min(($totalEkuitas / $totalAset) * 100, 100) }}%"></div>
                    @else
                        <div class="bg-green-600 h-3 rounded-full" style="width: 0%"></div>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    @if($totalAset > 0)
                        {{ number_format(($totalEkuitas / $totalAset) * 100, 2) }}%
                    @else
                        0%
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Rasio Piutang terhadap Aset</p>
                <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                    @if($totalAset > 0)
                        <div class="bg-purple-600 h-3 rounded-full" style="width: {{ min(($totalPiutang / $totalAset) * 100, 100) }}%"></div>
                    @else
                        <div class="bg-purple-600 h-3 rounded-full" style="width: 0%"></div>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    @if($totalAset > 0)
                        {{ number_format(($totalPiutang / $totalAset) * 100, 2) }}%
                    @else
                        0%
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection