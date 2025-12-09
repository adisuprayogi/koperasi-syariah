@extends('layouts.app')

@section('title', 'Manajemen Pengurus')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Pengurus</h1>
            <p class="text-gray-600 mt-2">Kelola data pengurus koperasi</p>
        </div>
        <div>
            <a href="{{ route('admin.pengurus.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Pengurus
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
                    <h3 class="text-sm font-medium text-gray-500">Total Pengurus</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $pengurus->count() }}</p>
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
                    <p class="text-2xl font-bold text-green-600">{{ $pengurus->where('status', 'aktif')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-crown text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Ketua</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $pengurus->where('posisi', 'ketua')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Sekretaris</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $pengurus->where('posisi', 'sekretaris')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-coins text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Bendahara</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $pengurus->where('posisi', 'bendahara')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengurus Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Pengurus</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Informasi Pengurus
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jabatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kontak
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Menjabat
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengurus as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $p->user?->name ?? 'Data tidak tersedia' }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $p->user?->email ?? 'Email tidak tersedia' }}</div>
                                    <div class="text-xs text-gray-400">NIK: {{ $p->nik ?? '-' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($p->posisi == 'ketua')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($p->posisi == 'sekretaris')
                                        bg-blue-100 text-blue-800
                                    @elseif($p->posisi == 'bendahara')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($p->posisi_label) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($p->no_telepon)
                                        <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $p->no_telepon }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                                @if($p->user && $p->user->first_login)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 mt-1">
                                        <i class="fas fa-key mr-1"></i>Belum ganti password
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->status)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $p->tanggal_menjabat->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.pengurus.edit', $p->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($p->user_id != auth()->id())
                                        <form action="{{ route('admin.pengurus.destroy', $p->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengurus ini? Data akan diarsipkan di database.')">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Belum ada data pengurus</p>
                                <p class="text-gray-400 text-sm mt-1">
                                    <a href="{{ route('admin.pengurus.create') }}" class="text-indigo-600 hover:text-indigo-500">
                                        Tambah pengurus pertama
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
            <i class="fas fa-info-circle mr-2"></i>Informasi Manajemen Pengurus
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Struktur Pengurus:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Ketua:</strong> Pemimpin tertinggi koperasi</li>
                    <li><strong>Sekretaris:</strong> Mengelola administrasi dan dokumentasi</li>
                    <li><strong>Bendahara:</strong> Mengelola keuangan dan transaksi</li>
                    <li><strong>Pengurus Lainnya:</strong> Anggota pengurus dengan tugas khusus</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Catatan Penting:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Setiap pengurus akan dibuatkan akun otomatis</li>
                    <li>Password default akan diminta ubah pada login pertama</li>
                    <li>Pengurus dapat mengakses semua fitur kecuali menu admin</li>
                    <li>Hapus pengurus akan menghapus akun terkait</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection