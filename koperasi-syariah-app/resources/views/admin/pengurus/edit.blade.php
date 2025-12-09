@extends('layouts.app')

@section('title', 'Edit Pengurus')

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
                        <a href="{{ route('admin.pengurus.index') }}" class="text-gray-700 hover:text-gray-900">
                            Pengurus
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
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Edit Pengurus</h1>
        <p class="text-gray-600 mt-2">Edit data pengurus: {{ $pengurus->user?->name ?? 'Data tidak tersedia' }}</p>
    </div>

    <form action="{{ route('admin.pengurus.update', $pengurus->id) }}" method="POST">
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
            <!-- Account Information -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user-circle mr-2"></i>Informasi Akun
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required
                               value="{{ old('name', $pengurus->user->name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required
                               value="{{ old('email', $pengurus->user->email) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="email@example.com">
                    </div>
                </div>
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
                    @if($pengurus->user->first_login)
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                User belum pernah mengganti password
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Personal Information -->
            <div class="px-6 py-4 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-id-card mr-2"></i>Informasi Pribadi
                </h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="posisi" class="block text-sm font-medium text-gray-700">Jabatan</label>
                        <select id="posisi" name="posisi" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Pilih Jabatan</option>
                            <option value="ketua" {{ old('posisi', $pengurus->posisi) == 'ketua' ? 'selected' : '' }}>Ketua</option>
                            <option value="sekretaris" {{ old('posisi', $pengurus->posisi) == 'sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                            <option value="bendahara" {{ old('posisi', $pengurus->posisi) == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                            <option value="pengurus_lainnya" {{ old('posisi', $pengurus->posisi) == 'pengurus_lainnya' ? 'selected' : '' }}>Pengurus Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_menjabat" class="block text-sm font-medium text-gray-700">Tanggal Menjabat</label>
                        <input type="date" id="tanggal_menjabat" name="tanggal_menjabat" required
                               value="{{ old('tanggal_menjabat', $pengurus->tanggal_menjabat->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input type="tel" id="no_telepon" name="no_telepon"
                           value="{{ old('no_telepon', $pengurus->no_telepon) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="08xx-xxxx-xxxx">
                </div>
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              placeholder="Alamat lengkap">{{ old('alamat', $pengurus->alamat) }}</textarea>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.pengurus.index') }}"
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
    @if($pengurus->user_id != auth()->id())
        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-900 mb-3">
                <i class="fas fa-exclamation-triangle mr-2"></i>Area Berbahaya
            </h3>
            <p class="text-sm text-red-700 mb-4">Hapus pengurus ini secara permanen dari sistem. Data akan diarsipkan di database namun tidak akan ditampilkan di aplikasi.</p>
            <form action="{{ route('admin.pengurus.destroy', $pengurus->id) }}"
                  method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengurus ini? Data akan diarsipkan di database.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Pengurus
                </button>
            </form>
        </div>
    @endif

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Pengurus
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Data Akun:</h4>
                <ul class="space-y-1">
                    <li><strong>Username:</strong> {{ $pengurus->user?->email ?? 'Email tidak tersedia' }}</li>
                    <li><strong>Status Password:</strong> {{ $pengurus->user?->first_login ? 'Belum diubah' : 'Sudah diubah' }}</li>
                    <li><strong>Tanggal Menjabat:</strong> {{ $pengurus->tanggal_menjabat->format('d M Y') }}</li>
                    <li><strong>Status Pengurus:</strong>
                        @if($pengurus->status == 'aktif')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-pause-circle mr-1"></i>Tidak Aktif
                            </span>
                        @endif
                    </li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Catatan:</h4>
                <ul class="space-y-1">
                    <li>• Email digunakan sebagai login username</li>
                    <li>• Password reset akan memaksa user mengganti password</li>
                    <li>• Tidak dapat menghapus akun sendiri</li>
                    <li>• Hapus pengurus akan mengarsipkan data di database</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection