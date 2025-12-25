@extends('layouts.app')

@section('title', 'Manajemen Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Pembiayaan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPembiayaan ?? 0 }}</p>
                </div>
                <div class="ml-4">
                    <i class="fas fa-clipboard-list text-3xl text-blue-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAktif ?? 0 }}</p>
                </div>
                <div class="ml-4">
                    <i class="fas fa-play text-3xl text-green-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Lunas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalLunas ?? 0 }}</p>
                </div>
                <div class="ml-4">
                    <i class="fas fa-check text-3xl text-indigo-500"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Nilai</p>
                    <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="ml-4">
                    <i class="fas fa-dollar-sign text-3xl text-yellow-500"></i>
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="cair" {{ request('status') == 'cair' ? 'selected' : '' }}>Cair</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembiayaan</label>
                    <select name="jenis_pembiayaan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisPembiayaans as $jenis)
                        <option value="{{ $jenis->id }}" {{ request('jenis_pembiayaan_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_pembiayaan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
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
    <div class="bg-white rounded-lg shadow">
        <div class="px-3 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pembiayaan</h2>
        </div>
        @if($pembiayaans->count() > 0)
        <!-- Mobile Responsive Table -->
        <div class="overflow-x-auto shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Informasi
                        </th>
                        <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Anggota
                        </th>
                        <th class="hidden md:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="hidden lg:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tenor
                        </th>
                        <th class="hidden xl:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="hidden 2xl:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Progress
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $no = $pembiayaans->firstItem(); @endphp
                    @foreach($pembiayaans as $pembiayaan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">{{ $no++ }}</td>
                        <td class="px-3 py-3">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $pembiayaan->kode_pengajuan }}</div>
                                <div class="text-gray-500">{{ $pembiayaan->jenisPembiayaan->nama_pembiayaan }}</div>
                                <div class="sm:hidden text-xs text-gray-400 mt-1">
                                    {{ $pembiayaan->anggota->nama_lengkap }}
                                </div>
                                <div class="md:hidden text-xs text-gray-400">
                                    {{ $pembiayaan->jumlah_pengajuan_formatted }}
                                </div>
                                <div class="lg:hidden text-xs text-gray-400">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $pembiayaan->tenor }} bulan
                                    </span>
                                </div>
                                <div class="xl:hidden mt-1">
                                    {!! $pembiayaan->status_label !!}
                                </div>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell px-3 py-3">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $pembiayaan->anggota->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500">{{ $pembiayaan->anggota->nomor_anggota }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="hidden md:table-cell px-3 py-3">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $pembiayaan->jumlah_pengajuan_formatted }}</div>
                                <div class="text-gray-500">{{ $pembiayaan->total_angsuran_formatted }}/bln</div>
                            </div>
                        </td>
                        <td class="hidden lg:table-cell px-3 py-3">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $pembiayaan->tenor }} bulan
                            </span>
                        </td>
                        <td class="hidden xl:table-cell px-3 py-3">
                            {!! $pembiayaan->status_label !!}
                        </td>
                        <td class="hidden 2xl:table-cell px-3 py-3">
                            @if($pembiayaan->angsurans->count() > 0)
                                <?php
                                $totalAngsuran = $pembiayaan->angsurans->count();
                                $totalTerbayar = $pembiayaan->angsurans->where('status', 'terbayar')->count();
                                $progress = ($totalTerbayar / $totalAngsuran) * 100;
                                ?>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ number_format($progress, 0) }}% ({{ $totalTerbayar }}/{{ $totalAngsuran }})</div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('pengurus.pembiayaan.show', $pembiayaan->id) }}"
                               class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-3 py-3 border-t border-gray-200">
            {{ $pembiayaans->links('pagination.custom') }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-hand-holding-usd text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data pembiayaan</h3>
            <p class="text-gray-500 mb-6">Belum ada pembiayaan yang aktif saat ini</p>
            <a href="{{ route('pengurus.pengajuan.index') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Lihat Pengajuan
            </a>
        </div>
        @endif
    </div>
</div>
@endsection