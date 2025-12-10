@extends('layouts.app')

@section('title', 'Detail Pembiayaan')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Informasi lengkap pembiayaan dan jadwal angsuran</p>
        </div>
        <div>
            <a href="{{ route('anggota.pembiayaan.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    @if(isset($pembiayaan))
        <!-- Financing Details Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600">
                <h2 class="text-xl font-bold text-white">Informasi Pembiayaan</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kode Pembiayaan</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $pembiayaan->kode_pembiayaan }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Pembiayaan</label>
                            <p class="text-lg text-gray-900">{{ $pembiayaan->jenisPembiayaan->nama_pembiayaan ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Pengajuan</label>
                            <p class="text-lg text-gray-900">{{ $pembiayaan->tanggal_pengajuan->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Plafond</label>
                            <p class="text-lg font-semibold text-blue-600">Rp {{ number_format($pembiayaan->plafond, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Margin</label>
                            <p class="text-lg font-semibold text-orange-600">Rp {{ number_format($pembiayaan->margin, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Total Pinjaman</label>
                            <p class="text-xl font-bold text-purple-600">Rp {{ number_format($pembiayaan->plafond + $pembiayaan->margin, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tenor</label>
                            <p class="text-lg text-gray-900">{{ $pembiayaan->tenor }} Bulan</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Angsuran per Bulan</label>
                            <p class="text-lg font-semibold text-green-600">Rp {{ number_format(($pembiayaan->plafond + $pembiayaan->margin) / $pembiayaan->tenor, 0, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            <div class="flex items-center">
                                @switch($pembiayaan->status)
                                    @case('pengajuan')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-2"></i>Pengajuan
                                        </span>
                                        @break
                                    @case('disetujui')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-check mr-2"></i>Disetujui
                                        </span>
                                        @break
                                    @case('ditolak')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-2"></i>Ditolak
                                        </span>
                                        @break
                                    @case('aktif')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-play mr-2"></i>Aktif
                                        </span>
                                        @break
                                    @case('lunas')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-check-circle mr-2"></i>Lunas
                                        </span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $pembiayaan->status }}
                                        </span>
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($pembiayaan->keterangan)
                    <div class="border-t pt-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
                            <p class="text-gray-900">{{ $pembiayaan->keterangan }}</p>
                        </div>
                    </div>
                @endif

                <!-- Additional Information -->
                @if($pembiayaan->verified_at || $pembiayaan->approved_at || $pembiayaan->tanggal_cair)
                    <div class="border-t pt-6 mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            @if($pembiayaan->verified_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Terverifikasi pada</label>
                                    <p class="text-gray-900">{{ $pembiayaan->verified_at->format('d F Y, H:i') }}</p>
                                    <p class="text-gray-600">Oleh: {{ $pembiayaan->verifiedBy->nama_lengkap ?? '-' }}</p>
                                </div>
                            @endif
                            @if($pembiayaan->approved_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Disetujui pada</label>
                                    <p class="text-gray-900">{{ $pembiayaan->approved_at->format('d F Y, H:i') }}</p>
                                    <p class="text-gray-600">Oleh: {{ $pembiayaan->approvedBy->nama_lengkap ?? '-' }}</p>
                                </div>
                            @endif
                            @if($pembiayaan->tanggal_cair)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Dicairkan pada</label>
                                    <p class="text-gray-900">{{ $pembiayaan->tanggal_cair->format('d F Y, H:i') }}</p>
                                    <p class="text-gray-600">Oleh: {{ $pembiayaan->pencair->pengurus->nama_lengkap ?? $pembiayaan->pencair->name ?? '-' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Installment Schedule -->
        @if($pembiayaan->status == 'aktif' && isset($angsurans) && $angsurans->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Jadwal Angsuran</h2>
                    <div class="text-sm text-gray-500">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                              @if($angsurans->where('status', 'lunas')->count() == $angsurans->count())
                                  bg-green-100 text-green-800
                              @else
                                  bg-blue-100 text-blue-800
                              @endif">
                            {{ $angsurans->where('status', 'lunas')->count() }} dari {{ $angsurans->count() }} Angsuran Lunas
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No.
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jatuh Tempo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Bayar
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($angsurans as $angsuran)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $angsuran->angsuran_ke }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $angsuran->tanggal_jatuh_tempo->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($angsuran->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($angsuran->status == 'lunas')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Lunas
                                            </span>
                                        @else
                                            @if($angsuran->tanggal_jatuh_tempo->isPast())
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i>Belum Bayar
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $angsuran->tanggal_bayar ? $angsuran->tanggal_bayar->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $angsuran->keterangan ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Angsuran Lunas</h3>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $angsurans->where('status', 'lunas')->count() }} / {{ $angsurans->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Sisa Angsuran</h3>
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $angsurans->where('status', '!=', 'lunas')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calculator text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Sisa Pinjaman</h3>
                            <p class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($angsurans->where('status', '!=', 'lunas')->sum('jumlah'), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @else
        <!-- Financing not found -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Pembiayaan Tidak Ditemukan</h3>
            <p class="text-gray-500 mb-6">Pembiayaan yang Anda cari tidak tersedia atau telah dihapus.</p>
            <a href="{{ route('anggota.pembiayaan.index') }}"
               class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Pembiayaan
            </a>
        </div>
    @endif
</div>
@endsection