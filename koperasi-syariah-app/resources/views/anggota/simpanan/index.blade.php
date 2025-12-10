@extends('layouts.app')

@section('title', 'Data Simpanan Saya')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Data Simpanan Saya</h1>
        <p class="text-gray-600 mt-2">Lihat riwayat transaksi simpanan Anda</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Simpanan Wajib -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-piggy-bank text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Simpanan Wajib</h3>
                    <p class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($totalSimpananWajib, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Simpanan Sukarela -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Simpanan Sukarela</h3>
                    <p class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($totalSimpananSukarela, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Simpanan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-wallet text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Simpanan</h3>
                    <p class="text-2xl font-bold text-purple-600">
                        Rp {{ number_format($totalSimpananWajib + $totalSimpananSukarela, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Transaksi</h2>
        <form action="{{ route('anggota.simpanan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Simpanan</label>
                <select name="jenis_simpanan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Jenis</option>
                    @if(isset($jenisSimpanan))
                        @foreach($jenisSimpanan as $js)
                            <option value="{{ $js->id }}" {{ request('jenis_simpanan_id') == $js->id ? 'selected' : '' }}>
                                {{ $js->nama_simpanan }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="md:col-span-3">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('anggota.simpanan.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Riwayat Transaksi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode Transaksi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Simpanan
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                            Jenis Transaksi
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Jumlah
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-w">
                            Keterangan
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">
                            Petugas
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($transaksi) && $transaksi->count() > 0)
                        @foreach($transaksi as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->tanggal_transaksi->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item->kode_transaksi }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->jenisSimpanan->nama_simpanan ?? '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    @if($item->jenis_transaksi == 'setor')
                                        <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-arrow-down mr-1"></i>Setoran
                                        </span>
                                    @else
                                        <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-arrow-up mr-1"></i>Penarikan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-right">
                                    @if($item->jenis_transaksi == 'setor')
                                        <span class="text-green-600 font-semibold">+Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-red-600 font-semibold">-Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $item->keterangan ?? '-' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->pengurus->nama_lengkap ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">Belum ada data transaksi</p>
                                    <p class="text-gray-400 text-sm mt-1">Transaksi simpanan Anda akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if(isset($transaksi) && $transaksi->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transaksi->links() }}
            </div>
        @endif
    </div>
</div>
@endsection