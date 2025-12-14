@extends('layouts.app')

@section('title', 'Master Jenis Simpanan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Master Jenis Simpanan</h1>
            <p class="text-gray-600 mt-2">Kelola jenis simpanan koperasi syariah</p>
        </div>
        <div>
            <a href="{{ route('admin.jenis-simpanan.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Jenis Simpanan
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-full">
                    <i class="fas fa-wallet text-indigo-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Jenis</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $jenisSimpanan->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-coins text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Modal</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $jenisSimpanan->where('tipe_simpanan', 'modal')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-shield-alt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Pokok</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $jenisSimpanan->where('tipe_simpanan', 'pokok')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-hand-holding-usd text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Wajib</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $jenisSimpanan->where('tipe_simpanan', 'wajib')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-donate text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Sukarela</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $jenisSimpanan->where('tipe_simpanan', 'sukarela')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Jenis Simpanan Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-3 py-3 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Jenis Simpanan</h2>
        </div>

        <!-- Mobile Responsive Table -->
        <div class="overflow-x-auto shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Simpanan
                        </th>
                        <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipe
                        </th>
                        <th class="hidden md:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nisbah
                        </th>
                        <th class="hidden lg:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Batas Setoran
                        </th>
                        <th class="hidden xl:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tarik
                        </th>
                        <th class="hidden 2xl:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jenisSimpanan as $js)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3 whitespace-nowrap">
                                <span class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                    {{ $js->kode_jenis }}
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $js->nama_simpanan }}
                                </div>
                                @if($js->keterangan)
                                    <div class="text-xs text-gray-500 truncate max-w-xs">
                                        {{ $js->keterangan }}
                                    </div>
                                @endif
                                <div class="sm:hidden text-xs text-gray-400 mt-1">
                                    {{ $js->tipe_simpanan_label }}
                                </div>
                                <div class="md:hidden text-xs text-gray-400">
                                    Nisbah: {{ $js->nisbah ? $js->nisbah . '%' : '-' }}
                                </div>
                                <div class="lg:hidden text-xs text-gray-400">
                                    Min: {{ $js->batas_min_setoran ? number_format($js->batas_min_setoran, 0, ',', '.') : '-' }}
                                </div>
                                <div class="xl:hidden mt-1">
                                    <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $js->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $js->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </td>
                            <td class="hidden sm:table-cell px-3 py-3 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($js->tipe_simpanan == 'modal')
                                        bg-purple-100 text-purple-800
                                    @elseif($js->tipe_simpanan == 'pokok')
                                        bg-blue-100 text-blue-800
                                    @elseif($js->tipe_simpanan == 'wajib')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-green-100 text-green-800
                                    @endif">
                                    {{ $js->tipe_label }}
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $js->nisbah }}%
                                </div>
                                @if($js->tipe_simpanan == 'sukarela')
                                    <div class="text-xs text-gray-500">Bergulir</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                <div>Min: Rp {{ number_format($js->minimal_setor, 0, ',', '.') }}</div>
                                @if($js->maksimal_setor)
                                    <div>Max: Rp {{ number_format($js->maksimal_setor, 0, ',', '.') }}</div>
                                @else
                                    <div class="text-xs text-gray-400">Tanpa maksimal</div>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($js->bisa_ditarik)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Bisa
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Tidak
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($js->status)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Non-aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.jenis-simpanan.edit', $js->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.jenis-simpanan.destroy', $js->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis simpanan ini?')">
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
                                <i class="fas fa-wallet text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Belum ada jenis simpanan</p>
                                <p class="text-gray-400 text-sm mt-1">
                                    <a href="{{ route('admin.jenis-simpanan.create') }}" class="text-indigo-600 hover:text-indigo-500">
                                        Tambah jenis simpanan pertama
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
            <i class="fas fa-info-circle mr-2"></i>Informasi Jenis Simpanan Syariah
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Jenis Simpanan:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Simpanan Modal:</strong> Modal awal anggota untuk menjadi bagian koperasi</li>
                    <li><strong>Simpanan Pokok:</strong> Simpanan wajib pokok keanggotaan</li>
                    <li><strong>Simpanan Wajib:</strong> Simpanan rutin wajib per periode</li>
                    <li><strong>Simpanan Sukarela:</strong> Simpanan tambahan dengan nisbah/bagi hasil</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Fitur Syariah:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>• Menggunakan sistem nisbah/bagi hasil (tanpa bunga)</li>
                    <li>• Nisbah diberikan dalam persentase (%)</li>
                    <li>• Perhitungan nisbah sesuai periode (bulanan/tahunan)</li>
                    <li>• Sesuai prinsip ekonomi Islam</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection