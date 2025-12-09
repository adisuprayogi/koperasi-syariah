@extends('layouts.app')

@section('title', 'Pengajuan Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengajuan Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Kelola pengajuan pembiayaan Anda</p>
        </div>
        <a href="{{ route('anggota.pengajuan.create') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Ajukan Pembiayaan Baru
        </a>
    </div>

    <!-- Info Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi Pengajuan</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Minimal pengajuan: Rp 1.000.000</li>
                        <li>Maksimal tenor: 60 bulan (5 tahun)</li>
                        <li>Waktu proses: 3-7 hari kerja</li>
                        <li>Dokumen wajib: KTP, KK, Slip Gaji</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-gray-100 rounded-full">
                    <i class="fas fa-list text-gray-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Pengajuan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pengajuans->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Menunggu Verifikasi</p>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ $pengajuans->whereIn('status', ['diajukan', 'verifikasi'])->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Disetujui</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $pengajuans->whereIn('status', ['approved', 'cair'])->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-money-bill-wave text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Dicairkan</p>
                    <p class="text-2xl font-bold text-purple-600">
                        Rp {{ number_format($pengajuans->where('status', 'cair')->sum('jumlah_cair'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('anggota.pengajuan.index') }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                Semua
            </a>
            <a href="{{ route('anggota.pengajuan.index', ['status' => 'pending']) }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Menunggu Verifikasi
            </a>
            <a href="{{ route('anggota.pengajuan.index', ['status' => 'approved']) }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Disetujui
            </a>
            <a href="{{ route('anggota.pengajuan.index', ['status' => 'rejected']) }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Ditolak
            </a>
        </nav>
    </div>

    <!-- Pengajuan List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full divide-y divide-gray-200" style="table-layout: fixed;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Pembiayaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tenor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengajuans as $pengajuan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap truncate">
                                <div class="text-sm font-medium text-gray-900">{{ $pengajuan->kode_pengajuan }}</div>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengajuan->created_at_formatted }}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengajuan->jenisPembiayaan->nama_pembiayaan }}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $pengajuan->jumlah_pengajuan_formatted }}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengajuan->tenor }} bulan
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap truncate">
                                {!! $pengajuan->status_label !!}
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('anggota.pengajuan.show', $pengajuan->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900 mr-2"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(in_array($pengajuan->status, ['draft', 'rejected']))
                                    <a href="{{ route('anggota.pengajuan.edit', $pengajuan->id) }}"
                                       class="text-blue-600 hover:text-blue-900 mr-2"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($pengajuan->status == 'draft')
                                        <form action="{{ route('anggota.pengajuan.submit', $pengajuan->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-2"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengajukan permohonan ini?')"
                                                    title="Ajukan">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('anggota.pengajuan.destroy', $pengajuan->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg">Belum ada pengajuan pembiayaan</p>
                                <p class="text-sm mt-2">Klik "Ajukan Pembiayaan Baru" untuk membuat pengajuan pertama Anda</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection