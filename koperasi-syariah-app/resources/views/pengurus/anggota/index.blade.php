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
                    <p class="text-2xl font-bold text-gray-900">{{ $anggota->count() }}</p>
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
                    <p class="text-2xl font-bold text-green-600">{{ $anggota->where('status_keanggotaan', 'aktif')->count() }}</p>
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
                    <p class="text-2xl font-bold text-blue-600">{{ $anggota->where('jenis_anggota', 'biasa')->count() }}</p>
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
                    <p class="text-2xl font-bold text-purple-600">{{ $anggota->where('tanggal_gabung', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Anggota Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Anggota</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Informasi Anggota
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Gabung
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Akun
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($anggota as $a)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $a->nama_lengkap }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $a->no_anggota_formatted ?? $a->no_anggota }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ $a->nik }} • {{ $a->jenis_kelamin_label }} • {{ $a->usia ?? 0 }} tahun
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        <i class="fas fa-calendar-alt mr-1"></i>{{ $a->periode_pendaftaran }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $a->no_hp }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>{{ $a->email }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    <i class="fas fa-briefcase text-gray-400 mr-1"></i>{{ $a->pekerjaan }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $a->tanggal_gabung->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
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