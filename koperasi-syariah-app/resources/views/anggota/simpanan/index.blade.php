@extends('layouts.app')

@section('title', 'Data Simpanan Saya')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Riwayat Simpanan</h1>
        <p class="text-gray-600 mt-2">Lihat riwayat transaksi simpanan Anda. Untuk menambah simpanan, silakan hubungi pengurus koperasi.</p>
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

    <!-- Compact Card View Transactions -->
    <div class="space-y-4">
        <!-- Section Header -->
        <div class="bg-gradient-to-r from-primary-600 to-emerald-600 rounded-xl p-5 text-white shadow-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-2 backdrop-blur-sm">
                            <i class="fas fa-history text-sm"></i>
                        </div>
                        Riwayat Transaksi
                    </h2>
                    <p class="text-primary-100 text-xs">Semua aktivitas simpanan Anda</p>
                </div>
                @if(isset($transaksi) && $transaksi->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center">{{ $transaksi->count() }}</p>
                        <p class="text-xs text-primary-100 text-center">Transaksi</p>
                    </div>
                @endif
            </div>
        </div>

        @if(isset($transaksi) && $transaksi->count() > 0)
            <div class="space-y-3">
                @foreach($transaksi as $item)
                    <div class="group bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden border border-gray-100 hover:border-primary-200">
                        <!-- Compact Card Header -->
                        <div class="relative px-4 py-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <!-- Compact Icon -->
                                    <div class="w-10 h-10 @if($item->jenis_transaksi == 'setor') bg-gradient-to-br from-emerald-400 to-green-500 @else bg-gradient-to-br from-rose-400 to-red-500 @endif rounded-xl flex items-center justify-center shadow-md">
                                        <i class="fas @if($item->jenis_transaksi == 'setor') fa-arrow-down @else fa-arrow-up @endif text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-gray-900">{{ $item->jenisSimpanan->nama_simpanan ?? 'Transaksi' }}</p>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                            {{ $item->tanggal_transaksi->format('d M Y') }}
                                            <span class="mx-1 text-gray-300">•</span>
                                            <i class="fas fa-clock text-gray-400 mr-1"></i>
                                            {{ $item->tanggal_transaksi->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($item->jenis_transaksi == 'setor')
                                        <p class="text-lg font-bold text-green-600">+{{ number_format($item->jumlah, 0, ',', '.') }}</p>
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-plus-circle mr-1"></i>SETORAN
                                        </span>
                                    @else
                                        <p class="text-lg font-bold text-red-600">-{{ number_format($item->jumlah, 0, ',', '.') }}</p>
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-minus-circle mr-1"></i>TARIK
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Compact Card Details -->
                        <div class="px-4 pb-4 bg-gray-50/50">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Kode Transaksi -->
                                <div class="bg-white rounded-lg p-2 border border-gray-200 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-barcode text-indigo-500 mr-2 text-xs"></i>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500">Kode</p>
                                            <p class="text-xs font-mono font-bold text-gray-900">{{ $item->kode_transaksi }}</p>
                                        </div>
                                    </div>
                                    <button onclick="navigator.clipboard.writeText('{{ $item->kode_transaksi }}'); this.classList.add('text-green-600'); setTimeout(() => this.classList.remove('text-green-600'), 1000)" class="text-gray-400 hover:text-indigo-600 transition-colors text-xs p-1">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>

                                <!-- Petugas -->
                                <div class="bg-white rounded-lg p-2 border border-gray-200 flex items-center">
                                    <i class="fas fa-user-tie text-blue-500 mr-2 text-xs"></i>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold text-gray-500">Petugas</p>
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ $item->pengurus->nama_lengkap ?? '-' }}</p>
                                    </div>
                                </div>

                                <!-- Tipe -->
                                <div class="bg-white rounded-lg p-2 border border-gray-200 flex items-center">
                                    <i class="fas fa-tag text-purple-500 mr-2 text-xs"></i>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500">Tipe</p>
                                        <p class="text-xs font-bold text-gray-900">{{ $item->jenisSimpanan->tipe_simpanan_label ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            @if($item->keterangan)
                                <div class="mt-4 bg-amber-50 rounded-lg p-3 border border-amber-100">
                                    <div class="flex items-start">
                                        <i class="fas fa-sticky-note text-amber-500 mr-2 text-xs mt-0.5"></i>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-gray-500">Keterangan</p>
                                            <p class="text-xs text-gray-700 mt-1 leading-relaxed">{{ $item->keterangan }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Compact Card Footer -->
                        <div class="px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center text-xs text-gray-600">
                                        <i class="fas fa-calendar-check mr-1"></i>
                                        {{ $item->tanggal_transaksi->format('d/m/Y') }}
                                    </div>
                                    @if($item->jenis_transaksi == 'setor')
                                        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">
                                            <i class="fas fa-arrow-trend-up mr-1"></i>+Saldo
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded">
                                            <i class="fas fa-arrow-trend-down mr-1"></i>-Saldo
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 font-mono">
                                    #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $transaksi->links() }}

        @else
            <!-- Compact Empty State -->
            <div class="bg-white rounded-xl shadow-lg border-2 border-dashed border-gray-300 py-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Transaksi</h3>
                    <p class="text-gray-600 mb-6 text-sm">Transaksi simpanan Anda akan tampil di sini. Mulai lakukan setoran pertama untuk memulai.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('anggota.dashboard') }}" class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection