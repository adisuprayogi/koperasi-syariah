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
            @if($pengajuan->status == 'diajukan')
                {{-- Show verifikasi & tolak buttons to Ketua, Sekretaris, & Pengurus Lainnya --}}
                @if(auth()->user()->pengurus && in_array(auth()->user()->pengurus->posisi, ['ketua', 'sekretaris', 'pengurus_lainnya']))
                    <form action="{{ route('pengurus.pengajuan.verifikasi', $pengajuan->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                                onclick="return confirm('Apakah Anda yakin ingin memverifikasi dan menyetujui pengajuan ini?')">
                            <i class="fas fa-check-circle mr-2"></i>Verifikasi & Setujui
                        </button>
                    </form>
                    <button type="button"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors"
                            onclick="document.getElementById('reject-form').classList.remove('hidden')">
                        <i class="fas fa-times mr-2"></i>Tolak
                    </button>
                @endif
            @endif
            @if($pengajuan->status == 'approved')
                {{-- Only show cairkan button to Bendahara --}}
                @if(auth()->user()->pengurus && auth()->user()->pengurus->posisi == 'bendahara')
                    <button type="button"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors"
                            onclick="openCairkanModal({{ $pengajuan->id }}, '{{ $pengajuan->kode_pengajuan }}')">
                        <i class="fas fa-money-bill-wave mr-2"></i>Cairkan
                    </button>
                @endif
            @endif
            <a href="{{ route('pengurus.pengajuan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
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
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                    <i class="fas fa-check h-4 w-4 text-green-600"></i>
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
                                    <p class="text-xs text-gray-500">{{ $pengajuan->tanggal_cair->format('d M Y H:i') }} - {{ $pengajuan->pencair ? $pengajuan->pencair->name : 'Admin' }}</p>
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

            <!-- Dokumen Pendukung -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen Pendukung</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($pengajuan->ktp_file)
                    <div>
                        <p class="text-sm text-gray-500">KTP</p>
                        <a href="{{ asset('storage/' . $pengajuan->ktp_file) }}" target="_blank"
                           class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                            <i class="fas fa-file-pdf mr-1"></i> Lihat KTP
                        </a>
                    </div>
                    @endif

                    @if($pengajuan->kk_file)
                    <div>
                        <p class="text-sm text-gray-500">KK</p>
                        <a href="{{ asset('storage/' . $pengajuan->kk_file) }}" target="_blank"
                           class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                            <i class="fas fa-file-pdf mr-1"></i> Lihat KK
                        </a>
                    </div>
                    @endif

                    @if($pengajuan->slip_gaji_file)
                    <div>
                        <p class="text-sm text-gray-500">Slip Gaji</p>
                        <a href="{{ asset('storage/' . $pengajuan->slip_gaji_file) }}" target="_blank"
                           class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                            <i class="fas fa-file-pdf mr-1"></i> Lihat Slip Gaji
                        </a>
                    </div>
                    @endif

                    @if($pengajuan->proposal_file)
                    <div>
                        <p class="text-sm text-gray-500">Proposal</p>
                        <a href="{{ asset('storage/' . $pengajuan->proposal_file) }}" target="_blank"
                           class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                            <i class="fas fa-file-pdf mr-1"></i> Lihat Proposal
                        </a>
                    </div>
                    @endif

                    @if($pengajuan->jaminan_file)
                    <div>
                        <p class="text-sm text-gray-500">Dokumen Jaminan</p>
                        <a href="{{ asset('storage/' . $pengajuan->jaminan_file) }}" target="_blank"
                           class="text-blue-600 hover:text-blue-800 text-sm mt-1">
                            <i class="fas fa-file-pdf mr-1"></i> Lihat Jaminan
                        </a>
                    </div>
                    @endif
                </div>
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
                    <div>
                        <p class="text-xs text-gray-500">Status Keanggotaan</p>
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($pengajuan->anggota->status_keanggotaan) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Form (Hidden by default) -->
    @if($pengajuan->status == 'diajukan' && auth()->user()->pengurus && in_array(auth()->user()->pengurus->posisi, ['ketua', 'sekretaris', 'pengurus_lainnya']))
    <form id="reject-form" action="{{ route('pengurus.pengajuan.reject', $pengajuan->id) }}" method="POST" class="hidden mt-6">
        @csrf
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Alasan Penolakan</h3>
            <div class="mb-4">
                <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Jelaskan alasan penolakan pengajuan ini..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('reject-form').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i>Tolak Pengajuan
                </button>
            </div>
        </div>
    </form>
    @endif
</div>

<!-- Modal Upload Bukti Pencairan -->
<div id="cairkanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full">
            <div class="bg-purple-600 text-white px-6 py-4">
                <h3 class="text-lg font-semibold">Upload Bukti Pencairan</h3>
            </div>
            <form id="cairkanForm" action="" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="id" id="cairkanId">

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">
                        Upload bukti pencairan untuk pengajuan: <span id="cairkanKode" class="font-semibold"></span>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Pencairan <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="bukti_pencairan" id="bukti_pencairan" required
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                    <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, PNG. Maks: 2MB</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Jatuh Tempo Angsuran Pertama <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_jatuh_tempo_pertama" id="tanggal_jatuh_tempo_pertama" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                           min="{{ date('Y-m-d') }}">
                    <p class="text-xs text-gray-500 mt-1">Tanggal jatuh tempo untuk pembayaran angsuran pertama</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan_jatuh_tempo" id="keterangan_jatuh_tempo" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Catatan tambahan mengenai jadwal angsuran..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="closeCairkanModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        Cairkan & Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCairkanModal(id, kode) {
    document.getElementById('cairkanId').value = id;
    document.getElementById('cairkanKode').textContent = kode;
    document.getElementById('cairkanForm').action = '{{ route("pengurus.pengajuan.cairkan", ":ID") }}'.replace(':ID', id);

    // Set default tanggal jatuh tempo (hari ini + 1 bulan)
    const today = new Date();
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
    const formattedDate = nextMonth.toISOString().split('T')[0];
    document.getElementById('tanggal_jatuh_tempo_pertama').value = formattedDate;

    document.getElementById('cairkanModal').classList.remove('hidden');
}

function closeCairkanModal() {
    document.getElementById('cairkanModal').classList.add('hidden');
    document.getElementById('bukti_pencairan').value = '';
}

// Close modal when clicking outside
document.getElementById('cairkanModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCairkanModal();
    }
});
</script>
@endsection