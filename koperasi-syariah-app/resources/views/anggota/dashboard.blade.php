@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Anggota</h1>
        <p class="text-gray-600 mt-2">Selamat datang di halaman Anggota Koperasi Syariah</p>
    </div>

    <!-- Info Card -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h2>
                <p class="text-green-100">Terima kasih telah menjadi anggota Koperasi Syariah kami</p>
            </div>
            <div class="text-6xl opacity-20">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
    </div>

    <!-- Savings Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        @foreach($saldoPerJenis as $saldo)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 {{ $saldo->jenis->tipe_simpanan == 'modal' ? 'bg-indigo-100' : ($saldo->jenis->tipe_simpanan == 'pokok' ? 'bg-blue-100' : ($saldo->jenis->tipe_simpanan == 'wajib' ? 'bg-yellow-100' : 'bg-green-100')) }} rounded-full">
                    <i class="fas {{ $saldo->jenis->tipe_simpanan == 'modal' ? 'fa-coins text-indigo-600' : ($saldo->jenis->tipe_simpanan == 'pokok' ? 'fa-lock text-blue-600' : ($saldo->jenis->tipe_simpanan == 'wajib' ? 'fa-calendar-check text-yellow-600' : 'fa-hand-holding-heart text-green-600')) }} text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">{{ $saldo->jenis->nama_simpanan }}</h3>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($saldo->saldo, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Total Saldo Card -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-green-100">Total Simpanan</h3>
                    <p class="text-2xl font-bold">{{ number_format($totalSimpanan, 0, ',', '.') }}</p>
                </div>
                <div class="text-3xl opacity-50">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Financing Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pembiayaan</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Total Pembiayaan</p>
                    <p class="text-xl font-bold text-gray-900">{{ number_format($totalPembiayaan, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sisa Pinjaman</p>
                    <p class="text-xl font-bold text-red-600">{{ number_format($sisaPinjaman, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Aktif</p>
                    <p class="text-xl font-bold text-blue-600">{{ $activePembiayaan }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Margin</p>
                    <p class="text-xl font-bold text-purple-600">{{ number_format($totalMargin, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pengajuan</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Draft</span>
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $statusPengajuan['draft'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Diajukan</span>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $statusPengajuan['diajukan'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Disetujui</span>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $statusPengajuan['approved'] + $statusPengajuan['cair'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Ditolak</span>
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $statusPengajuan['rejected'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Next Installment & Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Next Installment -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Angsuran Berikutnya</h3>
            @if($angsuranBerikutnya)
                <div class="space-y-3">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $angsuranBerikutnya->jumlah_angsuran_formatted }}
                        </div>
                        <div class="text-sm text-gray-600">
                            Angsuran ke-{{ $angsuranBerikutnya->angsuran_ke }}
                        </div>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jatuh Tempo:</span>
                            <span class="font-medium">{{ $angsuranBerikutnya->tanggal_jatuh_tempo_formatted }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-gray-600">Status:</span>
                            <span>{!! $angsuranBerikutnya->status_label !!}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <p class="text-sm">Tidak ada angsuran aktif</p>
                </div>
            @endif
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terkini</h3>
            <div class="space-y-3 max-h-48 overflow-y-auto">
                <!-- Recent Pengajuan -->
                @if($recentPengajuan->count() > 0)
                    @foreach($recentPengajuan as $pengajuan)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-file-alt text-purple-500"></i>
                            <div>
                                <span class="text-sm font-medium">{{ $pengajuan->kode_pengajuan }}</span>
                                <span class="text-xs text-gray-500">({{ $pengajuan->created_at->format('d M H:i') }})</span>
                            </div>
                        </div>
                        <div>{!! $pengajuan->status_label !!}</div>
                    </div>
                    @endforeach
                @endif

                <!-- Recent Transaksi -->
                @if($recentTransaksi->count() > 0)
                    @foreach($recentTransaksi as $transaksi)
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-exchange-alt text-blue-500"></i>
                            <div>
                                <span class="text-sm font-medium">{{ $transaksi->jenis_transaksi }}</span>
                                <span class="text-xs text-gray-500">({{ $transaksi->tanggal_transaksi->format('d M H:i') }})</span>
                            </div>
                        </div>
                        <div class="font-semibold {{ $transaksi->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                @endif

                @if($recentPengajuan->count() == 0 && $recentTransaksi->count() == 0)
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Belum ada aktivitas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Summary Chart -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Simpanan (6 Bulan Terakhir)</h3>
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

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('anggota.profile') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                <span class="font-medium text-gray-900">Edit Profile</span>
            </a>
            <a href="{{ route('anggota.simpanan.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-piggy-bank text-green-600 mr-3"></i>
                <span class="font-medium text-gray-900">Lihat Simpanan</span>
            </a>
            <a href="{{ route('anggota.pengajuan.create') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-plus-circle text-purple-600 mr-3"></i>
                <span class="font-medium text-gray-900">Ajukan Pembiayaan</span>
            </a>
            <a href="{{ route('anggota.pengajuan.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <i class="fas fa-hand-holding-usd text-yellow-600 mr-3"></i>
                <span class="font-medium text-gray-900">Lihat Pengajuan</span>
            </a>
        </div>
    </div>

    <!-- Information Panel -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>Informasi Penting
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-1">Manfaat Menjadi Anggota:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Akses pembiayaan dengan margin syariah</li>
                    <li>Hasil bagi (nisbah) dari simpanan sukarela</li>
                    <li>Fasilitas koperasi lainnya</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-1">Kewajiban Anggota:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Membayar simpanan pokok</li>
                    <li>Membayar simpanan wajib bulanan</li>
                    <li>Mematuhi peraturan AD/ART koperasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection