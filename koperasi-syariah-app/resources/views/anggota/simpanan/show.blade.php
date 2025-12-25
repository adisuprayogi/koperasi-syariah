@extends('layouts.app')

@section('title', 'Detail Transaksi Simpanan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Transaksi Simpanan</h1>
            <p class="text-gray-600 mt-2">Informasi lengkap transaksi simpanan</p>
        </div>
        <div>
            <a href="{{ route('anggota.simpanan.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    @if(isset($transaksi))
        <!-- Transaction Details Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-primary-500 to-primary-600">
                <h2 class="text-xl font-bold text-white">Informasi Transaksi</h2>
            </div>

            <div class="p-6">
                <!-- Transaction Code and Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kode Transaksi</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $transaksi->kode_transaksi }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Transaksi</label>
                            <p class="text-lg text-gray-900">{{ $transaksi->tanggal_transaksi->format('d F Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Periode Simpanan</label>
                            @php
                                $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            @endphp
                            <p class="text-lg text-gray-900">{{ $namaBulan[$transaksi->bulan] }} {{ $transaksi->tahun }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Simpanan</label>
                            <p class="text-lg text-gray-900">{{ $transaksi->jenisSimpanan->nama_simpanan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Transaksi</label>
                            <div class="flex items-center">
                                @if($transaksi->jenis_transaksi == 'setor')
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-down mr-2"></i>Setoran
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-up mr-2"></i>Penarikan
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah</label>
                            <p class="text-xl font-bold
                                @if($transaksi->jenis_transaksi == 'setor')
                                    text-green-600
                                @else
                                    text-red-600
                                @endif">
                                @if($transaksi->jenis_transaksi == 'setor')
                                    +Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                @else
                                    -Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Petugas</label>
                            <p class="text-lg text-gray-900">{{ $transaksi->pengurus->nama_lengkap ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="border-t pt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan</label>
                        <p class="text-gray-900">
                            @if($transaksi->keterangan)
                                {{ $transaksi->keterangan }}
                            @else
                                <span class="text-gray-400 italic">Tidak ada keterangan</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Bukti Transaksi -->
                @if($transaksi->bukti_transaksi)
                    <div class="border-t pt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-3">
                            <i class="fas fa-file-image mr-1"></i>Bukti Transaksi
                        </label>
                        @php
                            $buktiPath = $transaksi->bukti_transaksi;
                            $isImage = in_array(strtolower(pathinfo($buktiPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']);
                            $publicUrl = \Illuminate\Support\Facades\Storage::disk('public')->exists($buktiPath) ? \Illuminate\Support\Facades\Storage::url($buktiPath) : asset('storage/' . $buktiPath);
                        @endphp

                        @if($isImage)
                            <div class="border rounded-lg overflow-hidden mb-3">
                                <a href="{{ $publicUrl }}" target="_blank" class="block">
                                    <img src="{{ $publicUrl }}" alt="Bukti Transaksi" class="w-full h-auto max-h-80 object-contain bg-gray-50">
                                </a>
                            </div>
                        @endif

                        <div class="flex items-center space-x-3">
                            <a href="{{ $publicUrl }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Lihat Bukti
                            </a>
                            <a href="{{ $publicUrl }}" download
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Download
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Member Information -->
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Anggota</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                            <p class="text-gray-900">{{ $transaksi->anggota->nama_lengkap ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">No. Anggota</label>
                            <p class="text-gray-900">{{ $transaksi->anggota->no_anggota ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="border-t pt-6 mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                        <div>
                            <label class="block font-medium mb-1">Dibuat pada</label>
                            <p>{{ $transaksi->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Diperbarui pada</label>
                            <p>{{ $transaksi->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('anggota.simpanan.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                <i class="fas fa-list mr-2"></i>Lihat Semua Transaksi
            </a>

            @if($transaksi->jenis_transaksi == 'setor' && $transaksi->jenisSimpanan->nama_simpanan == 'Simpanan Sukarela')
                <!-- Tambahkan tombol untuk penarikan jika simpanan sukarela -->
                <!-- This could link to a withdrawal form if implemented -->
            @endif
        </div>
    @else
        <!-- Transaction not found -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Transaksi Tidak Ditemukan</h3>
            <p class="text-gray-500 mb-6">Transaksi yang Anda cari tidak tersedia atau telah dihapus.</p>
            <a href="{{ route('anggota.simpanan.index') }}"
               class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Transaksi
            </a>
        </div>
    @endif
</div>
@endsection