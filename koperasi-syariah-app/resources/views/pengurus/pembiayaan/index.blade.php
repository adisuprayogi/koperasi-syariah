@extends('layouts.app')

@section('title', 'Manajemen Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Kelola pembiayaan yang telah dicairkan</p>
        </div>
        <a href="{{ route('pengurus.dashboard') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $totalPembiayaan ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-play text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Aktif</h3>
                    <p class="text-lg font-bold text-green-600">{{ $totalAktif ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-indigo-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Lunas</h3>
                    <p class="text-lg font-bold text-indigo-600">{{ $totalLunas ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Nilai</h3>
                    <p class="text-lg font-bold text-yellow-600">{{ number_format($totalNilai ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h2>
            <form action="{{ route('pengurus.pembiayaan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Data</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode atau Nama Anggota"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Status</option>
                        <option value="cair" {{ request('status') == 'cair' ? 'selected' : '' }}>Cair</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembiayaan</label>
                    <select name="jenis_pembiayaan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPembiayaans as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('jenis_pembiayaan_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_pembiayaan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                    <a href="{{ route('pengurus.pembiayaan.index') }}"
                       class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-list-alt mr-2 text-primary-600"></i>
                        Daftar Pembiayaan
                    </h2>
                    <p class="text-gray-500 text-xs">Semua pembiayaan yang aktif</p>
                </div>
                @if($pembiayaans->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $pembiayaans->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Pembiayaan</p>
                    </div>
                @endif
            </div>
        </div>

        @if($pembiayaans->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plafond</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tenor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Angsuran</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $no = $pembiayaans->firstItem(); @endphp
                        @foreach($pembiayaans as $pembiayaan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- No -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $no++ }}</div>
                                </td>

                                <!-- Kode -->
                                <td class="px-4 py-3">
                                    <div class="text-xs font-mono font-semibold text-indigo-600">{{ $pembiayaan->kode_pengajuan }}</div>
                                </td>

                                <!-- Anggota -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                                                <i class="fas fa-user text-primary-600 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900">{{ $pembiayaan->anggota->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-500">{{ $pembiayaan->anggota->nomor_anggota ?? $pembiayaan->anggota->no_anggota }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $pembiayaan->jenisPembiayaan->nama_pembiayaan }}</div>
                                </td>

                                <!-- Plafond -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-blue-600">{{ $pembiayaan->jumlah_pengajuan_formatted }}</div>
                                    @if($pembiayaan->total_angsuran)
                                        <div class="text-xs text-gray-500">{{ number_format($pembiayaan->total_angsuran, 0, ',', '.') }}/bln</div>
                                    @endif
                                </td>

                                <!-- Tenor -->
                                <td class="px-4 py-3 text-center">
                                    <div class="text-sm text-gray-900">{{ $pembiayaan->tenor }} Bln</div>
                                </td>

                                <!-- Angsuran -->
                                <td class="px-4 py-3">
                                    @if($pembiayaan->angsurans->count() > 0)
                                        @php
                                            // Hitung progress berdasarkan NOMINAL yang dibayar, bukan periode
                                            $totalHarusDibayar = $pembiayaan->jumlah_pengajuan + $pembiayaan->jumlah_margin;
                                            $totalDibayar = $pembiayaan->totalDibayar();
                                            $progress = ($totalDibayar / $totalHarusDibayar) * 100;

                                            // Hitung periode info
                                            $totalAngsuran = $pembiayaan->angsurans->count();
                                            $periodeLunas = $pembiayaan->periodeLunas();
                                            $periodePending = $pembiayaan->periodePending();

                                            // Tentukan warna berdasarkan progress & status
                                            // Jika status lunas, langsung hijau
                                            if ($pembiayaan->status === 'lunas') {
                                                $progressColor = 'bg-green-600';
                                            } elseif ($progress >= 99.5) {
                                                // Progress 99.5%+ dianggap hampir lunas
                                                $progressColor = 'bg-green-600';
                                            } elseif ($progress >= 50) {
                                                $progressColor = 'bg-blue-600';
                                            } elseif ($progress >= 25) {
                                                $progressColor = 'bg-yellow-600';
                                            } else {
                                                $progressColor = 'bg-red-600';
                                            }
                                        @endphp
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="{{ $progressColor }} h-2 rounded-full transition-all" style="width: {{ min($progress, 100) }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ number_format($progress, 0) }}%
                                            <span class="text-gray-400">({{ $periodeLunas }}/{{ $totalAngsuran }} periode)</span>
                                            @if($periodePending > 0)
                                                <span class="text-yellow-600 font-medium"> +{{ $periodePending }} pending</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $pembiayaan->totalDibayar_formatted }} / {{ $pembiayaan->jumlah_pengajuan_formatted }}
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    @if($pembiayaan->status == 'cair')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-play-circle mr-1"></i>Aktif
                                        </span>
                                    @elseif($pembiayaan->status == 'lunas')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-check-circle mr-1"></i>Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $pembiayaan->status }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('pengurus.pembiayaan.show', $pembiayaan->id) }}"
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
                {{ $pembiayaans->links('pagination.custom') }}
            </div>

        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-usd text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pembiayaan</h3>
                    <p class="text-gray-600 mb-6 text-sm">Belum ada pembiayaan yang aktif saat ini</p>
                    <a href="{{ route('pengurus.pengajuan.index') }}"
                       class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Lihat Pengajuan
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
