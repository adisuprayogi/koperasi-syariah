@extends('layouts.app')

@section('title', 'Data Pembiayaan Saya')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Data Pembiayaan Saya</h1>
            <p class="text-gray-600 mt-2">Lihat status dan detail pembiayaan Anda</p>
        </div>
        <div>
            <a href="{{ route('anggota.pengajuan.create') }}"
               class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Ajukan Pembiayaan
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Total Pembiayaan -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Pinjaman</h3>
                    <p class="text-lg font-bold text-blue-600">
                        {{ number_format($totalPinjaman, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sisa Pinjaman -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Sisa Pinjaman</h3>
                    <p class="text-lg font-bold text-orange-600">
                        {{ number_format($sisaPinjaman, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Angsuran Terbayar -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Angsuran Terbayar</h3>
                    <p class="text-lg font-bold text-green-600">
                        {{ $angsuranTerbayar }} / {{ $totalAngsuran }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Pembiayaan Aktif</h3>
                    <p class="text-lg font-bold text-purple-600">
                        {{ $pembiayaanAktif }} Buah
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h2>
        <form action="{{ route('anggota.pembiayaan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembiayaan</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="pengajuan" {{ request('status') == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembiayaan</label>
                <select name="jenis_pembiayaan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Jenis</option>
                    @if(isset($jenisPembiayaan))
                        @foreach($jenisPembiayaan as $jp)
                            <option value="{{ $jp->id }}" {{ request('jenis_pembiayaan_id') == $jp->id ? 'selected' : '' }}>
                                {{ $jp->nama_pembiayaan }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="md:col-span-3">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('anggota.pembiayaan.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table View Pembiayaan -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-list-alt mr-2 text-primary-600"></i>
                        Daftar Pembiayaan
                    </h2>
                    <p class="text-gray-500 text-xs">Semua pengajuan dan pembiayaan Anda</p>
                </div>
                @if(isset($pembiayaan) && $pembiayaan->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $pembiayaan->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Pembiayaan</p>
                    </div>
                @endif
            </div>
        </div>

        @if(isset($pembiayaan) && $pembiayaan->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plafond</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Margin</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tenor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pembiayaan as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-4 py-3">
                                    @if($item->created_at)
                                        <div class="text-sm font-medium text-gray-900">{{ $item->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}</div>
                                    @else
                                        <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>

                                <!-- Kode -->
                                <td class="px-4 py-3">
                                    <div class="text-xs font-mono font-semibold text-indigo-600">{{ $item->kode_pengajuan }}</div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->jenisPembiayaan->nama_pembiayaan ?? '-' }}</div>
                                </td>

                                <!-- Plafond -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-blue-600">Rp {{ number_format($item->jumlah_pengajuan, 0, ',', '.') }}</div>
                                </td>

                                <!-- Margin -->
                                <td class="px-4 py-3">
                                    @if($item->jenisPembiayaan && $item->jenisPembiayaan->tipe_margin == 'flat')
                                        <div class="text-sm text-orange-600">{{ $item->jenisPembiayaan->margin }}%</div>
                                    @else
                                        <div class="text-sm font-bold text-orange-600">Rp {{ number_format($item->jumlah_margin, 0, ',', '.') }}</div>
                                    @endif
                                </td>

                                <!-- Total -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-gray-900">Rp {{ number_format($item->jumlah_pengajuan + $item->jumlah_margin, 0, ',', '.') }}</div>
                                </td>

                                <!-- Tenor -->
                                <td class="px-4 py-3 text-center">
                                    <div class="text-sm text-gray-900">{{ $item->tenor }} Bln</div>
                                    @if($item->status == 'aktif' && $item->pembiayaan)
                                        <div class="text-xs text-gray-500">{{ $item->pembiayaan->angsuran_dibayarkan ?? 0 }}/{{ $item->tenor }}</div>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    @switch($item->status)
                                        @case('pengajuan')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pengajuan
                                            </span>
                                            @break
                                        @case('disetujui')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-1"></i>Disetujui
                                            </span>
                                            @break
                                        @case('ditolak')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Ditolak
                                            </span>
                                            @break
                                        @case('aktif')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-play-circle mr-1"></i>Aktif
                                            </span>
                                            @break
                                        @case('lunas')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                <i class="fas fa-check-double mr-1"></i>Lunas
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $item->status }}
                                            </span>
                                    @endswitch
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('anggota.pembiayaan.show', $item->id) }}"
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
                {{ $pembiayaan->links('pagination.custom') }}
            </div>

        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-usd text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pembiayaan</h3>
                    <p class="text-gray-600 mb-6 text-sm">Ajukan pembiayaan sekarang untuk kebutuhan Anda</p>
                    <a href="{{ route('anggota.pengajuan.create') }}" class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Ajukan Pembiayaan
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection