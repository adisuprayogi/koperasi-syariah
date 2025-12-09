@extends('layouts.app')

@section('title', 'Master Jenis Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Master Jenis Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Kelola jenis pembiayaan koperasi syariah</p>
        </div>
        <div>
            <a href="{{ route('admin.jenis-pembiayaan.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Jenis Pembiayaan
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <i class="fas fa-hand-holding-usd text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Jenis</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $jenisPembiayaan->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Murabahah</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $jenisPembiayaan->where('tipe_pembiayaan', 'murabahah')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Mudharabah</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $jenisPembiayaan->where('tipe_pembiayaan', 'mudharabah')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-handshake text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Musyarakah</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $jenisPembiayaan->where('tipe_pembiayaan', 'musyarakah')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-donate text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Qardh</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $jenisPembiayaan->where('tipe_pembiayaan', 'qardh')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jenis Pembiayaan Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Jenis Pembiayaan</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Pembiayaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Margin/Bagi Hasil
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Plafon
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jangka Waktu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jenisPembiayaan as $jp)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                    {{ $jp->kode_jenis }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $jp->nama_pembiayaan }}
                                </div>
                                @if($jp->keterangan)
                                    <div class="text-xs text-gray-500 truncate max-w-xs">
                                        {{ $jp->keterangan }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($jp->tipe_pembiayaan == 'murabahah')
                                        bg-purple-100 text-purple-800
                                    @elseif($jp->tipe_pembiayaan == 'mudharabah')
                                        bg-blue-100 text-blue-800
                                    @elseif($jp->tipe_pembiayaan == 'musyarakah')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-green-100 text-green-800
                                    @endif">
                                    {{ $jp->tipe_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($jp->tipe_pembiayaan == 'murabahah')
                                        Margin: {{ $jp->margin_formatted }}
                                    @elseif(in_array($jp->tipe_pembiayaan, ['mudharabah', 'musyarakah']))
                                        Bagi Hasil: {{ $jp->bagi_hasil_formatted }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>Min: Rp {{ number_format($jp->minimal_pembiayaan, 0, ',', '.') }}</div>
                                @if($jp->maksimal_pembiayaan)
                                    <div>Max: Rp {{ number_format($jp->maksimal_pembiayaan, 0, ',', '.') }}</div>
                                @else
                                    <div class="text-xs text-gray-400">Tanpa maksimal</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $jp->jangka_waktu_min }} - {{ $jp->jangka_waktu_max }} bulan</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($jp->status)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.jenis-pembiayaan.edit', $jp->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.jenis-pembiayaan.destroy', $jp->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis pembiayaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <i class="fas fa-hand-holding-usd text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Belum ada jenis pembiayaan</p>
                                <p class="text-gray-400 text-sm mt-1">
                                    <a href="{{ route('admin.jenis-pembiayaan.create') }}" class="text-indigo-600 hover:text-indigo-500">
                                        Tambah jenis pembiayaan pertama
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
            <i class="fas fa-info-circle mr-2"></i>Informasi Jenis Pembiayaan Syariah
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Jenis Pembiayaan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Murabahah:</strong> Jual beli barang dengan keuntungan margin yang disepakati</li>
                    <li><strong>Mudharabah:</strong> Pembiayaan bagi hasil antara pemilik modal dan pengelola</li>
                    <li><strong>Musyarakah:</strong> Kerja sama dengan pembagian keuntungan sesuai kesepakatan</li>
                    <li><strong>Qardh:</strong> Pinjaman baik tanpa bunga/margin (sosial)</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Prinsip Syariah:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>• Tidak mengandung riba (bunga)</li>
                    <li>• Menggunakan sistem bagi hasil (Mudharabah/Musyarakah)</li>
                    <li>• Jual beli dengan margin transparan (Murabahah)</li>
                    <li>• Sesuai prinsip ekonomi Islam dan Koperasi Syariah</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection