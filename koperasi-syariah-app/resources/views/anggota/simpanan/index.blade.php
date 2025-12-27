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
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <!-- Simpanan Pokok -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Pokok</h3>
                    <p class="text-lg font-bold text-yellow-600">
                        {{ number_format($totalSimpananPokok, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Simpanan Wajib -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-piggy-bank text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Wajib</h3>
                    <p class="text-lg font-bold text-green-600">
                        {{ number_format($totalSimpananWajib, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Simpanan Sukarela -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Sukarela</h3>
                    <p class="text-lg font-bold text-blue-600">
                        {{ number_format($totalSimpananSukarela, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Simpanan Modal -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-briefcase text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Modal</h3>
                    <p class="text-lg font-bold text-orange-600">
                        {{ number_format($totalSimpananModal, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Simpanan -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-lg shadow-lg p-4 border-2 border-indigo-700">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-wallet text-indigo-800"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-semibold text-indigo-100">Total Simpanan</h3>
                    <p class="text-lg font-bold text-yellow-300">
                        {{ number_format($totalSimpananPokok + $totalSimpananWajib + $totalSimpananSukarela + $totalSimpananModal, 0, ',', '.') }}
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

    <!-- Table View Transactions -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-history mr-2 text-primary-600"></i>
                        Riwayat Transaksi
                    </h2>
                    <p class="text-gray-500 text-xs">Semua aktivitas simpanan Anda</p>
                </div>
                @if(isset($transaksi) && $transaksi->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $transaksi->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Transaksi</p>
                    </div>
                @endif
            </div>
        </div>

        @if(isset($transaksi) && $transaksi->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Saldo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Petugas</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($transaksi as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->tanggal_transaksi->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->tanggal_transaksi->format('H:i') }}</div>
                                </td>

                                <!-- Kode -->
                                <td class="px-4 py-3">
                                    <div class="text-xs font-mono font-semibold text-indigo-600">{{ $item->kode_transaksi }}</div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $item->jenisSimpanan->nama_simpanan ?? '-' }}</p>
                                        <div class="flex items-center mt-1">
                                            @if($item->jenis_transaksi == 'setor')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-plus-circle mr-1"></i>SETOR
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-minus-circle mr-1"></i>TARIK
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Periode -->
                                <td class="px-4 py-3">
                                    @php
                                        $namaBulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                    @endphp
                                    <div class="text-sm text-gray-900">{{ $namaBulan[$item->bulan] ?? '-' }} {{ $item->tahun }}</div>
                                </td>

                                <!-- Jumlah -->
                                <td class="px-4 py-3">
                                    @if($item->jenis_transaksi == 'setor')
                                        <p class="text-sm font-bold text-green-600">+Rp {{ number_format($item->jumlah, 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-sm font-bold text-red-600">-Rp {{ number_format($item->jumlah, 0, ',', '.') }}</p>
                                    @endif
                                </td>

                                <!-- Saldo -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-gray-900">Rp {{ number_format($item->saldo_setelahnya, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">Before: {{ number_format($item->saldo_sebelumnya, 0, ',', '.') }}</div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        @if($item->status == 'verified')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                                            </span>
                                        @elseif($item->status == 'pending')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Ditolak
                                            </span>
                                        @endif
                                        @if($item->bukti_transaksi)
                                            <a href="{{ asset('storage/' . $item->bukti_transaksi) }}" target="_blank" class="ml-2 text-indigo-500 hover:text-indigo-700" title="Lihat bukti">
                                                <i class="fas fa-file-image"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <!-- Petugas -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $item->pengurus->nama_lengkap ?? '-' }}</div>
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('anggota.simpanan.show', $item->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 transition-colors"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $transaksi->links('pagination.custom') }}
            </div>

        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
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