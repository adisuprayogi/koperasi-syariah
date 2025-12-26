@extends('layouts.app')

@section('title', 'Bayar Angsuran')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pembayaran Angsuran</h1>
            <p class="text-gray-600 mt-2">Catat pembayaran angsuran pembiayaan</p>
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
                <div class="flex items-center mb-6">
                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-2xl text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $pengajuan->anggota->nama_lengkap }}</h3>
                        <p class="text-gray-500">{{ $pengajuan->anggota->nomor_anggota }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Kode Pembiayaan</span>
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $pengajuan->kode_pengajuan }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Jenis Pembiayaan</span>
                        <span class="text-sm font-medium text-gray-900">{{ $pengajuan->jenisPembiayaan->nama_pembiayaan }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Pembayaran -->
        <div class="bg-white rounded-lg shadow border-l-4 border-green-500">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calculator mr-2 text-green-500"></i>
                    Detail Pembayaran
                </h2>
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-3">
                        <span class="text-2xl font-bold text-green-800">{{ $angsuran->angsuran_ke }}</span>
                    </div>
                    <p class="text-gray-600 mb-1">Angsuran Ke-{{ $angsuran->angsuran_ke }} dari {{ $pengajuan->tenor }}</p>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Jatuh Tempo</span>
                        <span class="font-medium {{ $angsuran->status == 'terlambat' ? 'text-red-600' : '' }}">
                            {{ $angsuran->tanggal_jatuh_tempo_formatted }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Angsuran Pokok</span>
                        <span class="font-medium">{{ $angsuran->jumlah_pokok_formatted }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Angsuran Margin</span>
                        <span class="font-medium">{{ $angsuran->jumlah_margin_formatted }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Total Angsuran</span>
                        <span class="text-lg font-bold text-blue-600">{{ $angsuran->jumlah_angsuran_formatted }}</span>
                    </div>
                                      <!-- Syariah Compliance: No denda displayed -->
                    @if($hariTerlambat > 0)
                    <div class="flex justify-between items-center py-2 bg-yellow-50 px-3 rounded-lg">
                        <span class="text-sm font-medium text-yellow-800">Keterangan</span>
                        <span class="text-sm text-yellow-700">
                            Terlambat {{ $hariTerlambat }} hari (tanpa denda)
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pembayaran -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Form Pembayaran
            </h2>
        </div>
        <div class="p-6">
            @if($angsuran->status == 'terbayar')
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Pembayaran Telah Dicatat</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Angsuran ini sudah terbayar pada tanggal {{ $angsuran->tanggal_bayar_formatted }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ route('pengurus.pembiayaan.bayar.store', [$pengajuan->id, $angsuran->id]) }}"
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
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p class="mt-1 text-sm text-gray-500">Tanggal pembayaran tidak boleh melebihi hari ini</p>
                        </div>
                        <div>
                            <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-money-check-alt mr-1"></i>
                                Jumlah Dibayar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" id="jumlah_bayar"
                                       name="jumlah_bayar" value="{{ $angsuran->jumlah_angsuran }}" required
                                       step="0.01" min="0"
                                       class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Masukkan jumlah yang dibayar anggota</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-info-circle mr-1 text-green-600"></i>
                                Informasi Syariah
                            </label>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <p class="text-sm text-green-800">
                                    <strong>Akad Syariah:</strong> Tidak ada denda keterlambatan sesuai prinsip syariah.
                                    @if($hariTerlambat > 0)
                                        <br>Angsuran terlambat {{ $hariTerlambat }} hari.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div>
                            <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fas fa-file-upload mr-1"></i>
                                Bukti Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="bukti_pembayaran"
                                   name="bukti_pembayaran" required
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <p id="file_name" class="mt-1 text-sm text-indigo-600 font-medium hidden"></p>
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
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('keterangan') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Keterangan pembayaran jika diperlukan</p>
                    </div>

                    <!-- Summary Card -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-sm text-gray-500">Jumlah Angsuran</p>
                                <p class="text-lg font-bold text-blue-600">{{ $angsuran->jumlah_angsuran_formatted }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Jumlah Pembayaran</p>
                                <p class="text-lg font-bold text-green-600" id="display-total">
                                    Rp {{ number_format($angsuran->jumlah_angsuran, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('pengurus.pembiayaan.show', $pengajuan->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pembayaran
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    @if($pengajuan->angsurans->where('status', 'terbayar')->count() > 0)
    <div class="bg-white rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-history mr-2"></i>
                Riwayat Pembayaran
            </h2>
        </div>
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full divide-y divide-gray-200" style="table-layout: fixed;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibayar oleh</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengajuan->angsurans->where('status', 'terbayar')->sortBy('angsuran_ke') as $bayar)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Ke-{{ $bayar->angsuran_ke }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bayar->tanggal_bayar_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bayar->jumlah_angsuran_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $bayar->denda > 0 ? $bayar->denda_formatted : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $bayar->total_terbayar_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $bayar->dibayarOleh->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@endsection

@section('script')
<script>
// Format currency display
document.getElementById('jumlah_bayar').addEventListener('input', function() {
    const jumlah = parseFloat(this.value) || 0;
    // Display formatted currency (no denda calculation in syariah)
    const formatted = 'Rp ' + jumlah.toLocaleString('id-ID');
    console.log('Jumlah Pembayaran:', formatted);
});

// Show file name when bukti pembayaran is selected
document.getElementById('bukti_pembayaran').addEventListener('change', function() {
    const fileName = document.getElementById('file_name');
    if (this.files && this.files[0]) {
        fileName.textContent = 'File terpilih: ' + this.files[0].name;
        fileName.classList.remove('hidden');
    } else {
        fileName.classList.add('hidden');
    }
});
</script>
@endsection