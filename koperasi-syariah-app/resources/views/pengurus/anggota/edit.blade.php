@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('pengurus.dashboard') }}" class="text-gray-700 hover:text-gray-900">
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('pengurus.anggota.index') }}" class="text-gray-700 hover:text-gray-900">
                            Anggota
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
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Edit Anggota</h1>
        <p class="text-gray-600 mt-2">Edit data anggota: {{ $anggota->nama_lengkap }} ({{ $anggota->no_anggota_formatted ?? $anggota->no_anggota }})</p>
    </div>

    <form action="{{ route('pengurus.anggota.update', $anggota->id) }}" method="POST">
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
            <!-- Personal Information -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user mr-2"></i>Informasi Pribadi
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required
                               value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" id="nik" name="nik" required maxlength="20"
                               value="{{ old('nik', $anggota->nik) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Nomor Induk Kependudukan">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" required
                               value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Kota kelahiran">
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required
                               value="{{ old('tanggal_lahir', $anggota->tanggal_lahir->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input type="tel" id="no_hp" name="no_hp" required maxlength="15"
                               value="{{ old('no_hp', $anggota->no_hp) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="08xx-xxxx-xxxx">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required
                               value="{{ old('email', $anggota->email) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="email@example.com">
                    </div>
                </div>

                <div>
                    <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              placeholder="Alamat lengkap">{{ old('alamat_lengkap', $anggota->alamat_lengkap) }}</textarea>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-briefcase mr-2"></i>Informasi Profesional
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                        <input type="text" id="pekerjaan" name="pekerjaan" required
                               value="{{ old('pekerjaan', $anggota->pekerjaan) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Pekerjaan saat ini">
                    </div>
                    <div>
                        <label for="penghasilan" class="block text-sm font-medium text-gray-700">Penghasilan per Bulan</label>
                        <input type="number" id="penghasilan" name="penghasilan" min="0" step="10000"
                               value="{{ old('penghasilan', $anggota->penghasilan) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="0">
                    </div>
                </div>
                <div>
                    <label for="no_npwp" class="block text-sm font-medium text-gray-700">No. NPWP</label>
                    <input type="text" id="no_npwp" name="no_npwp" maxlength="20"
                           value="{{ old('no_npwp', $anggota->no_npwp) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Nomor NPWP (opsional)">
                </div>
            </div>

            <!-- Membership Information -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-users mr-2"></i>Informasi Keanggotaan
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_anggota" class="block text-sm font-medium text-gray-700">Jenis Anggota</label>
                        <select id="jenis_anggota" name="jenis_anggota" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Pilih Jenis Anggota</option>
                            <option value="biasa" {{ old('jenis_anggota', $anggota->jenis_anggota) == 'biasa' ? 'selected' : '' }}>Anggota Biasa</option>
                            <option value="luar_biasa" {{ old('jenis_anggota', $anggota->jenis_anggota) == 'luar_biasa' ? 'selected' : '' }}>Anggota Luar Biasa</option>
                            <option value="kehormatan" {{ old('jenis_anggota', $anggota->jenis_anggota) == 'kehormatan' ? 'selected' : '' }}>Anggota Kehormatan</option>
                        </select>
                    </div>
                    <div>
                        <label for="status_keanggotaan" class="block text-sm font-medium text-gray-700">Status Keanggotaan</label>
                        <select id="status_keanggotaan" name="status_keanggotaan" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status_keanggotaan', $anggota->status_keanggotaan) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status_keanggotaan', $anggota->status_keanggotaan) == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                            <option value="keluar" {{ old('status_keanggotaan', $anggota->status_keanggotaan) == 'keluar' ? 'selected' : '' }}>Keluar</option>
                            <option value="meninggal" {{ old('status_keanggotaan', $anggota->status_keanggotaan) == 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="tanggal_gabung" class="block text-sm font-medium text-gray-700">Tanggal Gabung</label>
                    <input type="date" id="tanggal_gabung" name="tanggal_gabung" required
                           value="{{ old('tanggal_gabung', $anggota->tanggal_gabung ? $anggota->tanggal_gabung->format('Y-m-d') : date('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">Tanggal mulai anggota bergabung (digunakan untuk perhitungan simpanan wajib)</p>
                </div>
            </div>

            <!-- User Account Information -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user-circle mr-2"></i>Informasi Akun
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="border-t pt-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Ubah Password (opsional)</h4>
                    <p class="text-xs text-gray-500 mb-3">Kosongkan jika tidak ingin mengubah password</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" id="password" name="password"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Minimal 8 karakter">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="Ketik ulang password">
                        </div>
                    </div>
                    @if($anggota->user && $anggota->user->first_login)
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                User belum pernah mengganti password
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('pengurus.anggota.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (outside of main form) -->
    @if($anggota->user_id != auth()->id())
        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-900 mb-3">
                <i class="fas fa-exclamation-triangle mr-2"></i>Area Berbahaya
            </h3>
            <p class="text-sm text-red-700 mb-4">Hapus anggota ini secara permanen dari sistem. Data akan diarsipkan di database namun tidak akan ditampilkan di aplikasi.</p>
            <form action="{{ route('pengurus.anggota.destroy', $anggota->id) }}"
                  method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini? Data akan diarsipkan di database.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Anggota
                </button>
            </form>
        </div>
    @endif

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Anggota
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Data Akun:</h4>
                <ul class="space-y-1">
                    <li><strong>No. Anggota:</strong> {{ $anggota->no_anggota_formatted ?? $anggota->no_anggota }}</li>
                    <li><strong>Username:</strong> {{ $anggota->user?->username ?? $anggota->user?->email ?? 'Tidak tersedia' }}</li>
                    <li><strong>Status Password:</strong> {{ $anggota->user?->first_login ? 'Belum diubah' : 'Sudah diubah' }}</li>
                    <li><strong>Tanggal Gabung:</strong> {{ $anggota->tanggal_gabung->format('d M Y') }}</li>
                    <li><strong>Periode Daftar:</strong> {{ $anggota->periode_pendaftaran }}</li>
                    <li><strong>Usia:</strong> {{ $anggota->usia ?? 0 }} tahun</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Catatan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>• Anggota login dengan nomor anggota sebagai username</li>
                    <li>• Email tetap disimpan untuk komunikasi</li>
                    <li>• Password reset akan memaksa user mengganti password</li>
                    <li>• Tidak dapat menghapus akun sendiri</li>
                    <li>• Hapus anggota akan mengarsipkan data di database</li>
                    <li>• Status keanggotaan mempengaruhi hak akses ke layanan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection