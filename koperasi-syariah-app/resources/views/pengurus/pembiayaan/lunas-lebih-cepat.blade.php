@extends('layouts.app')

@section('title', 'Lunas Lebih Cepat')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lunas Lebih Cepat</h1>
            <p class="text-gray-600 mt-2">Bayar semua sisa angsuran sekaligus</p>
        </div>
        <a href="{{ route('pengurus.pembiayaan.show', $pengajuan->id) }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Informasi Anggota -->
        <div class="bg-white rounded-lg shadow border-l-4 border-blue-500">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Informasi Anggota
                </h2>
                <div class="flex items-center mb-4">
                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $pengajuan->anggota->nama_lengkap }}</h3>
                        <p class="text-gray-500">{{ $pengajuan->anggota->no_anggota }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Kode Pembiayaan</span>
                        <span class="font-medium text-gray-900">{{ $pengajuan->kode_pengajuan }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Jenis Pembiayaan</span>
                        <span class="text-sm font-medium text-gray-900">{{ $pengajuan->jenisPembiayaan->nama_pembiayaan }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rincian Pelunasan -->
        <div class="bg-white rounded-lg shadow border-l-4 border-purple-500">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calculator mr-2 text-purple-500"></i>
                    Rincian Pelunasan
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Sisa Pokok</span>
                        <span class="font-medium">{{ 'Rp ' . number_format($sisaPokok, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Sisa Margin</span>
                        <span class="font-medium">{{ 'Rp ' . number_format($sisaMargin, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 bg-purple-50 px-3 rounded-lg">
                        <span class="text-sm font-bold text-purple-800">Total Pelunasan</span>
                        <span class="text-lg font-bold text-purple-600">{{ 'Rp ' . number_format($sisaTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500">Jumlah Angsuran</span>
                        <span class="font-medium">{{ $jumlahAngsuranPending }} periode</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Angsuran yang akan dilunasi -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-list mr-2"></i>
                Daftar Angsuran yang Akan Dilunasi ({{ $jumlahAngsuranPending }} periode)
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ke</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pokok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Margin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($angsuranPending as $angsuran)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Ke-{{ $angsuran->angsuran_ke }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $angsuran->tanggal_jatuh_tempo_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $angsuran->jumlah_pokok_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $angsuran->jumlah_margin_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $angsuran->jumlah_angsuran_formatted }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-purple-50 font-semibold">
                        <td colspan="4" class="px-6 py-4 text-right">Total:</td>
                        <td class="px-6 py-4 text-purple-600">{{ 'Rp ' . number_format($sisaTotal, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Pelunasan -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Konfirmasi Pelunasan
            </h2>
        </div>
        <div class="p-6">
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Informasi Pelunasan</h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>Dengan melunasi lebih cepat, semua {{ $jumlahAngsuranPending }} angsuran yang tersisa akan ditandai sebagai LUNAS.</p>
                            <p class="mt-1"><strong>Tidak ada perpanjangan tenor</strong> - pembiayaan langsung selesai.</p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengurus.pembiayaan.lunas_lebih_cepat.store', $pengajuan->id) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="tanggal_bayar" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-calendar mr-1"></i>
                            Tanggal Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tanggal_bayar"
                               name="tanggal_bayar" value="{{ date('Y-m-d') }}" required
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <p class="mt-1 text-sm text-gray-500">Tanggal pelunasan tidak boleh melebihi hari ini</p>
                    </div>
                    <div>
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-file-upload mr-1"></i>
                            Bukti Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="bukti_pembayaran"
                               name="bukti_pembayaran" required
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, PDF (Max 2MB)</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-comment-alt mr-1"></i>
                        Keterangan
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                              placeholder="Catatan tambahan (opsional)"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('keterangan') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Keterangan pelunasan jika diperlukan</p>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('pengurus.pembiayaan.show', $pengajuan->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-rocket mr-2"></i>
                        Lunas Lebih Cepat - {{ 'Rp ' . number_format($sisaTotal, 0, ',', '.') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
