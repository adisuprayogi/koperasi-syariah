@extends('layouts.app')

@section('title', 'Detail Transaksi Simpanan')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Transaksi Simpanan</h1>
            <p class="text-gray-600 mt-2">Informasi lengkap transaksi simpanan</p>
        </div>
        <div>
            <a href="{{ route('pengurus.simpanan.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Transaction Detail Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-500 to-purple-600">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-white">
                    <i class="fas fa-exchange-alt mr-2"></i>{{ $transaksi->kode_transaksi }}
                </h2>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                    @if($transaksi->jenis_transaksi == 'setor')
                        bg-green-100 text-green-800
                    @else
                        bg-red-100 text-red-800
                    @endif">
                    {{ $transaksi->jenis_transaksi_label }}
                </span>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column - Transaction Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Transaksi</h3>

                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Tanggal Transaksi</span>
                        <span class="text-sm text-gray-900">{{ $transaksi->tanggal_transaksi->format('d M Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Periode Simpanan</span>
                        <span class="text-sm text-gray-900">
                            @php
                                $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            @endphp
                            {{ $namaBulan[$transaksi->bulan] }} {{ $transaksi->tahun }}
                        </span>
                    </div>

                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Jenis Simpanan</span>
                        <span class="text-sm text-gray-900">{{ $transaksi->jenisSimpanan->nama_simpanan }}</span>
                    </div>

                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Jumlah</span>
                        <span class="text-lg font-bold {{ $transaksi->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaksi->jenis_transaksi == 'setor' ? '+' : '-' }} Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="py-3 bg-gray-50 rounded-lg px-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Perubahan Saldo</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Saldo Sebelumnya:</span>
                                <span class="font-medium">Rp {{ number_format($transaksi->saldo_sebelumnya, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Saldo Setelahnya:</span>
                                <span class="font-bold text-lg">Rp {{ number_format($transaksi->saldo_setelahnya, 0, ',', '.') }}</span>
                            </div>
                            @if($transaksi->jenis_transaksi == 'setor')
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Kenaikan Saldo:</span>
                                    <span class="font-bold text-green-600">+Rp {{ number_format($transaksi->saldo_setelahnya - $transaksi->saldo_sebelumnya, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($transaksi->keterangan)
                        <div class="py-3">
                            <span class="text-sm font-medium text-gray-500">Keterangan</span>
                            <p class="text-sm text-gray-900 mt-1">{{ $transaksi->keterangan }}</p>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Anggota & Verification Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Anggota</h3>

                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-16 w-16 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600 text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">{{ $transaksi->anggota->nama_lengkap }}</h4>
                                <p class="text-sm text-gray-500">{{ $transaksi->anggota->no_anggota }}</p>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                    <span><i class="fas fa-phone mr-1"></i>{{ $transaksi->anggota->no_hp }}</span>
                                    <span><i class="fas fa-envelope mr-1"></i>{{ $transaksi->anggota->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-4 mt-6">Status Verifikasi</h3>

                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($transaksi->status == 'verified')
                                        bg-green-100 text-green-800
                                    @elseif($transaksi->status == 'pending')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    {{ $transaksi->status_label }}
                                </span>
                                @if($transaksi->verified_at)
                                    <p class="text-sm text-gray-600 mt-2">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Terverifikasi pada {{ $transaksi->verified_at->format('d M Y H:i') }}
                                    </p>
                                @endif
                            </div>
                            @if($transaksi->pengurus)
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">Diverifikasi oleh:</p>
                                    <p class="text-sm text-gray-600">{{ $transaksi->pengurus->nama_lengkap }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaksi->pengurus->jabatan ?? 'Pengurus' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($transaksi->catatan_verifikasi)
                        <div class="py-3">
                            <span class="text-sm font-medium text-gray-500">Catatan Verifikasi</span>
                            <p class="text-sm text-gray-900 mt-1">{{ $transaksi->catatan_verifikasi }}</p>
                        </div>
                    @endif

                    @if($transaksi->bukti_transaksi)
                        <div class="py-3">
                            <span class="text-sm font-medium text-gray-500">Bukti Transaksi</span>
                            <div class="mt-2">
                                @php
                                    $buktiPath = $transaksi->bukti_transaksi;
                                    $isImage = in_array(strtolower(pathinfo($buktiPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                                    $publicUrl = Storage::disk('public')->exists($buktiPath) ? Storage::url($buktiPath) : asset('storage/' . $buktiPath);
                                @endphp

                                @if($isImage)
                                    <div class="border rounded-lg overflow-hidden">
                                        <a href="{{ $publicUrl }}" target="_blank" class="block">
                                            <img src="{{ $publicUrl }}" alt="Bukti Transaksi" class="w-full h-auto max-h-64 object-contain bg-gray-50">
                                        </a>
                                    </div>
                                @endif

                                <div class="mt-2 flex items-center space-x-3">
                                    <a href="{{ $publicUrl }}" target="_blank"
                                       class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Lihat Bukti
                                    </a>
                                    <a href="{{ $publicUrl }}" download
                                       class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <i class="fas fa-download mr-2"></i>
                                        Download
                                    </a>
                                </div>

                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ $isImage ? 'Klik gambar untuk memperbesar' : 'Klik untuk melihat/download file' }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Transactions -->
    @if($relatedTransaksi->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-history mr-2"></i>Riwayat Transaksi
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Transaksi sebelumnya untuk {{ $transaksi->anggota->nama_lengkap }} pada {{ $transaksi->jenisSimpanan->nama_simpanan }}
                </p>
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
                                Jenis
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Saldo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Pengurus
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($relatedTransaksi as $t)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $t->kode_transaksi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $t->tanggal_transaksi->format('d M Y') }}
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $t->pengurus ? $t->pengurus->nama_lengkap : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('pengurus.simpanan.show', $t->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="mt-6 flex justify-end space-x-3">
        <a href="{{ route('pengurus.simpanan.print', $transaksi->id) }}?preview=1"
           target="_blank"
           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-file-pdf mr-2"></i>Cetak PDF
        </a>
        <a href="{{ route('pengurus.simpanan.create') }}?anggota_id={{ $transaksi->anggota_id }}&jenis_simpanan_id={{ $transaksi->jenis_simpanan_id }}"
           class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Transaksi Baru
        </a>
    </div>
</div>
@endsection