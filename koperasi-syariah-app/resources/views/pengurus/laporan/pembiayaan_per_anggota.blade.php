@extends('layouts.app')

@section('title', 'Laporan Pembiayaan per Anggota')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Laporan Pembiayaan per Anggota</h1>
        <p class="text-gray-600 mt-2">Laporan status dan history pembiayaan untuk setiap anggota</p>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('pengurus.laporan.pembiayaan-per-anggota') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Anggota</label>
                    <select name="anggota_id" id="anggota_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Semua Anggota --</option>
                        @foreach($listAnggota as $a)
                            <option value="{{ $a->id }}" {{ $anggota && $anggota->id == $a->id ? 'selected' : '' }}>
                                {{ $a->nomor_anggota }} - {{ $a->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Pembiayaan</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="cair" {{ $status == 'cair' ? 'selected' : '' }}>Cair</option>
                        <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Tampilkan Laporan
                    </button>
                </div>
                @if($reportData && isset($reportData['pengajuan']) && count($reportData['pengajuan']) > 0)
                <div class="flex items-end">
                    <a href="{{ route('pengurus.laporan.print', 'pembiayaan-per-anggota') }}?{{ http_build_query(request()->query()) }}"
                       target="_blank"
                       class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </a>
                </div>
                @endif
            </div>
        </form>
    </div>

    @if($anggota && $reportData && isset($reportData['pengajuan']))
    <!-- Anggota Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Informasi Anggota</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-blue-700">Nomor Anggota</p>
                <p class="font-semibold text-blue-900">{{ $anggota->nomor_anggota }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-700">Nama Lengkap</p>
                <p class="font-semibold text-blue-900">{{ $anggota->nama_lengkap }}</p>
            </div>
            <div>
                <p class="text-sm text-blue-700">Status Keanggotaan</p>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                    {{ $anggota->status_keanggotaan == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($anggota->status_keanggotaan) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-hand-holding-usd text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Plafond</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($reportData['total_plafond'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Dibayar</h3>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($reportData['total_dibayar'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-clock text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Sisa Pinjaman</h3>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($reportData['total_sisa'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pembiayaan Details -->
    <div class="space-y-6">
        @foreach($reportData['pengajuan'] as $item)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">{{ $item['pengajuan']->kode_pengajuan }}</h3>
                <div>{!! $item['pengajuan']->status_label !!}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Jenis Pembiayaan</p>
                    <p class="font-semibold">{{ $item['pengajuan']->jenisPembiayaan->nama_pembiayaan }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jumlah Pengajuan</p>
                    <p class="font-semibold">{{ number_format($item['pengajuan']->jumlah_pengajuan, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Margin</p>
                    <p class="font-semibold">{{ number_format($item['pengajuan']->jumlah_margin, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tenor</p>
                    <p class="font-semibold">{{ $item['pengajuan']->tenor }} Bulan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="text-center p-3 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600">Total Pinjaman</p>
                    <p class="text-lg font-bold text-purple-600">{{ number_format($item['pengajuan']->jumlah_pengajuan, 0, ',', '.') }}</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600">Total Dibayar</p>
                    <p class="text-lg font-bold text-green-600">{{ number_format($item['pengajuan']->totalDibayar(), 0, ',', '.') }}</p>
                </div>
                <div class="text-center p-3 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600">Sisa Pinjaman</p>
                    <p class="text-lg font-bold text-red-600">{{ number_format($item['pengajuan']->sisaTotal(), 0, ',', '.') }}</p>
                </div>
            </div>

            @if($item['pengajuan']->status == 'cair')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Tanggal Cair</p>
                    <p class="font-semibold">{{ $item['pengajuan']->tanggal_cair ? $item['pengajuan']->tanggal_cair->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Angsuran Dibayar / Total</p>
                    <p class="font-semibold">{{ $item['pengajuan']->angsuran()->where('status', 'terbayar')->count() }} / {{ $item['pengajuan']->angsuran()->count() }}</p>
                </div>
            </div>
            @endif

            @if($item['recent_angsuran']->count() > 0)
            <div class="border-t pt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Angsuran Terakhir Dibayar</h4>
                <div class="space-y-2">
                    @foreach($item['recent_angsuran'] as $angsuran)
                    <div class="flex items-center justify-between text-sm p-2 bg-gray-50 rounded">
                        <div class="flex items-center">
                            <span class="font-medium">{{ $angsuran->kode_angsuran }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $angsuran->tanggal_bayar->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-500">Pokok:</span>
                            <span class="font-semibold text-green-600">{{ number_format($angsuran->jumlah_pokok, 0, ',', '.') }}</span>
                            <span class="text-gray-500">Margin:</span>
                            <span class="font-semibold text-purple-600">{{ number_format($angsuran->jumlah_margin, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @elseif(request('anggota_id'))
    <!-- No Data Message -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mb-2"></i>
        <p class="text-yellow-700">Anggota yang dipilih tidak memiliki riwayat pembiayaan</p>
    </div>
    @else
    <!-- Instructions -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
        <i class="fas fa-info-circle text-gray-500 text-2xl mb-2"></i>
        <p class="text-gray-700">Pilih anggota dari dropdown di atas untuk melihat laporan pembiayaan detail</p>
    </div>
    @endif
</div>
@endsection