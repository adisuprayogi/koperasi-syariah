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
                    @if($pengajuan->tipe_angsuran)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tipe Angsuran</p>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium @if($pengajuan->tipe_angsuran == 'flat') bg-green-100 text-green-800 @elseif($pengajuan->tipe_angsuran == 'menurun') bg-orange-100 text-orange-800 @else bg-purple-100 text-purple-800 @endif">
                                @if($pengajuan->tipe_angsuran == 'flat') Flat (Tetap) @elseif($pengajuan->tipe_angsuran == 'menurun') Menurun (Declining) @else Menaik (Stepped) @endif
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Jadwal</p>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $pengajuan->angsurans->count() }} Periode
                            </span>
                        </div>
                    </div>
                    @endif
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
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <a href="{{ route('pengurus.pembiayaan.bayar', [$pengajuan->id, $angsuranBerikutnya->id]) }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-money-bill-wave mr-2"></i>
                                Bayar Sekarang
                            </a>
                            <a href="{{ route('pengurus.pembiayaan.lunas_lebih_cepat', $pengajuan->id) }}"
                               class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                                <i class="fas fa-rocket mr-2"></i>
                                Lunas Lebih Cepat
                            </a>
                        </div>
                        @php
                            $sisaTotal = $pengajuan->sisaTotal();
                            $sisaFormatted = 'Rp ' . number_format($sisaTotal, 0, ',', '.');
                        @endphp
                        <p class="mt-3 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Sisa total: <strong class="text-purple-600">{{ $sisaFormatted }}</strong>
                        </p>
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
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800">Jadwal Angsuran Belum Dibuat</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Jadwal akan otomatis dibuat saat pencairan dana. Jika belum muncul, klik tombol di bawah untuk membuat secara manual.</p>
                </div>
                <div class="mt-3">
                    <form method="POST" action="{{ route('pengurus.pembiayaan.generate-jadwal', $pengajuan->id) }}" onsubmit="return confirm('Buat jadwal angsuran sekarang?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Buat Jadwal Sekarang
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
            <div class="flex items-center gap-3">
                <!-- {{-- Fitur Pelunasan Lebih Cepat --}}
                @if($pengajuan->status == 'cair' && $pengajuan->jadwalPending()->count() > 0)
                <button onclick="openLunasModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-bolt mr-2"></i>
                    Pelunasan Lebih Cepat
                </button>
                @endif -->
                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    {{ $pengajuan->angsurans->count() }} Angsuran
                </span>
            </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengajuan->angsurans as $jadwal)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $jadwal->angsuran_ke }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="{{ $jadwal->status == 'pending' && $jadwal->tanggal_jatuh_tempo < now() ? 'text-red-600 font-medium' : '' }}">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_jatuh_tempo)->format('d M Y') }}
                            </div>
                            @if($jadwal->status == 'pending' && $jadwal->tanggal_jatuh_tempo < now())
                                <div class="text-xs text-red-500">Telat {{ \Carbon\Carbon::parse($jadwal->tanggal_jatuh_tempo)->diffInDays(now()) }} hari</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($jadwal->jumlah_pokok, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($jadwal->jumlah_margin, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-blue-600">{{ number_format($jadwal->jumlah_angsuran, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{!! $jadwal->status_label ?? '' !!}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jadwal->tanggal_bayar ? \Carbon\Carbon::parse($jadwal->tanggal_bayar)->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($jadwal->status == 'pending')
                                <button onclick="openBayarModal({{ $jadwal->angsuran_ke }}, {{ number_format($jadwal->jumlah_angsuran, 2, '.', '') }})"
                                        class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-800 text-xs font-medium rounded-md transition-colors">
                                    <i class="fas fa-money-bill-wave mr-1"></i>
                                    Bayar
                                </button>
                            @elseif($jadwal->status == 'terbayar' || $jadwal->status == 'lunas_lebih_cepat')
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-md">
                                    <i class="fas fa-check mr-1"></i>
                                    Lunas
                                </span>
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
            <p class="text-gray-500">Jadwal akan otomatis dibuat setelah pencairan dana</p>
        </div>
        @endif
    </div>

    {{-- Modal Pelunasan Lebih Cepat --}}
    @if($pengajuan->status == 'cair' && $pengajuan->jadwalPending()->count() > 0)
    <div id="lunasModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Pelunasan Lebih Cepat
                </h3>
                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Lunasi semua sisa angsuran sekaligus untuk menyelesaikan pembiayaan lebih cepat.
                        </p>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sisa Periode:</span>
                            <span class="font-medium">{{ $pengajuan->periodePending() }} bulan</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Sisa:</span>
                            <span class="font-bold text-blue-600">{{ number_format($pengajuan->sisaJadwal(), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Pokok Sisa:</span>
                            <span class="font-medium">{{ number_format($pengajuan->jadwalPending()->sum('jumlah_pokok'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Margin Sisa:</span>
                            <span class="font-medium">{{ number_format($pengajuan->jadwalPending()->sum('jumlah_margin'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                        <textarea id="catatanLunas" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button onclick="closeLunasModal()" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                        Batal
                    </button>
                    <button onclick="submitLunas()" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Lunasi Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Bayar Per Periode --}}
    <div id="bayarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                    Bayar Angsuran Ke-<span id="periodeKeBayar"></span>
                </h3>
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-blue-800">Jumlah tagihan:</span>
                            <span id="jumlahTagihan" class="text-lg font-bold text-blue-600"></span>
                        </div>
                        @if($jadwal->jumlah_dibayar > 0)
                        <div class="flex justify-between items-center text-sm text-gray-600">
                            <span>Sudah dibayar:</span>
                            <span>{{ number_format($jadwal->jumlah_dibayar, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar <span class="text-gray-500">(boleh kurang dari tagihan)</span></label>
                        <input type="number" id="jumlahBayarInput" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan jumlah bayar" min="0" step="1000">
                        <!-- Info Perpanjangan - hidden by default -->
                        <div id="infoPerpanjangan" class="mt-2 hidden"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar</label>
                        <input type="date" id="tanggalBayar" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                        <textarea id="catatanBayar" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Tambahkan catatan..."></textarea>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button onclick="closeBayarModal()" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                        Batal
                    </button>
                    <button onclick="submitBayar()" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedPeriode = null;
        let jumlahTagihanValue = 0;

        // Data perpanjangan dari server
        const perpanjanganData = {
            totalPerpanjangan: {{ $pengajuan->angsurans()->where('is_perpanjangan', true)->count() }},
            maxPerpanjangan: 6,
            totalAngsuran: {{ $pengajuan->angsurans->count() }}
        };

        function openLunasModal() {
            document.getElementById('lunasModal').classList.remove('hidden');
            document.getElementById('lunasModal').classList.add('flex');
        }

        function closeLunasModal() {
            document.getElementById('lunasModal').classList.add('hidden');
            document.getElementById('lunasModal').classList.remove('flex');
            document.getElementById('catatanLunas').value = '';
        }

        function openBayarModal(periode, jumlah) {
            selectedPeriode = periode;
            jumlahTagihanValue = parseFloat(jumlah);

            document.getElementById('periodeKeBayar').textContent = periode;
            document.getElementById('jumlahTagihan').textContent = 'Rp ' + parseInt(jumlah).toLocaleString('id-ID');
            document.getElementById('jumlahBayarInput').value = Math.round(jumlah);

            // Cek apakah ini periode terakhir dan sudah 6 perpanjangan
            const isLastPeriode = parseInt(periode) === perpanjanganData.totalAngsuran;
            const isMaxPerpanjangan = perpanjanganData.totalPerpanjangan >= perpanjanganData.maxPerpanjangan;

            const jumlahBayarInput = document.getElementById('jumlahBayarInput');
            const infoPerpanjangan = document.getElementById('infoPerpanjangan');

            if (isLastPeriode && isMaxPerpanjangan) {
                // Disable input dan beri info
                jumlahBayarInput.disabled = true;
                jumlahBayarInput.value = Math.round(jumlah); // Force full amount
                jumlahBayarInput.classList.add('bg-gray-100', 'cursor-not-allowed');

                if (infoPerpanjangan) {
                    infoPerpanjangan.classList.remove('hidden');
                    infoPerpanjangan.innerHTML = '<div class="text-sm text-red-600 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i>Batas perpanjangan maksimal (6 bulan) tercapai. Pembayaran harus lunas tepat.</div>';
                }
            } else {
                // Enable input normal
                jumlahBayarInput.disabled = false;
                jumlahBayarInput.classList.remove('bg-gray-100', 'cursor-not-allowed');

                if (infoPerpanjangan) {
                    infoPerpanjangan.classList.add('hidden');
                }
            }

            document.getElementById('bayarModal').classList.remove('hidden');
            document.getElementById('bayarModal').classList.add('flex');
        }

        function closeBayarModal() {
            document.getElementById('bayarModal').classList.add('hidden');
            document.getElementById('bayarModal').classList.remove('flex');
            document.getElementById('catatanBayar').value = '';

            // Reset jumlahBayarInput state
            const jumlahBayarInput = document.getElementById('jumlahBayarInput');
            jumlahBayarInput.value = '';
            jumlahBayarInput.disabled = false;
            jumlahBayarInput.classList.remove('bg-gray-100', 'cursor-not-allowed');

            // Reset infoPerpanjangan
            const infoPerpanjangan = document.getElementById('infoPerpanjangan');
            if (infoPerpanjangan) {
                infoPerpanjangan.classList.add('hidden');
                infoPerpanjangan.innerHTML = '';
            }

            selectedPeriode = null;
        }

        async function submitLunas() {
            const catatan = document.getElementById('catatanLunas').value;

            if (!confirm('Apakah Anda yakin ingin melunasi semua sisa angsuran sebesar Rp {{ number_format($pengajuan->sisaJadwal(), 0, ',', '.') }}?')) {
                return;
            }

            try {
                const response = await fetch('{{ route('pengurus.pembiayaan.lunas-cepat', $pengajuan->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        catatan: catatan
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Pelunasan berhasil! ' + data.message);
                    window.location.reload();
                } else {
                    alert('Gagal: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        async function submitBayar() {
            const tanggal = document.getElementById('tanggalBayar').value;
            const catatan = document.getElementById('catatanBayar').value;
            const jumlahBayar = parseFloat(document.getElementById('jumlahBayarInput').value);

            if (!tanggal) {
                alert('Pilih tanggal pembayaran');
                return;
            }

            if (isNaN(jumlahBayar) || jumlahBayar <= 0) {
                alert('Masukkan jumlah pembayaran yang valid');
                return;
            }

            try {
                const response = await fetch(`/pengurus/pembiayaan/{{ $pengajuan->id }}/jadwal/${selectedPeriode}/bayar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        jumlah_bayar: jumlahBayar,
                        tanggal_bayar: tanggal,
                        catatan: catatan
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Pembayaran berhasil!');
                    window.location.reload();
                } else {
                    alert('Gagal: ' + data.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }
    </script>
@endif
</div>
@endsection