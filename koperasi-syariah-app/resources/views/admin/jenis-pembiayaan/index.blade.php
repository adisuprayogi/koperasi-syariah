@extends('layouts.app')

@section('title', 'Master Jenis Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Master Jenis Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Kelola jenis pembiayaan koperasi syariah</p>
        </div>
        <a href="{{ route('admin.jenis-pembiayaan.create') }}"
           class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Jenis Pembiayaan
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-primary-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Jenis</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $jenisPembiayaan->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Murabahah</h3>
                    <p class="text-lg font-bold text-purple-600">{{ $jenisPembiayaan->where('tipe_pembiayaan', 'murabahah')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Mudharabah</h3>
                    <p class="text-lg font-bold text-blue-600">{{ $jenisPembiayaan->where('tipe_pembiayaan', 'mudharabah')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-handshake text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Musyarakah</h3>
                    <p class="text-lg font-bold text-yellow-600">{{ $jenisPembiayaan->where('tipe_pembiayaan', 'musyarakah')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-donate text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Qardh</h3>
                    <p class="text-lg font-bold text-green-600">{{ $jenisPembiayaan->where('tipe_pembiayaan', 'qardh')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jenis Pembiayaan Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-list mr-2 text-primary-600"></i>
                        Daftar Jenis Pembiayaan
                    </h2>
                    <p class="text-gray-500 text-xs">Semua jenis pembiayaan koperasi</p>
                </div>
                @if($jenisPembiayaan->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $jenisPembiayaan->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Jenis Pembiayaan</p>
                    </div>
                @endif
            </div>
        </div>

        @if($jenisPembiayaan->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pembiayaan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Margin/Bagi Hasil</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plafon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jangka Waktu</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($jenisPembiayaan as $jp)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Kode -->
                                <td class="px-4 py-3">
                                    <span class="text-xs font-mono font-semibold bg-gray-100 px-2 py-1 rounded text-gray-700">
                                        {{ $jp->kode_jenis }}
                                    </span>
                                </td>

                                <!-- Nama Pembiayaan -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $jp->nama_pembiayaan }}</div>
                                    @if($jp->keterangan)
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $jp->keterangan }}</div>
                                    @endif
                                </td>

                                <!-- Tipe -->
                                <td class="px-4 py-3">
                                    @if($jp->tipe_pembiayaan == 'murabahah')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <i class="fas fa-shopping-cart mr-1"></i>{{ $jp->tipe_label }}
                                        </span>
                                    @elseif($jp->tipe_pembiayaan == 'mudharabah')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-chart-line mr-1"></i>{{ $jp->tipe_label }}
                                        </span>
                                    @elseif($jp->tipe_pembiayaan == 'musyarakah')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-handshake mr-1"></i>{{ $jp->tipe_label }}
                                        </span>
                                    @elseif($jp->tipe_pembiayaan == 'qardh')
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-donate mr-1"></i>{{ $jp->tipe_label }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-home mr-1"></i>{{ $jp->tipe_label }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Margin/Bagi Hasil -->
                                <td class="px-4 py-3">
                                    @if($jp->tipe_pembiayaan == 'murabahah')
                                        <div class="text-sm font-semibold text-purple-600">{{ $jp->margin_formatted }}</div>
                                    @elseif(in_array($jp->tipe_pembiayaan, ['mudharabah', 'musyarakah']))
                                        <div class="text-sm font-semibold text-blue-600">{{ $jp->bagi_hasil_formatted }}</div>
                                    @elseif($jp->tipe_pembiayaan == 'ijarah')
                                        <div class="text-sm font-semibold text-red-600">{{ $jp->margin_formatted }}</div>
                                    @else
                                        <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>

                                <!-- Plafon -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ number_format($jp->minimal_pembiayaan, 0, ',', '.') }}</div>
                                    @if($jp->maksimal_pembiayaan)
                                        <div class="text-xs text-gray-500">Max: {{ number_format($jp->maksimal_pembiayaan, 0, ',', '.') }}</div>
                                    @else
                                        <div class="text-xs text-gray-400">Tanpa maksimal</div>
                                    @endif
                                </td>

                                <!-- Jangka Waktu -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $jp->jangka_waktu_min }} - {{ $jp->jangka_waktu_max }} bln</div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    @if($jp->status)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            <i class="fas fa-times-circle mr-1"></i>Non-aktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('admin.jenis-pembiayaan.edit', $jp->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 transition-colors"
                                           title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.jenis-pembiayaan.destroy', $jp->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis pembiayaan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 transition-colors"
                                                    title="Hapus">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
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
                        <i class="fas fa-hand-holding-usd text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Jenis Pembiayaan</h3>
                    <p class="text-gray-600 mb-6 text-sm">Tambahkan jenis pembiayaan pertama koperasi</p>
                    <a href="{{ route('admin.jenis-pembiayaan.create') }}" class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Jenis Pembiayaan
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Jenis Pembiayaan Syariah
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
            <div>
                <h4 class="font-semibold mb-2">Jenis Pembiayaan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Murabahah:</strong> Jual beli barang dengan keuntungan margin yang disepakati</li>
                    <li><strong>Mudharabah:</strong> Pembiayaan bagi hasil antara pemilik modal dan pengelola</li>
                    <li><strong>Musyarakah:</strong> Kerja sama dengan pembagian keuntungan sesuai kesepakatan</li>
                    <li><strong>Qardh:</strong> Pinjaman baik tanpa bunga/margin (sosial)</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Prinsip Syariah:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Tidak mengandung riba (bunga)</li>
                    <li>Menggunakan sistem bagi hasil (Mudharabah/Musyarakah)</li>
                    <li>Jual beli dengan margin transparan (Murabahah)</li>
                    <li>Sesuai prinsip ekonomi Islam dan Koperasi Syariah</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
