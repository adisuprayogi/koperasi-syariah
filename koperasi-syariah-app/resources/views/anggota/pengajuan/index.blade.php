@extends('layouts.app')

@section('title', 'Pengajuan Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengajuan Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Kelola pengajuan pembiayaan Anda</p>
        </div>
        <a href="{{ route('anggota.pengajuan.create') }}"
           class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Ajukan Baru
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-list text-gray-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Total</p>
                    <p class="text-lg font-bold text-gray-900">{{ $pengajuans->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Pending</p>
                    <p class="text-lg font-bold text-yellow-600">{{ $pengajuans->whereIn('status', ['diajukan', 'verifikasi'])->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Disetujui</p>
                    <p class="text-lg font-bold text-green-600">{{ $pengajuans->whereIn('status', ['approved', 'cair'])->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-500">Dicairkan</p>
                    <p class="text-lg font-bold text-purple-600">Rp {{ number_format($pengajuans->where('status', 'cair')->sum('jumlah_cair'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto">
            <a href="{{ route('anggota.pengajuan.index') }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 {{ request('status') == '' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Semua
            </a>
            <a href="{{ route('anggota.pengajuan.index', ['status' => 'pending']) }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 {{ request('status') == 'pending' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Menunggu Verifikasi
            </a>
            <a href="{{ route('anggota.pengajuan.index', ['status' => 'approved']) }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 {{ request('status') == 'approved' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Disetujui
            </a>
            <a href="{{ route('anggota.pengajuan.index', ['status' => 'rejected']) }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 {{ request('status') == 'rejected' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Ditolak
            </a>
        </nav>
    </div>

    <!-- Table View Pengajuan -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-file-alt mr-2 text-primary-600"></i>
                        Daftar Pengajuan
                    </h2>
                    <p class="text-gray-500 text-xs">Semua pengajuan pembiayaan Anda</p>
                </div>
                @if($pengajuans->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $pengajuans->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Pengajuan</p>
                    </div>
                @endif
            </div>
        </div>

        @if($pengajuans->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plafond</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tenor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pengajuans as $pengajuan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Tanggal -->
                                <td class="px-4 py-3">
                                    @if($pengajuan->created_at)
                                        <div class="text-sm font-medium text-gray-900">{{ $pengajuan->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $pengajuan->created_at->format('H:i') }}</div>
                                    @else
                                        <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>

                                <!-- Kode -->
                                <td class="px-4 py-3">
                                    <div class="text-xs font-mono font-semibold text-indigo-600">{{ $pengajuan->kode_pengajuan }}</div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $pengajuan->jenisPembiayaan->nama_pembiayaan ?? '-' }}</div>
                                </td>

                                <!-- Plafond -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-blue-600">Rp {{ number_format($pengajuan->jumlah_pengajuan, 0, ',', '.') }}</div>
                                </td>

                                <!-- Tenor -->
                                <td class="px-4 py-3 text-center">
                                    <div class="text-sm text-gray-900">{{ $pengajuan->tenor }} Bln</div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    @switch($pengajuan->status)
                                        @case('draft')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <i class="fas fa-file mr-1"></i>Draft
                                            </span>
                                            @break
                                        @case('diajukan')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-paper-plane mr-1"></i>Diajukan
                                            </span>
                                            @break
                                        @case('verifikasi')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-search mr-1"></i>Verifikasi
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Disetujui
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Ditolak
                                            </span>
                                            @break
                                        @case('cair')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                <i class="fas fa-money-bill-wave mr-1"></i>Cair
                                            </span>
                                            @break
                                        @case('lunas')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                <i class="fas fa-check-double mr-1"></i>Lunas
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $pengajuan->status }}
                                            </span>
                                    @endswitch
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('anggota.pengajuan.show', $pengajuan->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 transition-colors"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        @if(in_array($pengajuan->status, ['draft', 'rejected']))
                                            <a href="{{ route('anggota.pengajuan.edit', $pengajuan->id) }}"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 hover:text-blue-700 transition-colors"
                                               title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                        @endif
                                        @if($pengajuan->status == 'draft')
                                            <form action="{{ route('anggota.pengajuan.submit', $pengajuan->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('Apakah Anda yakin ingin mengajukan permohonan ini?')"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 hover:text-green-700 transition-colors"
                                                        title="Ajukan">
                                                    <i class="fas fa-paper-plane text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if(in_array($pengajuan->status, ['draft', 'rejected']))
                                            <form action="{{ route('anggota.pengajuan.destroy', $pengajuan->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')"
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

        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pengajuan</h3>
                    <p class="text-gray-600 mb-6 text-sm">Ajukan pembiayaan sekarang untuk kebutuhan Anda</p>
                    <a href="{{ route('anggota.pengajuan.create') }}" class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Ajukan Pembiayaan
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
