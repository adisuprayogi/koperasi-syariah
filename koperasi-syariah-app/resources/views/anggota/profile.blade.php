@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Profil Saya</h1>
        <p class="text-gray-600 mt-2">Kelola informasi pribadi dan akun Anda</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('anggota.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Avatar Section -->
            <div class="mb-6 flex items-center space-x-6">
                <div class="relative">
                    @if(auth()->user()->anggota && auth()->user()->anggota->foto)
                        <img id="foto-preview" src="{{ asset('storage/' . auth()->user()->anggota->foto) }}"
                             alt="Profile"
                             class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div id="foto-preview" class="h-20 w-20 rounded-full bg-gray-300 border-4 border-white shadow-lg flex items-center justify-center">
                            <i class="fas fa-user text-gray-500 text-2xl"></i>
                        </div>
                    @endif

                    <!-- Upload Button -->
                    <div class="absolute bottom-0 right-0">
                        <label for="foto-upload" class="bg-primary-600 hover:bg-primary-700 text-white p-2 rounded-full cursor-pointer shadow-md transition-colors duration-200" title="Upload Foto Profil">
                            <i class="fas fa-camera text-xs"></i>
                        </label>
                        <input type="file" id="foto-upload" name="foto" accept="image/*;capture=camera" class="hidden" onchange="previewFoto(event)">
                    </div>
                    <div id="upload-status" class="hidden absolute bottom-0 left-0 bg-green-500 text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-check mr-1"></i>Terupload
                    </div>
                </div>

                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                          @if(auth()->user()->isAdmin())
                              bg-red-100 text-red-800
                          @elseif(auth()->user()->isPengurus())
                              bg-blue-100 text-blue-800
                          @else
                              bg-green-100 text-green-800
                          @endif">
                        {{ auth()->user()->role_label }}
                    </span>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pribadi</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap"
                                   value="{{ auth()->user()->anggota->nama_lengkap ?? '' }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>

                        <div>
                            <label for="no_anggota" class="block text-sm font-medium text-gray-700">No. Anggota</label>
                            <input type="text" id="no_anggota" name="no_anggota"
                                   value="{{ auth()->user()->anggota->no_anggota ?? '' }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm bg-gray-50"
                                   readonly>
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir"
                                   value="{{ auth()->user()->anggota->tempat_lahir ?? '' }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                   value="{{ auth()->user()->anggota->tanggal_lahir ? auth()->user()->anggota->tanggal_lahir->format('Y-m-d') : '' }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                    required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ (auth()->user()->anggota->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ (auth()->user()->anggota->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kontak & Alamat</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                            <input type="tel" id="no_hp" name="no_hp"
                                   value="{{ auth()->user()->anggota->no_hp ?? '' }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email"
                                   value="{{ auth()->user()->email }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm bg-gray-50"
                                   readonly>
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                      required>{{ auth()->user()->anggota->alamat_lengkap ?? '' }}</textarea>
                        </div>

                        <div>
                            <label for="pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                            <input type="text" id="pekerjaan" name="pekerjaan"
                                   value="{{ auth()->user()->anggota->pekerjaan ?? '' }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>

                        <div>
                            <label for="pendapatan" class="block text-sm font-medium text-gray-700">Pendapatan per Bulan</label>
                            <input type="number" id="pendapatan" name="pendapatan"
                                   value="{{ auth()->user()->anggota->penghasilan ?? '' }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('anggota.dashboard') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Kartu Anggota Section -->
    @if(auth()->user()->anggota && auth()->user()->anggota->status_keanggotaan == 'aktif')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Kartu Anggota</h3>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-2">Download kartu anggota Anda dalam format PDF</p>
                <p class="text-xs text-gray-500">Kartu berlaku sebagai identitas resmi anggota koperasi</p>
            </div>
            <a href="{{ route('anggota.download-kartu') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-download mr-2"></i>
                Download Kartu
            </a>
        </div>
    </div>
    @endif

    <!-- Account Security Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Keamanan Akun</h3>

        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Password</h4>
                    <p class="text-sm text-gray-500">Ubah password akun Anda</p>
                </div>
                <a href="{{ route('password.change') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <i class="fas fa-key mr-2"></i>Ubah Password
                </a>
            </div>

            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Status Akun</h4>
                    <p class="text-sm text-gray-500">Akun Anda dalam status @if(auth()->user()->anggota && auth()->user()->anggota->status_keanggotaan == 'aktif')<span class="text-green-600 font-medium">Aktif</span>@else<span class="text-red-600 font-medium">Nonaktif</span>@endif</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                      @if(auth()->user()->anggota && auth()->user()->anggota->status_keanggotaan == 'aktif')
                          bg-green-100 text-green-800
                      @else
                          bg-red-100 text-red-800
                      @endif">
                    @if(auth()->user()->anggota && auth()->user()->anggota->status_keanggotaan == 'aktif')
                        <i class="fas fa-check-circle mr-1"></i>Aktif
                    @else
                        <i class="fas fa-times-circle mr-1"></i>Nonaktif
                    @endif
                </span>
            </div>
        </div>
    </div>

    <script>
        function previewFoto(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('foto-preview');
            const uploadStatus = document.getElementById('upload-status');

            if (file) {
                // Validate file size (2MB)
                if (file.size > 2048 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    event.target.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('File harus berupa gambar (JPEG, PNG, JPG).');
                    event.target.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg">`;

                    // Show upload success indicator
                    uploadStatus.classList.remove('hidden');
                    setTimeout(() => {
                        uploadStatus.classList.add('hidden');
                    }, 3000);
                }
                reader.readAsDataURL(file);
            }
        }

        // Mobile camera support and file input optimization
        document.getElementById('foto-upload').addEventListener('click', function() {
            // For mobile devices, set capture attribute to prefer camera
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            if (isMobile) {
                this.setAttribute('capture', 'camera');
            } else {
                this.removeAttribute('capture');
            }
        });

        // Form submission feedback
        document.querySelector('form').addEventListener('submit', function(e) {
            const fotoInput = document.getElementById('foto-upload');
            if (fotoInput.files && fotoInput.files[0]) {
                const uploadStatus = document.getElementById('upload-status');
                uploadStatus.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Mengupload...';
                uploadStatus.classList.remove('hidden');
                uploadStatus.classList.remove('bg-green-500');
                uploadStatus.classList.add('bg-blue-500');
            }
        });
    </script>
</div>
@endsection