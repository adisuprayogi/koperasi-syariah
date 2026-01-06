@extends('layouts.app')

@section('title', 'Dashboard Pengurus')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-4 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard Pengurus</h1>
        <p class="text-gray-600 mt-1 sm:mt-2 text-sm sm:text-base">Selamat datang di halaman Pengurus Koperasi Syariah</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-friends text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Saldo</h3>
                    <p class="text-lg font-bold text-green-600">{{ number_format($totalSaldo, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Kas koperasi</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-piggy-bank text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Simpanan</h3>
                    <p class="text-lg font-bold text-blue-600">{{ number_format($saldoSimpanan, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Saldo simpanan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Pembiayaan</h3>
                    <p class="text-lg font-bold text-purple-600">{{ number_format($totalPembiayaanCair, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">{{ $activePembiayaan }} aktif</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Margin</h3>
                    <p class="text-lg font-bold text-yellow-600">{{ number_format($totalMargin, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Dari pembiayaan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Simpanan Per Jenis -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Total Simpanan per Jenis</h3>
            <a href="{{ route('pengurus.laporan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($simpananPerJenis as $simpanan)
            @if($simpanan->saldo > 0)
            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow" style="border-left: 4px solid {{ $simpanan->jenis->warna ?? '#16a34a' }};">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-800 text-sm">{{ $simpanan->jenis->nama_simpanan }}</h4>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: {{ $simpanan->jenis->warna ?? '#16a34a' }}20;">
                        <i class="fas fa-wallet text-sm" style="color: {{ $simpanan->jenis->warna ?? '#16a34a' }};"></i>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Total Setor:</span>
                        <span class="text-green-600 font-medium">{{ number_format($simpanan->total_setor, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Total Tarik:</span>
                        <span class="text-red-600 font-medium">{{ number_format($simpanan->total_tarik, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm pt-2 border-t">
                        <span class="text-gray-700 font-medium">Saldo:</span>
                        <span class="font-bold" style="color: {{ $simpanan->jenis->warna ?? '#16a34a' }};">{{ number_format($simpanan->saldo, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @if($simpananPerJenis->where('saldo', '>', 0)->count() === 0)
        <div class="text-center text-gray-500 py-8">
            <i class="fas fa-inbox text-4xl mb-2"></i>
            <p>Belum ada data simpanan</p>
        </div>
        @endif
    </div>

    <!-- Angsuran Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-medium opacity-90">Sisa Angsuran</h3>
                    <p class="text-xl font-bold mt-1">{{ number_format($totalSisaAngsuran, 0, ',', '.') }}</p>
                    <p class="text-xs opacity-75 mt-1">{{ $countAngsuranBelumLunas }} belum lunas</p>
                </div>
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-medium opacity-90">Sudah Dibayar</h3>
                    <p class="text-xl font-bold mt-1">{{ number_format($totalSudahBayar, 0, ',', '.') }}</p>
                    <p class="text-xs opacity-75 mt-1">Total terbayar</p>
                </div>
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow p-4 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-medium opacity-90">Tunggakan Wajib</h3>
                    <p class="text-xl font-bold mt-1">{{ number_format($totalTunggakanWajib, 0, ',', '.') }}</p>
                    <p class="text-xs opacity-75 mt-1">{{ $jumlahAnggotaNunggakWajib }} anggota</p>
                </div>
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Summary & Pending Tasks -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Today's Summary -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Ringkasan Hari Ini</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                <div class="text-center p-3 sm:p-4 bg-blue-50 rounded-lg">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl sm:text-2xl mb-2"></i>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ $transaksiHariIni }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">Transaksi</p>
                </div>
                <div class="text-center p-3 sm:p-4 bg-green-50 rounded-lg">
                    <i class="fas fa-arrow-up text-green-600 text-xl sm:text-2xl mb-2"></i>
                    <p class="text-xl sm:text-2xl font-bold text-green-600">{{ number_format($setoranHariIni, 0, ',', '.') }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">Setoran</p>
                </div>
                <div class="text-center p-3 sm:p-4 bg-red-50 rounded-lg">
                    <i class="fas fa-arrow-down text-red-600 text-xl sm:text-2xl mb-2"></i>
                    <p class="text-xl sm:text-2xl font-bold text-red-600">{{ number_format($penarikanHariIni, 0, ',', '.') }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">Penarikan</p>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Task Menunggu</h3>
            @if($pendingTasks->count() > 0)
                <div class="space-y-2">
                    @foreach($pendingTasks as $task)
                    <a href="{{ $task->url }}" class="block p-3 {{ $task->priority == 'high' ? 'bg-red-50 hover:bg-red-100 border-red-200' : 'bg-yellow-50 hover:bg-yellow-100 border-yellow-200' }} border rounded-lg transition-colors">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">{{ $task->task }}</span>
                            <span class="text-sm font-bold {{ $task->priority == 'high' ? 'text-red-600' : 'text-yellow-600' }}">{{ $task->count }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <p class="text-sm">Tidak ada task pending</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6 mb-6 sm:mb-8">
        <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <a href="{{ route('pengurus.anggota.create') }}" class="flex flex-col sm:flex-row items-center justify-center p-3 sm:p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-user-plus text-green-600 mr-0 sm:mr-3 mb-2 sm:mb-0 text-sm sm:text-base"></i>
                <span class="font-medium text-gray-900 text-sm sm:text-base text-center">Tambah Anggota</span>
            </a>
            <a href="{{ route('pengurus.simpanan.create') }}" class="flex flex-col sm:flex-row items-center justify-center p-3 sm:p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-plus-circle text-blue-600 mr-0 sm:mr-3 mb-2 sm:mb-0 text-sm sm:text-base"></i>
                <span class="font-medium text-gray-900 text-sm sm:text-base text-center">Input Simpanan</span>
            </a>
            <a href="{{ route('pengurus.pengajuan.index') }}" class="flex flex-col sm:flex-row items-center justify-center p-3 sm:p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-clipboard-check text-purple-600 mr-0 sm:mr-3 mb-2 sm:mb-0 text-sm sm:text-base"></i>
                <span class="font-medium text-gray-900 text-sm sm:text-base text-center">Verifikasi Pengajuan</span>
            </a>
            <a href="{{ route('pengurus.laporan.index') }}" class="flex flex-col sm:flex-row items-center justify-center p-3 sm:p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-chart-bar text-yellow-600 mr-0 sm:mr-3 mb-2 sm:mb-0 text-sm sm:text-base"></i>
                <span class="font-medium text-gray-900 text-sm sm:text-base text-center">Lihat Laporan</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pengajuan Terbaru -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengajuan Terbaru</h3>
            <div class="space-y-3">
                @if($recentPengajuan->count() > 0)
                    @foreach($recentPengajuan as $pengajuan)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $pengajuan->kode_pengajuan }}</span>
                                <span class="mx-2">•</span>
                                <span class="text-sm text-gray-600">{{ $pengajuan->anggota->nama_lengkap }}</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ number_format($pengajuan->jumlah_pengajuan, 0, ',', '.') }} •
                                {{ $pengajuan->created_at->format('d M H:i') }}
                            </div>
                        </div>
                        <div>
                            {!! $pengajuan->status_label !!}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Belum ada pengajuan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaksi Terbaru</h3>
            <div class="space-y-3">
                @if($recentTransaksi->count() > 0)
                    @foreach($recentTransaksi as $transaksi)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-900">{{ $transaksi->kode_transaksi }}</span>
                                <span class="mx-2">•</span>
                                <span class="text-sm text-gray-600">{{ $transaksi->anggota->nama_lengkap }}</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $transaksi->jenisSimpanan->nama_simpanan }} •
                                {{ $transaksi->tanggal_transaksi->format('d M H:i') }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold {{ $transaksi->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaksi->jenis_transaksi == 'setor' ? '+' : '-' }}
                                {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $transaksi->jenis_transaksi }}</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-exchange-alt text-4xl mb-2"></i>
                        <p>Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Summary Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Bulanan (6 Bulan Terakhir)</h3>
        @if($monthlySummary->count() > 0)
            <div class="space-y-3">
                @foreach($monthlySummary as $summary)
                <div class="flex items-center">
                    <div class="w-32 text-sm font-medium text-gray-700">
                        {{ \Carbon\Carbon::createFromDate($summary->year, $summary->month, 1)->format('M Y') }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-4">
                                    <div class="bg-green-500 h-4 rounded-full" style="width: {{ $summary->total_setor > 0 ? min(($summary->total_setor / $monthlySummary->max('total_setor')) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="w-32 text-sm text-gray-600">
                                Setor: {{ number_format($summary->total_setor, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 mt-1">
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-3">
                                    <div class="bg-red-500 h-3 rounded-full" style="width: {{ $summary->total_tarik > 0 ? min(($summary->total_tarik / $monthlySummary->max('total_tarik')) * 100, 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="w-32 text-sm text-gray-600">
                                Tarik: {{ number_format($summary->total_tarik, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-chart-bar text-4xl mb-2"></i>
                <p>Belum ada data bulanan</p>
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Informasi
        </h3>
        <p class="text-blue-700 text-sm">
            Sebagai Pengurus Koperasi, Anda dapat mengelola anggota, transaksi simpanan, verifikasi pengajuan pembiayaan, dan melihat laporan keuangan.
        </p>
    </div>
</div>
@endsection