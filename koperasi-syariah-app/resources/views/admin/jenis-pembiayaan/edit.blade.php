@extends('layouts.app')

@section('title', 'Edit Jenis Pembiayaan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-gray-900">
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('admin.jenis-pembiayaan.index') }}" class="text-gray-700 hover:text-gray-900">
                        Jenis Pembiayaan
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Edit</span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-3xl font-bold text-gray-900 mt-4">Edit Jenis Pembiayaan</h1>
    <p class="text-gray-600 mt-2">Update jenis pembiayaan: {{ $jenisPembiayaan->nama_pembiayaan }}</p>
</div>

<form action="{{ route('admin.jenis-pembiayaan.update', $jenisPembiayaan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Ada {{ $errors->count() }} kesalahan</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <!-- Informasi Umum -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-wallet mr-2"></i>Informasi Umum
            </h3>
        </div>
        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="kode_jenis" class="block text-sm font-medium text-gray-700">Kode Jenis</label>
                    <input type="text" id="kode_jenis" name="kode_jenis" required maxlength="10"
                           value="{{ old('kode_jenis', $jenisPembiayaan->kode_jenis) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Contoh: PM001">
                    <p class="text-xs text-gray-500 mt-1">Kode unik untuk jenis pembiayaan (maks. 10 karakter)</p>
                </div>
                <div>
                    <label for="nama_pembiayaan" class="block text-sm font-medium text-gray-700">Nama Pembiayaan</label>
                    <input type="text" id="nama_pembiayaan" name="nama_pembiayaan" required
                           value="{{ old('nama_pembiayaan', $jenisPembiayaan->nama_pembiayaan) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Contoh: Pembiayaan Motor Murabahah">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tipe_pembiayaan" class="block text-sm font-medium text-gray-700">Tipe Pembiayaan</label>
                    <select id="tipe_pembiayaan" name="tipe_pembiayaan" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Pilih Tipe Pembiayaan</option>
                        <option value="murabahah" {{ old('tipe_pembiayaan', $jenisPembiayaan->tipe_pembiayaan) == 'murabahah' ? 'selected' : '' }}>Murabahah (Jual Beli)</option>
                        <option value="mudharabah" {{ old('tipe_pembiayaan', $jenisPembiayaan->tipe_pembiayaan) == 'mudharabah' ? 'selected' : '' }}>Mudharabah (Bagi Hasil)</option>
                        <option value="musyarakah" {{ old('tipe_pembiayaan', $jenisPembiayaan->tipe_pembiayaan) == 'musyarakah' ? 'selected' : '' }}>Musyarakah (Kerja Sama)</option>
                        <option value="qardh" {{ old('tipe_pembiayaan', $jenisPembiayaan->tipe_pembiayaan) == 'qardh' ? 'selected' : '' }}>Qardh (Pinjaman Baik)</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Pilih Status</option>
                        <option value="1" {{ old('status', $jenisPembiayaan->status) == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status', $jenisPembiayaan->status) == '0' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Syariah Settings -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-percentage mr-2"></i>Pengaturan Syariah
            </h3>
        </div>
        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="margin" class="block text-sm font-medium text-gray-700">Margin (%)</label>
                    <input type="number" id="margin" name="margin" required step="0.01" min="0" max="100"
                           value="{{ old('margin', $jenisPembiayaan->margin) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="15.00">
                    <p class="text-xs text-gray-500 mt-1">Keuntungan penjual (untuk Murabahah)</p>
                </div>
                <div>
                    <label for="bagi_hasil" class="block text-sm font-medium text-gray-700">Bagi Hasil (%)</label>
                    <input type="number" id="bagi_hasil" name="bagi_hasil" required step="0.01" min="0" max="100"
                           value="{{ old('bagi_hasil', $jenisPembiayaan->bagi_hasil) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="60.00">
                    <p class="text-xs text-gray-500 mt-1">Nisbah bagi hasil (untuk Mudharabah/Musyarakah)</p>
                </div>
            </div>

            <div>
                <label for="periode_hitung" class="block text-sm font-medium text-gray-700">Periode Hitung</label>
                <select id="periode_hitung" name="periode_hitung" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Pilih Periode</option>
                    <option value="bulanan" {{ old('periode_hitung', $jenisPembiayaan->periode_hitung) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ old('periode_hitung', $jenisPembiayaan->periode_hitung) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    <option value="otomatis" {{ old('periode_hitung', $jenisPembiayaan->periode_hitung) == 'otomatis' ? 'selected' : '' }}>Otomatis (setiap transaksi)</option>
                    <option value="jtempo" {{ old('periode_hitung', $jenisPembiayaan->periode_hitung) == 'jtempo' ? 'selected' : '' }}>Sesuai Jatuh Tempo</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Perhitungan margin/bagi hasil</p>
            </div>
        </div>

        <!-- Plafon dan Jangka Waktu -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-coins mr-2"></i>Plafon dan Jangka Waktu
            </h3>
        </div>
        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="minimal_pembiayaan" class="block text-sm font-medium text-gray-700">Minimal Pembiayaan</label>
                    <input type="number" id="minimal_pembiayaan" name="minimal_pembiayaan" required step="100000" min="0"
                           value="{{ old('minimal_pembiayaan', $jenisPembiayaan->minimal_pembiayaan) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="1000000">
                    <p class="text-xs text-gray-500 mt-1">Minimal jumlah pembiayaan (dalam Rupiah)</p>
                </div>
                <div>
                    <label for="maksimal_pembiayaan" class="block text-sm font-medium text-gray-700">Maksimal Pembiayaan</label>
                    <input type="number" id="maksimal_pembiayaan" name="maksimal_pembiayaan" step="100000" min="0"
                           value="{{ old('maksimal_pembiayaan', $jenisPembiayaan->maksimal_pembiayaan) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="50000000">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batasan maksimal</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="jangka_waktu_min" class="block text-sm font-medium text-gray-700">Jangka Waktu Minimal</label>
                    <input type="number" id="jangka_waktu_min" name="jangka_waktu_min" required min="1"
                           value="{{ old('jangka_waktu_min', $jenisPembiayaan->jangka_waktu_min) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="1">
                    <p class="text-xs text-gray-500 mt-1">Dalam bulan</p>
                </div>
                <div>
                    <label for="jangka_waktu_max" class="block text-sm font-medium text-gray-700">Jangka Waktu Maksimal</label>
                    <input type="number" id="jangka_waktu_max" name="jangka_waktu_max" required min="1"
                           value="{{ old('jangka_waktu_max', $jenisPembiayaan->jangka_waktu_max) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="12">
                    <p class="text-xs text-gray-500 mt-1">Dalam bulan</p>
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-info-circle mr-2"></i>Keterangan
            </h3>
        </div>
        <div class="px-6 py-4 space-y-4">
            <div>
                <label for="syarat_dukung" class="block text-sm font-medium text-gray-700">Syarat & Dokumen Pendukung</label>
                <textarea id="syarat_dukung" name="syarat_dukung" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Contoh: KTP, KK, Slip Gaji, Surat Keterangan Usaha">{{ old('syarat_dukung', $jenisPembiayaan->syarat_dukung) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Opsional: Syarat dan dokumen yang dibutuhkan</p>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="2"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Keterangan tambahan tentang jenis pembiayaan ini">{{ old('keterangan', $jenisPembiayaan->keterangan) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Opsional: Informasi tambahan tentang jenis pembiayaan</p>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.jenis-pembiayaan.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Update Jenis Pembiayaan
                </button>
            </div>
        </div>
    </div>
</form>
</div>
@endsection