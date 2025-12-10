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
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Pembiayaan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Total Pinjaman</h3>
                    <p class="text-2xl font-bold text-blue-600">
                        Rp {{ number_format($totalPinjaman, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sisa Pinjaman -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Sisa Pinjaman</h3>
                    <p class="text-2xl font-bold text-orange-600">
                        Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Angsuran Terbayar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Angsuran Terbayar</h3>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $angsuranTerbayar }} / {{ $totalAngsuran }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Pembiayaan Aktif</h3>
                    <p class="text-2xl font-bold text-purple-600">
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

    <!-- Financing Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pembiayaan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Pembiayaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Plafond
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Margin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Pinjaman
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            Tenor
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                            Status
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($pembiayaan) && $pembiayaan->count() > 0)
                        @foreach($pembiayaan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item->kode_pembiayaan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->jenisPembiayaan->nama_pembiayaan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($item->plafond, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($item->margin, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($item->plafond + $item->margin, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    {{ $item->tenor }} Bulan
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    @switch($item->status)
                                        @case('pengajuan')
                                            <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pengajuan
                                            </span>
                                            @break
                                        @case('disetujui')
                                            <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-check mr-1"></i>Disetujui
                                            </span>
                                            @break
                                        @case('ditolak')
                                            <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i>Ditolak
                                            </span>
                                            @break
                                        @case('aktif')
                                            <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-play mr-1"></i>Aktif
                                            </span>
                                            @break
                                        @case('lunas')
                                            <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                <i class="fas fa-check-circle mr-1"></i>Lunas
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $item->status }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <a href="{{ route('anggota.pembiayaan.show', $item->id) }}"
                                       class="text-primary-600 hover:text-primary-900 inline-flex items-center">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-hand-holding-usd text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-500 text-lg">Belum ada data pembiayaan</p>
                                    <p class="text-gray-400 text-sm mt-1">Ajukan pembiayaan sekarang untuk memulai</p>
                                    <a href="{{ route('anggota.pengajuan.create') }}"
                                       class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                                        <i class="fas fa-plus mr-2"></i>
                                        Ajukan Pembiayaan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if(isset($pembiayaan) && $pembiayaan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pembiayaan->links() }}
            </div>
        @endif
    </div>
</div>
@endsection