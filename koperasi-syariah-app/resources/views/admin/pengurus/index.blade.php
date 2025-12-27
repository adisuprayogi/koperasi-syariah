@extends('layouts.app')

@section('title', 'Manajemen Pengurus')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Pengurus</h1>
            <p class="text-gray-600 mt-2">Kelola data pengurus koperasi</p>
        </div>
        <a href="{{ route('admin.pengurus.create') }}"
           class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Pengurus
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
                    <h3 class="text-xs font-medium text-gray-500">Total</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $pengurus->count() }}</p>
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
                    <p class="text-lg font-bold text-green-600">{{ $pengurus->where('status', 'aktif')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-crown text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Ketua</h3>
                    <p class="text-lg font-bold text-yellow-600">{{ $pengurus->where('posisi', 'ketua')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Sekretaris</h3>
                    <p class="text-lg font-bold text-blue-600">{{ $pengurus->where('posisi', 'sekretaris')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengurus Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-list mr-2 text-primary-600"></i>
                        Daftar Pengurus
                    </h2>
                    <p class="text-gray-500 text-xs">Semua pengurus koperasi</p>
                </div>
                @if($pengurus->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $pengurus->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Pengurus</p>
                    </div>
                @endif
            </div>
        </div>

        @if($pengurus->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jabatan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl Menjabat</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pengurus as $p)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Nama -->
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                                                <i class="fas fa-user text-primary-600 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-900">{{ $p->user?->name ?? 'Data tidak tersedia' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $p->user?->email ?? '-' }}</div>
                                    @if($p->user && $p->user->first_login)
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 mt-1">
                                            <i class="fas fa-key mr-1"></i>Belum ganti password
                                        </span>
                                    @endif
                                </td>

                                <!-- NIK -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900 font-mono">{{ $p->nik ?? '-' }}</div>
                                </td>

                                <!-- Jabatan -->
                                <td class="px-4 py-3">
                                    @if($p->posisi == 'ketua')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-crown mr-1"></i>
                                            {{ ucfirst($p->posisi_label) }}
                                        </span>
                                    @elseif($p->posisi == 'sekretaris')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            {{ ucfirst($p->posisi_label) }}
                                        </span>
                                    @elseif($p->posisi == 'bendahara')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-coins mr-1"></i>
                                            {{ ucfirst($p->posisi_label) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ ucfirst($p->posisi_label) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Kontak -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">
                                        @if($p->no_telepon)
                                            <i class="fas fa-phone text-gray-400 mr-1"></i>{{ $p->no_telepon }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    @if($p->status)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Tanggal Menjabat -->
                                <td class="px-4 py-3">
                                    @if($p->tanggal_menjabat)
                                        <div class="text-sm text-gray-900">{{ $p->tanggal_menjabat->format('d/m/Y') }}</div>
                                    @else
                                        <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('admin.pengurus.edit', $p->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 transition-colors"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @if($p->user_id != auth()->id())
                                            <form action="{{ route('admin.pengurus.destroy', $p->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengurus ini?')">
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
            @if($pengurus->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $pengurus->links('pagination.custom') }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pengurus</h3>
                    <p class="text-gray-600 mb-6 text-sm">Tambahkan pengurus pertama koperasi</p>
                    <a href="{{ route('admin.pengurus.create') }}" class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pengurus
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Manajemen Pengurus
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
            <div>
                <h4 class="font-semibold mb-2">Struktur Pengurus:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Ketua:</strong> Pemimpin tertinggi koperasi</li>
                    <li><strong>Sekretaris:</strong> Mengelola administrasi dan dokumentasi</li>
                    <li><strong>Bendahara:</strong> Mengelola keuangan dan transaksi</li>
                    <li><strong>Pengurus Lainnya:</strong> Anggota pengurus dengan tugas khusus</li>
                </ul>
            </div>
            <div>
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
