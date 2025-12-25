@extends('layouts.app')

@section('title', 'Manajemen Anggota')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Anggota</h1>
            <p class="text-gray-600 mt-2">Kelola data anggota koperasi</p>
        </div>
        <div>
            <a href="{{ route('pengurus.anggota.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Anggota
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <i class="fas fa-users text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Anggota</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAnggota ?? $anggota->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Aktif</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $totalAktif }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Anggota Biasa</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalBiasa }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-calendar text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Bulan Ini</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalBulanIni }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('pengurus.anggota.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Anggota</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nama, No. Anggota, NIK, Email, No. HP..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Keanggotaan</label>
                    <select name="status_keanggotaan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status_keanggotaan') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status_keanggotaan') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                        <option value="keluar" {{ request('status_keanggotaan') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        <option value="meninggal" {{ request('status_keanggotaan') == 'meninggal' ? 'selected' : '' }}>Meninggal</option>
                    </select>
                </div>

                <!-- Jenis Anggota Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Anggota</label>
                    <select name="jenis_anggota"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Semua Jenis</option>
                        <option value="biasa" {{ request('jenis_anggota') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                        <option value="luar_biasa" {{ request('jenis_anggota') == 'luar_biasa' ? 'selected' : '' }}>Luar Biasa</option>
                        <option value="kehormatan" {{ request('jenis_anggota') == 'kehormatan' ? 'selected' : '' }}>Kehormatan</option>
                    </select>
                </div>
            </div>

            <!-- Date Range Filter -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Gabung Mulai</label>
                    <input type="date"
                           name="tanggal_mulai"
                           value="{{ request('tanggal_mulai') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Gabung Selesai</label>
                    <input type="date"
                           name="tanggal_selesai"
                           value="{{ request('tanggal_selesai') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $anggota->count() }}</span> data
                    @if(request()->hasAny(['search', 'status_keanggotaan', 'jenis_anggota', 'tanggal_mulai', 'tanggal_selesai']))
                        dari hasil filter
                    @endif
                </div>
                <div class="space-x-2">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('pengurus.anggota.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Anggota Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-3 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Anggota</h2>
        </div>

        <!-- Mobile Responsive Table -->
        <div class="overflow-x-auto shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Informasi Anggota
                        </th>
                        <th class="hidden sm:table-cell px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kontak
                        </th>
                          <th class="hidden xl:table-cell px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Gabung
                        </th>
                        <th class="hidden xl:table-cell px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Akun
                        </th>
                        <th class="hidden xl:table-cell px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($anggota as $a)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $a->nama_lengkap }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $a->no_anggota_formatted ?? $a->no_anggota }}</div>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full
                                            @if($a->status_keanggotaan == 'aktif')
                                                bg-green-100 text-green-800
                                            @elseif($a->status_keanggotaan == 'nonaktif')
                                                bg-gray-100 text-gray-800
                                            @elseif($a->status_keanggotaan == 'keluar')
                                                bg-yellow-100 text-yellow-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif">
                                            {{ $a->status_label }}
                                        </span>
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full
                                            @if($a->jenis_anggota == 'biasa')
                                                bg-blue-100 text-blue-800
                                            @elseif($a->jenis_anggota == 'luar_biasa')
                                                bg-purple-100 text-purple-800
                                            @else
                                                bg-orange-100 text-orange-800
                                            @endif">
                                            {{ $a->jenis_label }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400 sm:hidden mt-1">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $a->no_hp }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $a->nik }} • {{ $a->jenis_kelamin_label }} • {{ $a->usia ?? 0 }} th
                                    </div>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-3 py-2">
                                <div class="text-xs text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $a->no_hp }}
                                </div>
                                <div class="text-xs text-gray-500 truncate">
                                    {{ $a->email }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    <i class="fas fa-briefcase text-gray-400 mr-1"></i>{{ $a->pekerjaan }}
                                </div>
                            </td>
                              <td class="hidden xl:table-cell px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $a->tanggal_gabung->format('d M Y') }}
                            </td>
                            <td class="hidden xl:table-cell px-3 py-3 whitespace-nowrap">
                                @if($a->user)
                                    <div class="text-sm text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </div>
                                    @if($a->user->first_login)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 mt-1">
                                            <i class="fas fa-key mr-1"></i>Belum ganti password
                                        </span>
                                    @endif
                                @else
                                    <div class="text-sm text-gray-400">
                                        <i class="fas fa-times-circle mr-1"></i>Tidak ada
                                    </div>
                                @endif
                            </td>
                            <td class="hidden xl:table-cell px-3 py-3 whitespace-nowrap">
                                <div>
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full
                                        @if($a->status_keanggotaan == 'aktif')
                                            bg-green-100 text-green-800
                                        @elseif($a->status_keanggotaan == 'keluar')
                                            bg-red-100 text-red-800
                                        @else
                                            bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($a->status_keanggotaan) }}
                                    </span>
                                    @if($a->tanggal_keluar)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-calendar-times mr-1"></i>{{ $a->tanggal_keluar->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-end sm:justify-center space-x-2">
                                    @if($a->status_keanggotaan == 'aktif')
                                        <a href="{{ route('pengurus.anggota.keluar', $a->id) }}"
                                           class="text-orange-600 hover:text-orange-900 mr-2"
                                           title="Tandai Keluar">
                                            <i class="fas fa-user-times"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('pengurus.anggota.reaktif', $a->id) }}"
                                           class="text-green-600 hover:text-green-900 mr-2"
                                           title="Aktifkan Kembali">
                                            <i class="fas fa-user-check"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('pengurus.anggota.edit', $a->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($a->user_id != auth()->id())
                                        <form action="{{ route('pengurus.anggota.destroy', $a->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini? Data akan diarsipkan di database.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Belum ada data anggota</p>
                                <p class="text-gray-400 text-sm mt-1">
                                    <a href="{{ route('pengurus.anggota.create') }}" class="text-indigo-600 hover:text-indigo-500">
                                        Tambah anggota pertama
                                    </a>
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $anggota->links('pagination.custom') }}
        </div>
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Manajemen Anggota
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Status Keanggotaan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Aktif:</strong> Anggota yang masih aktif berpartisipasi</li>
                    <li><strong>Non-aktif:</strong> Anggota yang tidak aktif untuk sementara</li>
                    <li><strong>Keluar:</strong> Anggota yang keluar dari koperasi</li>
                    <li><strong>Meninggal:</strong> Anggota yang telah meninggal dunia</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Status Keanggotaan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Aktif:</strong> Anggota yang masih aktif berpartisipasi</li>
                    <li><strong>Tidak Aktif:</strong> Anggota yang tidak aktif untuk sementara</li>
                    <li><strong>Keluar:</strong> Anggota yang keluar dari koperasi</li>
                </ul>

                <h4 class="font-semibold mb-2 mt-4">Management Status:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Tandai Keluar:</strong> Ubah status anggota menjadi keluar dengan alasan yang jelas</li>
                    <li><strong>Aktifkan Kembali:</strong> Ubah status anggota keluar kembali menjadi aktif</li>
                    <li><strong>Tanggal Keluar:</strong> Akan otomatis tercatat saat anggota ditandai keluar</li>
                    <li><strong>Alasan Keluar:</strong> Dicatat untuk keperluan administrasi dan arsip</li>
                </ul>

                <h4 class="font-semibold mb-2">Jenis Anggota:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Anggota Biasa:</strong> Anggota reguler dengan hak biasa</li>
                    <li><strong>Anggota Luar Biasa:</strong> Anggota dengan hak khusus</li>
                    <li><strong>Anggota Kehormatan:</strong> Anggota penghargaan/tokoh masyarakat</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Format Nomor Anggota:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Format:</strong> YYMM + . + 5 digit nomor urut</li>
                    <li><strong>Contoh:</strong> 2512.00001 (Desember 2025, anggota pertama)</li>
                    <li><strong>Reset:</strong> Nomor urut reset ke 00001 setiap bulan baru</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection