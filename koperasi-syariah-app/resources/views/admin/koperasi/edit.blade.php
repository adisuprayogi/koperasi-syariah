@extends('layouts.app')

@section('title', 'Edit Data Koperasi')

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
                        <a href="{{ route('admin.koperasi.index') }}" class="text-gray-700 hover:text-gray-900">
                            Data Koperasi
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
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Edit Data Koperasi</h1>
        <p class="text-gray-600 mt-2">Update profil data koperasi</p>
    </div>

    <form action="{{ route('admin.koperasi.update') }}" method="POST" enctype="multipart/form-data">
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

        @php
            $koperasi = \App\Models\Koperasi::first();
        @endphp

        <div class="bg-white shadow rounded-lg">
            <!-- Informasi Umum -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-building mr-2"></i>Informasi Umum
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_koperasi" class="block text-sm font-medium text-gray-700">Nama Koperasi</label>
                        <input type="text" id="nama_koperasi" name="nama_koperasi" required
                               value="{{ old('nama_koperasi', $koperasi->nama_lengkap ?? $koperasi->nama_koperasi ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Nama koperasi lengkap">
                    </div>
                    <div>
                        <label for="no_koperasi" class="block text-sm font-medium text-gray-700">No. Koperasi</label>
                        <input type="text" id="no_koperasi" name="no_koperasi" required maxlength="50"
                               value="{{ old('no_koperasi', $koperasi->no_koperasi ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Nomor registrasi koperasi">
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              placeholder="Alamat lengkap koperasi">{{ old('alamat', $koperasi->alamat ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <input type="tel" id="telepon" name="telepon" required maxlength="20"
                               value="{{ old('telepon', $koperasi->telepon ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="08xx-xxxx-xxxx">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required
                               value="{{ old('email', $koperasi->email ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="email@koperasi.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                        <input type="url" id="website" name="website"
                               value="{{ old('website', $koperasi->website ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="https://www.koperasi.com">
                    </div>
                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo Koperasi</label>
                        <input type="file" id="logo" name="logo" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal: 2MB</p>
                        @if($koperasi && $koperasi->logo)
                            <div class="mt-2">
                                <span class="text-xs text-gray-500">Logo saat ini:</span>
                                <img class="h-12 w-auto mt-1" src="{{ asset('storage/' . $koperasi->logo) }}" alt="Logo">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_berdiri" class="block text-sm font-medium text-gray-700">Tanggal Berdiri</label>
                        <input type="date" id="tanggal_berdiri" name="tanggal_berdiri" required
                               value="{{ old('tanggal_berdiri', $koperasi->tanggal_berdiri?->format('Y-m-d') ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status', $koperasi->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ old('status', $koperasi->status ?? '') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Informasi Legalitas -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-file-contract mr-2"></i>Informasi Legalitas
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="no_akta_notaris" class="block text-sm font-medium text-gray-700">No. Akta Notaris</label>
                        <input type="text" id="no_akta_notaris" name="no_akta_notaris" required maxlength="100"
                               value="{{ old('no_akta_notaris', $koperasi->no_akta_notaris ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Nomor akta pendirian">
                    </div>
                    <div>
                        <label for="tanggal_akta" class="block text-sm font-medium text-gray-700">Tanggal Akta</label>
                        <input type="date" id="tanggal_akta" name="tanggal_akta" required
                               value="{{ old('tanggal_akta', $koperasi->tanggal_akta?->format('Y-m-d') ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div>
                    <label for="nama_notaris" class="block text-sm font-medium text-gray-700">Nama Notaris</label>
                    <input type="text" id="nama_notaris" name="nama_notaris" required maxlength="100"
                           value="{{ old('nama_notaris', $koperasi->nama_notaris ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Nama notaris pembuat akta">
                </div>
            </div>

            <!-- Data Pengurus -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-users mr-2"></i>Data Pengurus
                </h3>
            </div>
            <div class="px-6 py-4 space-y-6">
                <!-- Ketua -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Ketua Koperasi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="ketua_nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="ketua_nama" name="ketua_nama" required maxlength="100"
                                   value="{{ old('ketua_nama', $koperasi->ketua_nama ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Nama ketua koperasi">
                        </div>
                        <div>
                            <label for="ketua_nik" class="block text-sm font-medium text-gray-700">NIK</label>
                            <input type="text" id="ketua_nik" name="ketua_nik" required maxlength="20"
                                   value="{{ old('ketua_nik', $koperasi->ketua_nik ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Nomor Induk Kependudukan">
                        </div>
                    </div>
                </div>

                <!-- Sekretaris -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Sekretaris Koperasi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="sekretaris_nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="sekretaris_nama" name="sekretaris_nama" required maxlength="100"
                                   value="{{ old('sekretaris_nama', $koperasi->sekretaris_nama ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Nama sekretaris koperasi">
                        </div>
                        <div>
                            <label for="sekretaris_nik" class="block text-sm font-medium text-gray-700">NIK</label>
                            <input type="text" id="sekretaris_nik" name="sekretaris_nik" required maxlength="20"
                                   value="{{ old('sekretaris_nik', $koperasi->sekretaris_nik ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Nomor Induk Kependudukan">
                        </div>
                    </div>
                </div>

                <!-- Bendahara -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Bendahara Koperasi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="bendahara_nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" id="bendahara_nama" name="bendahara_nama" required maxlength="100"
                                   value="{{ old('bendahara_nama', $koperasi->bendahara_nama ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Nama bendahara koperasi">
                        </div>
                        <div>
                            <label for="bendahara_nik" class="block text-sm font-medium text-gray-700">NIK</label>
                            <input type="text" id="bendahara_nik" name="bendahara_nik" required maxlength="20"
                                   value="{{ old('bendahara_nik', $koperasi->bendahara_nik ?? '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Nomor Induk Kependudukan">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.koperasi.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Update Data Koperasi
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection