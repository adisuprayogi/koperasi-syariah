@extends('layouts.app')

@section('title', 'Tandai Anggota Keluar')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-times text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tandai Anggota Keluar</h1>
                    <p class="text-gray-600 text-sm mt-1">Proses perubahan status keanggotaan</p>
                </div>
            </div>
            <a href="{{ route('pengurus.anggota.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Info Card Anggota -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                Informasi Anggota
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-id-badge text-gray-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Nomor Anggota</p>
                            <p class="font-medium text-gray-900">{{ $anggota->no_anggota }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-user text-gray-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Nama Lengkap</p>
                            <p class="font-medium text-gray-900">{{ $anggota->nama_lengkap }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-address-card text-gray-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">NIK</p>
                            <p class="font-medium text-gray-900">{{ $anggota->nik }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-envelope text-gray-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium text-gray-900">{{ $anggota->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-calendar-check text-gray-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Tanggal Gabung</p>
                            <p class="font-medium text-gray-900">{{ $anggota->tanggal_gabung ? $anggota->tanggal_gabung->format('d F Y') : '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-tag text-gray-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Status Saat Ini</p>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $anggota->status_keanggotaan == 'aktif' ? 'bg-green-100 text-green-800' :
                                       ($anggota->status_keanggotaan == 'keluar' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    <i class="fas fa-circle mr-1.5" style="font-size: 6px;"></i>
                                    {{ ucfirst($anggota->status_keanggotaan) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-user-tag text-gray-600 text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-500">Jenis Anggota</p>
                            <p class="font-medium text-gray-900">{{ $anggota->jenis_anggota_label }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Status Keluar -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-orange-50 to-red-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2 text-orange-600"></i>
                Formulir Perubahan Status
            </h2>
        </div>

        <form action="{{ route('pengurus.anggota.process.keluar', $anggota->id) }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-6">
                <!-- Tanggal Keluar -->
                <div>
                    <label for="tanggal_keluar" class="block text-sm font-medium text-gray-900 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>
                        Tanggal Keluar
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="date"
                               id="tanggal_keluar"
                               name="tanggal_keluar"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-gray-50 cursor-not-allowed"
                               value="{{ date('Y-m-d') }}"
                               required
                               readonly>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-calendar text-gray-400"></i>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Tanggal saat ini akan otomatis digunakan. Status akan berubah ke "keluar".
                    </p>
                </div>

                <!-- Alasan Keluar -->
                <div>
                    <label for="alasan_keluar" class="block text-sm font-medium text-gray-900 mb-2">
                        <i class="fas fa-comment-alt mr-2 text-gray-500"></i>
                        Alasan Keluar
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea name="alasan_keluar"
                              id="alasan_keluar"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none"
                              placeholder="Masukkan alasan anggota keluar..."
                              required></textarea>
                    <div class="mt-2 flex justify-between items-center">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Jelaskan mengapa anggota ini keluar dari koperasi
                        </p>
                        <span id="char-counter" class="text-sm text-gray-400">0/500 karakter</span>
                    </div>
                </div>
            </div>

            <!-- Warning Section -->
            <div class="mt-8 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5" style="font-size: 1.25rem;"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-900">Perhatian!</h3>
                        <div class="mt-2 text-sm text-amber-800">
                            <p>Dengan mengubah status anggota ke "keluar":</p>
                            <ul class="mt-2 ml-4 list-disc space-y-1">
                                <li>Akun pengguna anggota akan otomatis dinonaktifkan</li>
                                <li>Anggota tidak lagi dapat login ke sistem</li>
                                <li>Semua transaksi akan dihentikan</li>
                                <li>Data anggota tetap disimpan untuk keperluan arsip</li>
                                <li>Status dapat dikembalikan ke "aktif" jika diperlukan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ route('pengurus.anggota.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-200 transition-colors duration-200 font-medium">
                    <i class="fas fa-ban mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium shadow-md">
                    <i class="fas fa-user-times mr-2"></i>
                    Konfirmasi Anggota Keluar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter untuk alasan_keluar
    const alasanTextarea = document.getElementById('alasan_keluar');
    const maxLength = 500;

    if (alasanTextarea) {
        alasanTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;

            // Update character counter
            const counter = document.getElementById('char-counter');
            counter.textContent = `${currentLength}/${maxLength} karakter`;

            // Change color based on remaining characters
            counter.className = currentLength > 480 ? 'text-sm text-red-600' :
                               currentLength > 450 ? 'text-sm text-amber-600' :
                               'text-sm text-gray-400';

            // Prevent exceeding max length
            if (currentLength > maxLength) {
                this.value = this.value.substring(0, maxLength);
            }
        });

        // Initialize counter on load
        alasanTextarea.dispatchEvent(new Event('input'));
    }

    // Prevent focus on readonly date input
    const tanggalInput = document.getElementById('tanggal_keluar');
    if (tanggalInput) {
        tanggalInput.addEventListener('focus', function(e) {
            e.preventDefault();
            this.blur();
        });
    }
});
</script>
@endsection