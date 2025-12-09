@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Laba Rugi</h1>
        <p class="text-gray-600 mt-2">Laporan keuangan untuk periode tertentu</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('pengurus.laporan.laba-rugi') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" id="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromDate(null, $i, 1)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" id="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @for($i = now()->year - 2; $i <= now()->year + 2; $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Tampilkan Laporan
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('pengurus.laporan.print', 'laba-rugi') }}?{{ http_build_query(request()->query()) }}"
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
            <h2 class="text-2xl font-bold text-gray-900">LAPORAN LABA RUGI</h2>
            <p class="text-lg text-gray-600 mt-1">Koperasi Syariah</p>
            <p class="text-md text-gray-500">Periode: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y') }}</p>
        </div>
    </div>

    <!-- Pendapatan Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-green-600 mb-4">
                <i class="fas fa-arrow-up mr-2"></i>PENDAPATAN
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Margin Pembiayaan</p>
                        <p class="text-sm text-gray-600">Pendapatan dari margin pembiayaan syariah</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">{{ number_format($marginReceived, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Pendapatan Lainnya</p>
                        <p class="text-sm text-gray-600">Pendapatan dari sumber lainnya</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-green-600">{{ number_format($otherIncome, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t-2 border-green-200">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-900">TOTAL PENDAPATAN</p>
                    <p class="text-lg font-bold text-green-600">{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Beban Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-red-600 mb-4">
                <i class="fas fa-arrow-down mr-2"></i>BEBAN
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Beban Operasional</p>
                        <p class="text-sm text-gray-600">Biaya operasional koperasi</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-red-600">{{ number_format($bebanOperasional, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Beban Administrasi</p>
                        <p class="text-sm text-gray-600">Biaya administrasi dan umum</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-red-600">{{ number_format($bebanAdministrasi, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t-2 border-red-200">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-900">TOTAL BEBAN</p>
                    <p class="text-lg font-bold text-red-600">{{ number_format($totalBeban, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SHU Calculation -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-blue-600 mb-4">
                <i class="fas fa-calculator mr-2"></i>PERHITUNGAN SHU (SISA HASIL USAHA)
            </h3>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b">
                    <p class="font-medium text-gray-900">Pendapatan Bersih sebelum Pajak</p>
                    <div class="text-right">
                        <p class="font-semibold text-blue-600">{{ number_format($shuSebelumPajak, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <p class="font-medium text-gray-900">Pajak (5%)</p>
                        <p class="text-sm text-gray-600">Pajak atas pendapatan koperasi</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-orange-600">{{ number_format($pajak, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t-4 border-blue-200">
                <div class="flex justify-between items-center">
                    <p class="text-xl font-bold text-gray-900">SHU SETELAH PAJAK</p>
                    <p class="text-2xl font-bold {{ $shuSetelahPajak >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($shuSetelahPajak, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
            <div class="text-center">
                <i class="fas fa-arrow-up text-3xl opacity-50 mb-2"></i>
                <p class="text-green-100 text-sm">Total Pendapatan</p>
                <p class="text-2xl font-bold">{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow p-6 text-white">
            <div class="text-center">
                <i class="fas fa-arrow-down text-3xl opacity-50 mb-2"></i>
                <p class="text-red-100 text-sm">Total Beban</p>
                <p class="text-2xl font-bold">{{ number_format($totalBeban, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
            <div class="text-center">
                <i class="fas fa-percentage text-3xl opacity-50 mb-2"></i>
                <p class="text-orange-100 text-sm">Pajak</p>
                <p class="text-2xl font-bold">{{ number_format($pajak, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-gradient-to-r {{ $shuSetelahPajak >= 0 ? 'from-blue-500 to-blue-600' : 'from-red-500 to-red-600' }} rounded-lg shadow p-6 text-white">
            <div class="text-center">
                <i class="fas fa-chart-line text-3xl opacity-50 mb-2"></i>
                <p class="{{ $shuSetelahPajak >= 0 ? 'text-blue-100' : 'text-red-100' }} text-sm">SHU Bersih</p>
                <p class="text-2xl font-bold">{{ number_format($shuSetelahPajak, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($bebanOperasional == 0 && $bebanAdministrasi == 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
            <div>
                <h4 class="text-lg font-semibold text-yellow-900 mb-2">Catatan Penting</h4>
                <p class="text-yellow-700 text-sm">
                    Beban operasional dan administrasi saat ini diset 0 (placeholder).
                    Silakan input data beban riil untuk mendapatkan laporan yang akurat.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Financial Indicators -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Indikator Keuangan</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Rasio Profitabilitas</p>
                <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                    @if($totalPendapatan > 0)
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ min(($shuSetelahPajak / $totalPendapatan) * 100, 100) }}%"></div>
                    @else
                        <div class="bg-green-600 h-3 rounded-full" style="width: 0%"></div>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    @if($totalPendapatan > 0)
                        {{ number_format(($shuSetelahPajak / $totalPendapatan) * 100, 2) }}%
                    @else
                        0%
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Rasio Beban terhadap Pendapatan</p>
                <div class="w-full bg-gray-200 rounded-full h-3 mt-1">
                    @if($totalPendapatan > 0)
                        <div class="bg-red-600 h-3 rounded-full" style="width: {{ min(($totalBeban / $totalPendapatan) * 100, 100) }}%"></div>
                    @else
                        <div class="bg-red-600 h-3 rounded-full" style="width: 0%"></div>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    @if($totalPendapatan > 0)
                        {{ number_format(($totalBeban / $totalPendapatan) * 100, 2) }}%
                    @else
                        0%
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection