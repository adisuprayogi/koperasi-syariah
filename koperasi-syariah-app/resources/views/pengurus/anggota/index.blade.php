@extends('layouts.app')

@section('title', 'Manajemen Anggota')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Anggota</h1>
            <p class="text-gray-600 mt-2">Kelola data anggota koperasi</p>
        </div>
        <a href="{{ route('pengurus.anggota.create') }}"
           class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Anggota
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-primary-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Anggota</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $totalAnggota ?? $anggota->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Aktif</h3>
                    <p class="text-lg font-bold text-green-600">{{ $totalAktif }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-tie text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Anggota Biasa</h3>
                    <p class="text-lg font-bold text-blue-600">{{ $totalBiasa }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Bulan Ini</h3>
                    <p class="text-lg font-bold text-purple-600">{{ $totalBulanIni }}</p>
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Keanggotaan</label>
                    <select name="status_keanggotaan"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Gabung Selesai</label>
                    <input type="date"
                           name="tanggal_selesai"
                           value="{{ request('tanggal_selesai') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">{{ $anggota->count() }}</span> data
                    @if(request()->hasAny(['search', 'status_keanggotaan', 'jenis_anggota', 'tanggal_mulai', 'tanggal_selesai']))
                        dari hasil filter
                    @endif
                </div>
                <div class="flex space-x-2">
                    <button type="submit"
                            class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('pengurus.anggota.index') }}"
                       class="px-4 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition-colors">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Anggota Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-list mr-2 text-primary-600"></i>
                        Daftar Anggota
                    </h2>
                    <p class="text-gray-500 text-xs">Semua anggota koperasi</p>
                </div>
                @if($anggota->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $anggota->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Anggota</p>
                    </div>
                @endif
            </div>
        </div>

        @if($anggota->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Anggota</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Gabung</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($anggota as $a)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- No. Anggota -->
                                <td class="px-4 py-3">
                                    <div class="text-xs font-mono font-semibold text-indigo-600">{{ $a->no_anggota_formatted ?? $a->no_anggota }}</div>
                                    <div class="text-xs text-gray-500">NIK: {{ $a->nik }}</div>
                                </td>

                                <!-- Nama -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $a->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $a->jenis_kelamin_label }} • {{ $a->usia ?? 0 }} th</div>
                                    @if($a->pekerjaan)
                                        <div class="text-xs text-gray-400">{{ $a->pekerjaan }}</div>
                                    @endif
                                </td>

                                <!-- Kontak -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-phone text-gray-400 mr-1 text-xs"></i>{{ $a->no_hp }}
                                    </div>
                                    @if($a->email)
                                        <div class="text-xs text-gray-500 truncate">{{ $a->email }}</div>
                                    @endif
                                </td>

                                <!-- Tanggal Gabung -->
                                <td class="px-4 py-3">
                                    @if($a->tanggal_gabung)
                                        <div class="text-sm text-gray-900">{{ $a->tanggal_gabung->format('d/m/Y') }}</div>
                                    @else
                                        <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>

                                <!-- Jenis -->
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                        @if($a->jenis_anggota == 'biasa')
                                            bg-blue-100 text-blue-800
                                        @elseif($a->jenis_anggota == 'luar_biasa')
                                            bg-purple-100 text-purple-800
                                        @else
                                            bg-orange-100 text-orange-800
                                        @endif">
                                        {{ $a->jenis_label }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                        @if($a->status_keanggotaan == 'aktif')
                                            bg-green-100 text-green-800
                                        @elseif($a->status_keanggotaan == 'nonaktif')
                                            bg-gray-100 text-gray-800
                                        @elseif($a->status_keanggotaan == 'keluar')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif">
                                        @if($a->status_keanggotaan == 'aktif')
                                            <i class="fas fa-check-circle mr-1"></i>
                                        @elseif($a->status_keanggotaan == 'keluar')
                                            <i class="fas fa-user-times mr-1"></i>
                                        @elseif($a->status_keanggotaan == 'meninggal')
                                            <i class="fas fa-cross mr-1"></i>
                                        @else
                                            <i class="fas fa-pause-circle mr-1"></i>
                                        @endif
                                        {{ $a->status_label }}
                                    </span>
                                    @if($a->tanggal_keluar)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $a->tanggal_keluar->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        @if($a->status_keanggotaan == 'aktif')
                                            <a href="{{ route('pengurus.anggota.keluar', $a->id) }}"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-600 hover:text-orange-700 transition-colors"
                                               title="Tandai Keluar">
                                                <i class="fas fa-user-times text-sm"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('pengurus.anggota.reaktif', $a->id) }}"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 hover:text-green-700 transition-colors"
                                               title="Aktifkan Kembali">
                                                <i class="fas fa-user-check text-sm"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('pengurus.anggota.edit', $a->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 transition-colors"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @if($a->user_id != auth()->id())
                                            <form action="{{ route('pengurus.anggota.destroy', $a->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 transition-colors"
                                                        title="Hapus">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $anggota->links('pagination.custom') }}
            </div>

        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Anggota</h3>
                    <p class="text-gray-600 mb-6 text-sm">Tambahkan anggota pertama ke koperasi</p>
                    <a href="{{ route('pengurus.anggota.create') }}" class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Anggota
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Manajemen Anggota
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-700">
            <div>
                <h4 class="font-semibold mb-2">Status Keanggotaan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Aktif:</strong> Anggota yang masih aktif berpartisipasi</li>
                    <li><strong>Non-aktif:</strong> Anggota yang tidak aktif untuk sementara</li>
                    <li><strong>Keluar:</strong> Anggota yang keluar dari koperasi</li>
                    <li><strong>Meninggal:</strong> Anggota yang telah meninggal dunia</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Jenis Anggota:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Biasa:</strong> Anggota reguler dengan hak biasa</li>
                    <li><strong>Luar Biasa:</strong> Anggota dengan hak khusus</li>
                    <li><strong>Kehormatan:</strong> Anggota penghargaan/tokoh masyarakat</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Format Nomor Anggota:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Format:</strong> YYMM + . + 5 digit nomor urut</li>
                    <li><strong>Contoh:</strong> 2512.00001 (Desember 2025)</li>
                    <li><strong>Reset:</strong> Nomor urut reset setiap bulan baru</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
