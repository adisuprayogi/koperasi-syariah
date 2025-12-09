@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Laporan</h1>
        <p class="text-gray-600 mt-2">Pilih jenis laporan yang ingin dilihat</p>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Laporan Harian -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Laporan Harian</h3>
                </div>
                <p class="text-gray-600 mb-4">Lihat semua transaksi simpanan per hari</p>
                <form action="{{ route('pengurus.laporan.harian') }}" method="GET" class="mt-4">
                    <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        <i class="fas fa-eye mr-2"></i>Lihat Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Mingguan -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-calendar-week text-green-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Laporan Mingguan</h3>
                </div>
                <p class="text-gray-600 mb-4">Lihat semua transaksi simpanan per minggu</p>
                <form action="{{ route('pengurus.laporan.mingguan') }}" method="GET" class="mt-4">
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <input type="date" name="start_date" value="{{ now()->startOfWeek()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <input type="date" name="end_date" value="{{ now()->endOfWeek()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        <i class="fas fa-eye mr-2"></i>Lihat Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Bulanan -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Laporan Bulanan</h3>
                </div>
                <p class="text-gray-600 mb-4">Lihat semua transaksi simpanan per bulan</p>
                <form action="{{ route('pengurus.laporan.bulanan') }}" method="GET" class="mt-4">
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>
                                    {{ now()->month($i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                        <input type="number" name="tahun" value="{{ now()->year }}" min="2020" max="2030"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        <i class="fas fa-eye mr-2"></i>Lihat Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Simpanan Wajib -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <i class="fas fa-file-invoice-dollar text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Simpanan Wajib</h3>
                </div>
                <p class="text-gray-600 mb-4">Lihat status pembayaran simpanan wajib per bulan</p>
                <form action="{{ route('pengurus.laporan.simpanan-wajib') }}" method="GET" class="mt-4">
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>
                                    {{ now()->month($i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                        <input type="number" name="tahun" value="{{ now()->year }}" min="2020" max="2030"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        <i class="fas fa-eye mr-2"></i>Lihat Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Laporan Simpanan per Anggota -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-teal-100 rounded-full">
                        <i class="fas fa-users text-teal-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Simpanan per Anggota</h3>
                </div>
                <p class="text-gray-600 mb-4">Lihat detail simpanan untuk anggota tertentu</p>
                <a href="{{ route('pengurus.laporan.simpanan-per-anggota') }}" class="block w-full bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Pembiayaan per Anggota -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-cyan-100 rounded-full">
                        <i class="fas fa-hand-holding-usd text-cyan-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Pembiayaan per Anggota</h3>
                </div>
                <p class="text-gray-600 mb-4">Lihat status pembiayaan untuk anggota tertentu</p>
                <a href="{{ route('pengurus.laporan.pembiayaan-per-anggota') }}" class="block w-full bg-cyan-600 hover:bg-cyan-700 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Laba Rugi -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-emerald-100 rounded-full">
                        <i class="fas fa-chart-line text-emerald-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Laba Rugi</h3>
                </div>
                <p class="text-gray-600 mb-4">Laporan pendapatan dan beban per periode</p>
                <a href="{{ route('pengurus.laporan.laba-rugi') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Neraca -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-slate-100 rounded-full">
                        <i class="fas fa-balance-scale text-slate-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Neraca</h3>
                </div>
                <p class="text-gray-600 mb-4">Laporan posisi keuangan koperasi</p>
                <a href="{{ route('pengurus.laporan.neraca') }}" class="block w-full bg-slate-600 hover:bg-slate-700 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Quick Stats</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Transaksi Hari Ini</span>
                        <span class="font-semibold text-gray-900">{{ \App\Models\TransaksiSimpanan::whereDate('tanggal_transaksi', today())->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Anggota Aktif</span>
                        <span class="font-semibold text-gray-900">{{ \App\Models\Anggota::where('status_keanggotaan', 'aktif')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Simpanan</span>
                        <span class="font-semibold text-gray-900">Rp {{ number_format(\App\Models\TransaksiSimpanan::where('jenis_transaksi', 'setor')->sum('jumlah'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-gray-100 rounded-full">
                        <i class="fas fa-cog text-gray-600 text-xl"></i>
                    </div>
                    <h3 class="ml-4 text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('pengurus.simpanan.create') }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        <i class="fas fa-plus mr-2"></i>Input Transaksi
                    </a>
                    <button onclick="window.open('generate:wajib')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        <i class="fas fa-sync mr-2"></i>Generate Simpanan Wajib
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-fill end date when start date changes (for weekly report)
    document.querySelector('input[name="start_date"]').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 6);

        const endDateInput = document.querySelector('input[name="end_date"]');
        endDateInput.value = endDate.toISOString().split('T')[0];
    });
</script>
@endsection