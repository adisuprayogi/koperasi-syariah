@extends('layouts.app')

@section('title', 'Tambah Jenis Simpanan')

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
                        <a href="{{ route('admin.jenis-simpanan.index') }}" class="text-gray-700 hover:text-gray-900">
                        Jenis Simpanan
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Tambah</span>
                </div>
            </li>
        </ol>
    </nav>
    <h1 class="text-3xl font-bold text-gray-900 mt-4">Tambah Jenis Simpanan</h1>
    <p class="text-gray-600 mt-2">Buat jenis simpanan syariah baru</p>
</div>

<form action="{{ route('admin.jenis-simpanan.store') }}" method="POST">
    @csrf

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
                           value="{{ old('kode_jenis') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Contoh: SM001">
                    <p class="text-xs text-gray-500 mt-1">Kode unik untuk jenis simpanan (maks. 10 karakter)</p>
                </div>
                <div>
                    <label for="nama_simpanan" class="block text-sm font-medium text-gray-700">Nama Simpanan</label>
                    <input type="text" id="nama_simpanan" name="nama_simpanan" required
                           value="{{ old('nama_simpanan') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Contoh: Simpanan Sukarela">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tipe_simpanan" class="block text-sm font-medium text-gray-700">Tipe Simpanan</label>
                    <select id="tipe_simpanan" name="tipe_simpanan" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Pilih Tipe Simpanan</option>
                        <option value="modal" {{ old('tipe_simpanan') == 'modal' ? 'selected' : '' }}>Simpanan Modal</option>
                        <option value="pokok" {{ old('tipe_simpanan') == 'pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
                        <option value="wajib" {{ old('tipe_simpanan') == 'wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                        <option value="sukarela" {{ old('tipe_simpanan') == 'sukarela' ? 'selected' : '' }}>Simpanan Sukarela</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Pilih Status</option>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Syariah Settings -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-percentage mr-2"></i>Pengaturan Nisbah Syariah
            </h3>
        </div>
        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nisbah" class="block text-sm font-medium text-gray-700">Nisbah (%)</label>
                    <input type="number" id="nisbah" name="nisbah" required step="any" min="0" max="100"
                           value="{{ old('nisbah') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Contoh: 5.5 atau 10">
                    <p class="text-xs text-gray-500 mt-1">Gunakan titik (.) untuk desimal, contoh: 5.5 atau 10.25</p>
                </div>
                <div>
                    <label for="periode_hitung_bunga" class="block text-sm font-medium text-gray-700">Periode Hitung Nisbah</label>
                    <select id="periode_hitung_bunga" name="periode_hitung_bunga" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Pilih Periode</option>
                        <option value="bulanan" {{ old('periode_hitung_bunga') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="tahunan" {{ old('periode_hitung_bunga') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                        <option value="otomatis" {{ old('periode_hitung_bunga') == 'otomatis' ? 'selected' : '' }}>Otomatis (setiap transaksi)</option>
                        <option value="manual" {{ old('periode_hitung_bunga') == 'manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Batasan Setoran -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-coins mr-2"></i>Batasan Setoran
            </h3>
        </div>
        <div class="px-6 py-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="minimal_setor" class="block text-sm font-medium text-gray-700">Minimal Setoran</label>
                    <input type="number" id="minimal_setor" name="minimal_setor" required step="1000" min="0"
                           value="{{ old('minimal_setor') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="100000">
                    <p class="text-xs text-gray-500 mt-1">Minimal jumlah setoran (dalam Rupiah)</p>
                </div>
                <div>
                    <label for="maksimal_setor" class="block text-sm font-medium text-gray-700">Maksimal Setoran</label>
                    <input type="number" id="maksimal_setor" name="maksimal_setor" step="1000" min="0"
                           value="{{ old('maksimal_setor') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="10000000">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batasan maksimal</p>
                </div>
            </div>
        </div>

        <!-- Penarikan Settings -->
        <div class="px-6 py-4 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-hand-holding-usd mr-2"></i>Pengaturan Penarikan
            </h3>
        </div>
        <div class="px-6 py-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bisa Ditarik?</label>
                <div class="flex space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="bisa_ditarik" value="1" required
                               {{ old('bisa_ditarik') == '1' ? 'checked' : '' }}
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Ya, simpanan bisa ditarik</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="bisa_ditarik" value="0" required
                               {{ old('bisa_ditarik') == '0' ? 'checked' : '' }}
                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-700">Tidak, tidak bisa ditarik</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="aturan_penarikan" class="block text-sm font-medium text-gray-700">Aturan Penarikan</label>
                <textarea id="aturan_penarikan" name="aturan_penarikan" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Contoh: Penarikan minimal Rp 100.000, maksimal 50% dari total simpanan">{{ old('aturan_penarikan') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Opsional: Aturan khusus untuk penarikan simpanan</p>
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
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea id="keterangan" name="keterangan" rows="2"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Keterangan tambahan tentang jenis simpanan ini">{{ old('keterangan') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Opsional: Informasi tambahan tentang jenis simpanan</p>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.jenis-simpanan.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Jenis Simpanan
                </button>
            </div>
        </div>
    </div>
</form>
</div>
@endsection