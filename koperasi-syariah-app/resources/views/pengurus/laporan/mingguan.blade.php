@extends('layouts.app')

@section('title', 'Laporan Mingguan - ' . $startDate . ' s/d ' . $endDate)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Mingguan</h1>
            <p class="text-gray-600 mt-2">
                Transaksi simpanan {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}
                s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            </p>
        </div>
        <div class="flex space-x-3">
            <form action="{{ route('pengurus.laporan.mingguan') }}" method="GET" class="flex">
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-green-500">
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="px-3 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-r-md transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <a href="{{ route('pengurus.laporan.print', 'mingguan') }}?start_date={{ $startDate }}&end_date={{ $endDate }}"
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
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-arrow-down text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Setoran</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalSetor, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-arrow-up text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Penarikan</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalTarik, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-exchange-alt text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Net Transaksi</p>
                    <p class="text-2xl font-bold {{ $netTransaksi >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($netTransaksi, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-list text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Jumlah Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $transaksi->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Summary -->
    @if($dailySummary)
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Ringkasan Harian</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($dailySummary as $daily)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-2">{{ \Carbon\Carbon::parse($daily['tanggal'])->format('d M Y') }}</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Setor:</span>
                                <span class="font-medium text-green-600">Rp {{ number_format($daily['total_setor'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tarik:</span>
                                <span class="font-medium text-red-600">Rp {{ number_format($daily['total_tarik'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t">
                                <span class="text-gray-700 font-medium">Transaksi:</span>
                                <span class="font-bold">{{ $daily['jumlah_transaksi'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Summary by Jenis Simpanan -->
    @if($summaryByJenis)
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Ringkasan per Jenis Simpanan</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($summaryByJenis as $summary)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-3">{{ $summary['jenis'] }}</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Setor:</span>
                                <span class="font-medium text-green-600">Rp {{ number_format($summary['total_setor'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tarik:</span>
                                <span class="font-medium text-red-600">Rp {{ number_format($summary['total_tarik'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t">
                                <span class="text-gray-700 font-medium">Transaksi:</span>
                                <span class="font-bold">{{ $summary['jumlah_transaksi'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Transaction List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Transaksi</h2>
            <span class="text-sm text-gray-500">{{ $transaksi->count() }} transaksi</span>
        </div>
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full divide-y divide-gray-200" style="table-layout: fixed;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Anggota
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Simpanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Saldo
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transaksi as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $t->kode_transaksi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $t->tanggal_transaksi->format('d M Y') }}
                                <div class="text-xs text-gray-500">{{ $t->tanggal_transaksi->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $t->anggota->nama_lengkap }}</div>
                                <div class="text-sm text-gray-500">{{ $t->anggota->no_anggota }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $t->jenisSimpanan->nama_simpanan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $t->jenis_transaksi == 'setor' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $t->jenis_transaksi_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $t->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $t->jenis_transaksi == 'setor' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($t->saldo_setelahnya, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('pengurus.simpanan.show', $t->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pengurus.simpanan.print', $t->id) }}?preview=1"
                                   target="_blank"
                                   class="text-green-600 hover:text-green-900 ml-2"
                                   title="Cetak">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg">Tidak ada transaksi pada periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection