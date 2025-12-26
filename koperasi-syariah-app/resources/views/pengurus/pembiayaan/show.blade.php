@extends('layouts.app')

@section('title', 'Detail Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Informasi lengkap dan jadwal angsuran</p>
        </div>
        <a href="{{ route('pengurus.pembiayaan.index') }}"
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Informasi Pembiayaan -->
        <div class="bg-white rounded-lg shadow border-l-4 border-blue-500">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Informasi Pembiayaan
                </h2>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Kode Pengajuan</p>
                            <p class="font-medium text-gray-900">{{ $pengajuan->kode_pengajuan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p>{!! $pengajuan->status_label !!}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Anggota</p>
                            <p class="font-medium text-gray-900">{{ $pengajuan->anggota->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">No. Anggota</p>
                            <p class="font-medium text-gray-900">{{ $pengajuan->anggota->no_anggota }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Jenis Pembiayaan</p>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $pengajuan->jenisPembiayaan->nama_pembiayaan }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tujuan</p>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $pengajuan->tujuan_pembiayaan_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Keuangan -->
        <div class="bg-white rounded-lg shadow border-l-4 border-green-500">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calculator mr-2 text-green-500"></i>
                    Detail Keuangan
                </h2>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Jumlah Pengajuan</p>
                            <p class="text-xl font-bold text-blue-600">{{ $pengajuan->jumlah_pengajuan_formatted }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tenor</p>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $pengajuan->tenor }} bulan
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Margin</p>
                            <p class="font-medium">{{ $pengajuan->margin_percent }}% ({{ $pengajuan->jumlah_margin_formatted }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Angsuran/bulan</p>
                            <p class="text-xl font-bold text-green-600">{{ $pengajuan->total_angsuran_formatted }}</p>
                        </div>
                    </div>
                    @if($pengajuan->status == 'cair')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Cair</p>
                            <p class="font-medium">{{ $pengajuan->tanggal_cair ? date('d M Y', strtotime($pengajuan->tanggal_cair)) : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jumlah Cair</p>
                            <p class="text-xl font-bold text-indigo-600">{{ $pengajuan->jumlah_cair_formatted ?? '-' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Progress & Next Payment -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Statistik Angsuran -->
        <div class="md:col-span-2 bg-white rounded-lg shadow border-l-4 border-indigo-500">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-indigo-500"></i>
                    Statistik Angsuran
                </h2>
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ $totalAngsuran }}</p>
                        <p class="text-sm text-gray-500">Total</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $totalTerbayar }}</p>
                        <p class="text-sm text-gray-500">Terbayar</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-yellow-600">{{ $totalPending }}</p>
                        <p class="text-sm text-gray-500">Pending</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-red-600">{{ $totalTerlambat }}</p>
                        <p class="text-sm text-gray-500">Terlambat</p>
                    </div>
                </div>
                <?php $progress = $totalAngsuran > 0 ? ($totalTerbayar / $totalAngsuran) * 100 : 0; ?>
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Progress Pembayaran</span>
                        <span class="font-medium">{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $totalTerbayar }} dari {{ $totalAngsuran }} angsuran</p>
                </div>
            </div>
        </div>

        <!-- Angsuran Berikutnya -->
        <div class="bg-white rounded-lg shadow border-l-4 border-yellow-500">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-yellow-500"></i>
                    Angsuran Berikutnya
                </h2>
                @if($angsuranBerikutnya)
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                            <span class="text-xl font-bold text-yellow-800">{{ $angsuranBerikutnya->angsuran_ke }}</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Angsuran Ke-{{ $angsuranBerikutnya->angsuran_ke }}</h3>
                        <div class="space-y-2">
                            <p class="text-sm">
                                <span class="text-gray-500">Jatuh Tempo:</span>
                                <span class="font-medium {{ $angsuranBerikutnya->status == 'terlambat' ? 'text-red-600' : '' }}">
                                    {{ $angsuranBerikutnya->tanggal_jatuh_tempo_formatted }}
                                </span>
                            </p>
                            <p class="text-2xl font-bold text-green-600">{{ $angsuranBerikutnya->jumlah_angsuran_formatted }}</p>
                        </div>
                        <a href="{{ route('pengurus.pembiayaan.bayar', [$pengajuan->id, $angsuranBerikutnya->id]) }}"
                           class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Bayar Sekarang
                        </a>
                    </div>
                @else
                    <div class="text-center text-gray-500">
                        <i class="fas fa-check-circle text-5xl mb-4"></i>
                        <p>Semua angsuran telah terbayar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert untuk generate jadwal -->
    @if($pengajuan->status == 'cair' && $pengajuan->angsurans->count() == 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Jadwal Angsuran Belum Dibuat</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Buat jadwal angsuran untuk memulai tracking pembayaran.</p>
                </div>
                <div class="mt-4">
                    <form action="{{ route('pengurus.pembiayaan.generate-jadwal', $pengajuan->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors"
                                onclick="return confirm('Generate jadwal angsuran untuk pembiayaan ini?')">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Generate Jadwal Angsuran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Jadwal Angsuran Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>
                Jadwal Angsuran
            </h2>
            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                {{ $pengajuan->angsurans->count() }} Angsuran
            </span>
        </div>
        @if($pengajuan->angsurans->count() > 0)
        <div class="overflow-x-auto lg:overflow-x-visible">
            <table class="w-full divide-y divide-gray-200" style="table-layout: fixed;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pokok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengajuan->angsurans as $angsuran)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $angsuran->angsuran_ke }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="{{ $angsuran->status == 'terlambat' ? 'text-red-600 font-medium' : '' }}">
                                {{ $angsuran->tanggal_jatuh_tempo_formatted }}
                            </div>
                            @if($angsuran->status == 'terlambat')
                                <div class="text-xs text-red-500">{{ $angsuran->keterlambat }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $angsuran->jumlah_pokok_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $angsuran->jumlah_margin_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-blue-600">{{ $angsuran->jumlah_angsuran_formatted }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-green-600">Tanpa Denda</span>
                            @if($angsuran->hari_terlambat > 0 && $angsuran->status == 'terbayar')
                                <br><span class="text-xs text-gray-500">({{ $angsuran->hari_terlambat }} hr telat)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{!! $angsuran->status_label !!}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $angsuran->tanggal_bayar_formatted }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($angsuran->status != 'terbayar')
                                <a href="{{ route('pengurus.pembiayaan.bayar', [$pengajuan->id, $angsuran->id]) }}"
                                   class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-800 text-xs font-medium rounded-md transition-colors">
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    Bayar
                                </a>
                            @else
                                <div class="space-x-2">
                                    @if($angsuran->bukti_pembayaran)
                                        <a href="{{ asset('storage/bukti_pembayaran/' . $angsuran->bukti_pembayaran) }}"
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-md transition-colors">
                                            <i class="fas fa-file mr-1"></i>
                                            Bukti
                                        </a>
                                    @endif
                                    <a href="{{ route('pengurus.pembiayaan.print-bukti', [$pengajuan->id, $angsuran->id]) }}"
                                       class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium rounded-md transition-colors">
                                        <i class="fas fa-print mr-1"></i>
                                        Cetak
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada jadwal angsuran</h3>
            <p class="text-gray-500">Generate jadwal angsuran untuk melihat detail pembayaran</p>
        </div>
        @endif
    </div>
</div>
@endsection