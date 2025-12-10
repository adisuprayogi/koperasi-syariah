@extends('layouts.app')

@section('title', 'Detail Pengajuan - ' . $pengajuan->kode_pengajuan)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Pengajuan Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Kode: {{ $pengajuan->kode_pengajuan }}</p>
        </div>
        <div class="flex space-x-3">
            @if(in_array($pengajuan->status, ['draft', 'rejected']))
                <a href="{{ route('anggota.pengajuan.edit', $pengajuan->id) }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            @endif
            @if($pengajuan->status == 'draft')
                <form action="{{ route('anggota.pengajuan.submit', $pengajuan->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                            onclick="return confirm('Apakah Anda yakin ingin mengajukan permohonan ini?')">
                        <i class="fas fa-paper-plane mr-2"></i>Ajukan Sekarang
                    </button>
                </form>
            @endif
            <a href="{{ route('anggota.pengajuan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-white">Status Pengajuan</h2>
                {!! $pengajuan->status_label !!}
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Status Timeline -->
                <div class="col-span-2">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">Proses Pengajuan</h3>
                    <div class="space-y-4">
                        <!-- Diajukan -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full {{ $pengajuan->status != 'draft' ? 'bg-green-100' : 'bg-gray-300' }}">
                                    <i class="fas fa-check h-4 w-4 {{ $pengajuan->status != 'draft' ? 'text-green-600' : 'text-gray-500' }}"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">1. Pengajuan Dibuat</p>
                                <p class="text-xs text-gray-500">{{ $pengajuan->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Verifikasi -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full {{ in_array($pengajuan->status, ['verifikasi', 'approved', 'rejected', 'cair', 'lunas']) ? 'bg-green-100' : 'bg-gray-300' }}">
                                    <i class="fas fa-search h-4 w-4 {{ in_array($pengajuan->status, ['verifikasi', 'approved', 'rejected', 'cair', 'lunas']) ? 'text-green-600' : 'text-gray-500' }}"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">2. Verifikasi Dokumen</p>
                                @if($pengajuan->verified_at)
                                    <p class="text-xs text-gray-500">{{ $pengajuan->verified_at->format('d M Y H:i') }} - {{ $pengajuan->verifiedBy->nama_lengkap }}</p>
                                @else
                                    <p class="text-xs text-gray-500">Menunggu verifikasi</p>
                                @endif
                            </div>
                        </div>

                        <!-- Approval -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full {{ in_array($pengajuan->status, ['approved', 'cair', 'lunas']) ? 'bg-green-100' : ($pengajuan->status == 'rejected' ? 'bg-red-100' : 'bg-gray-300') }}">
                                    @if($pengajuan->status == 'rejected')
                                        <i class="fas fa-times h-4 w-4 text-red-600"></i>
                                    @elseif(in_array($pengajuan->status, ['approved', 'cair', 'lunas']))
                                        <i class="fas fa-check h-4 w-4 text-green-600"></i>
                                    @else
                                        <i class="fas fa-user-check h-4 w-4 text-gray-500"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">3. Persetujuan</p>
                                @if($pengajuan->approved_at)
                                    <p class="text-xs text-gray-500">{{ $pengajuan->approved_at->format('d M Y H:i') }} - {{ $pengajuan->approvedBy->nama_lengkap }}</p>
                                @elseif($pengajuan->status == 'rejected')
                                    <p class="text-xs text-red-500">Ditolak - {{ $pengajuan->alasan_penolakan }}</p>
                                @else
                                    <p class="text-xs text-gray-500">Menunggu persetujuan</p>
                                @endif
                            </div>
                        </div>

                        <!-- Pencairan -->
                        @if($pengajuan->status == 'approved' || $pengajuan->status == 'cair' || $pengajuan->status == 'lunas')
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full {{ $pengajuan->status == 'cair' || $pengajuan->status == 'lunas' ? 'bg-green-100' : 'bg-gray-300' }}">
                                    <i class="fas fa-money-bill-wave h-4 w-4 {{ $pengajuan->status == 'cair' || $pengajuan->status == 'lunas' ? 'text-green-600' : 'text-gray-500' }}"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">4. Pencairan Dana</p>
                                @if($pengajuan->tanggal_cair)
                                    <p class="text-xs text-gray-500">{{ $pengajuan->tanggal_cair->format('d M Y H:i') }} - {{ $pengajuan->pencair->pengurus->nama_lengkap ?? $pengajuan->pencair->name ?? 'Admin' }}</p>
                                @else
                                    <p class="text-xs text-gray-500">Menunggu pencairan</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Info -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-4">Informasi Cepat</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Jenis Pembiayaan</p>
                            <p class="text-sm font-medium text-gray-900">{{ $pengajuan->jenisPembiayaan->nama_pembiayaan }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tujuan</p>
                            <p class="text-sm font-medium text-gray-900">{{ $pengajuan->tujuan_pembiayaan_label }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Jangka Waktu</p>
                            <p class="text-sm font-medium text-gray-900">{{ $pengajuan->tenor }} bulan</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal Jatuh Tempo</p>
                            <p class="text-sm font-medium text-gray-900">{{ $pengajuan->tanggal_jatuh_tempo ? $pengajuan->tanggal_jatuh_tempo->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Pengajuan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Data Pembiayaan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Data Pembiayaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Jumlah Pengajuan</p>
                        <p class="text-lg font-medium text-gray-900">{{ $pengajuan->jumlah_pengajuan_formatted }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Margin ({{ $pengajuan->margin_percent }}%)</p>
                        <p class="text-lg font-medium text-gray-900">Rp {{ number_format($pengajuan->jumlah_margin, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Pinjaman</p>
                        <p class="text-lg font-medium text-gray-900">Rp {{ number_format($pengajuan->jumlah_pengajuan + $pengajuan->jumlah_margin, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Angsuran/Bulan</p>
                        <p class="text-lg font-medium text-green-600">{{ $pengajuan->total_angsuran_formatted }}</p>
                    </div>
                </div>

                @if($pengajuan->deskripsi)
                <div class="mt-6">
                    <p class="text-sm text-gray-500">Deskripsi Pengajuan</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $pengajuan->deskripsi }}</p>
                </div>
                @endif
            </div>

            <!-- Informasi Rekening -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Rekening</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nomor Rekening</p>
                        <p class="text-lg font-medium text-gray-900">{{ $pengajuan->no_rekening }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Atas Nama</p>
                        <p class="text-lg font-medium text-gray-900">{{ $pengajuan->atas_nama }}</p>
                    </div>
                </div>

                @if($pengajuan->bukti_transfer)
                <div class="mt-6">
                    <p class="text-sm text-gray-500">Bukti Transfer</p>
                    <a href="{{ asset('storage/' . $pengajuan->bukti_transfer) }}" target="_blank"
                       class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                        <i class="fas fa-download mr-1"></i> Download Bukti Transfer
                    </a>
                </div>
                @endif
            </div>

            <!-- Bukti Pencairan -->
            @if($pengajuan->bukti_pencairan)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Bukti Pencairan</h3>
                <div class="space-y-4">
                    <x-file-display :file="$pengajuan->bukti_pencairan" label="Bukti Pencairan" :pengajuanId="$pengajuan->id" field="bukti_pencairan" />

                    @if($pengajuan->tanggal_jatuh_tempo_pertama)
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Jatuh Tempo Angsuran Pertama</p>
                        <p class="text-sm font-medium text-gray-900">{{ $pengajuan->tanggal_jatuh_tempo_pertama_formatted }}</p>
                    </div>
                    @endif

                    @if($pengajuan->keterangan_jatuh_tempo)
                    <div>
                        <p class="text-sm text-gray-500">Keterangan</p>
                        <p class="text-sm text-gray-900">{{ $pengajuan->keterangan_jatuh_tempo }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Dokumen Pendukung -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen Pendukung</h3>

                <!-- Required Documents -->
                <div class="space-y-3 mb-6">
                    <h4 class="text-sm font-medium text-gray-900">Dokumen Wajib</h4>
                    <x-file-display :file="$pengajuan->ktp_file" label="Scan KTP" :pengajuanId="$pengajuan->id" field="ktp_file" />
                </div>

                <!-- Optional Documents -->
                <div class="space-y-3 mb-6">
                    <h4 class="text-sm font-medium text-gray-900">Dokumen Tambahan</h4>
                    <x-file-display :file="$pengajuan->kk_file" label="Scan KK" :pengajuanId="$pengajuan->id" field="kk_file" />
                    <x-file-display :file="$pengajuan->slip_gaji_file" label="Slip Gaji" :pengajuanId="$pengajuan->id" field="slip_gaji_file" />
                    <x-file-display :file="$pengajuan->proposal_file" label="Proposal Bisnis" :pengajuanId="$pengajuan->id" field="proposal_file" />
                </div>

                <!-- Jaminan Documents -->
                @if(count($pengajuan->all_jaminan_files) > 0)
                <div class="space-y-3 mb-6">
                    <h4 class="text-sm font-medium text-gray-900">Dokumen Jaminan</h4>
                    @foreach($pengajuan->all_jaminan_files as $index => $file)
                        <x-file-display :file="$file" :label="'Dokumen Jaminan ' . ($index + 1)" />
                    @endforeach
                </div>
                @endif

                <!-- Other Documents -->
                @if(count($pengajuan->all_dokumen_lainnya_files) > 0)
                <div class="space-y-3">
                    <h4 class="text-sm font-medium text-gray-900">Dokumen Lainnya</h4>
                    @foreach($pengajuan->all_dokumen_lainnya_files as $index => $file)
                        <x-file-display :file="$file" :label="'Dokumen Lainnya ' . ($index + 1)" />
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Catatan & Info -->
        <div class="space-y-6">
            <!-- Catatan Verifikasi -->
            @if($pengajuan->catatan_verifikasi)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Catatan Verifikasi</h3>
                <p class="text-sm text-gray-600">{{ $pengajuan->catatan_verifikasi }}</p>
            </div>
            @endif

            <!-- Catatan Approval -->
            @if($pengajuan->catatan_approval)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Catatan Approval</h3>
                <p class="text-sm text-gray-600">{{ $pengajuan->catatan_approval }}</p>
            </div>
            @endif

            <!-- Info Anggota -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">Informasi Anggota</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Nama</p>
                        <p class="text-sm font-medium text-gray-900">{{ $pengajuan->anggota->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">No. Anggota</p>
                        <p class="text-sm font-medium text-gray-900">{{ $pengajuan->anggota->no_anggota }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">No. HP</p>
                        <p class="text-sm font-medium text-gray-900">{{ $pengajuan->anggota->no_hp }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Email</p>
                        <p class="text-sm font-medium text-gray-900">{{ $pengajuan->anggota->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection