@extends('layouts.app')

@section('title', 'Pengajuan Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengajuan Pembiayaan</h1>
            <p class="text-gray-600 mt-2">Kelola pengajuan pembiayaan anggota</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-list text-gray-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $pengajuans->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-paper-plane text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Menunggu</h3>
                    <p class="text-lg font-bold text-blue-600">{{ $stats['total_diajukan'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Disetujui</h3>
                    <p class="text-lg font-bold text-green-600">{{ $stats['total_approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Ditolak</h3>
                    <p class="text-lg font-bold text-red-600">{{ $stats['total_rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto">
            <a href="{{ route('pengurus.pengajuan.index') }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 {{ request('status') == '' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Semua
            </a>
            <a href="?status=diajukan"
               class="whitespace-nowrap py-2 px-1 border-b-2 {{ request('status') == 'diajukan' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Menunggu Verifikasi
            </a>
            <a href="?status=approved"
               class="whitespace-nowrap py-2 px-1 border-b-2 {{ request('status') == 'approved' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Disetujui
            </a>
            <a href="?status=rejected"
               class="whitespace-nowrap py-2 px-1 border-b-2 {{ request('status') == 'rejected' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                Ditolak
            </a>
        </nav>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Cari kode, nama anggota...">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="cair" {{ request('status') == 'cair' ? 'selected' : '' }}>Cair</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('pengurus.pengajuan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Reset
            </a>
        </form>
    </div>

    <!-- Pengajuan List -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-file-alt mr-2 text-primary-600"></i>
                        Daftar Pengajuan
                    </h2>
                    <p class="text-gray-500 text-xs">Semua pengajuan pembiayaan</p>
                </div>
                @if($pengajuans->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $pengajuans->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Pengajuan</p>
                    </div>
                @endif
            </div>
        </div>

        @if($pengajuans->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plafond</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tenor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pengajuans as $pengajuan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Kode -->
                                <td class="px-4 py-3">
                                    <div class="text-xs font-mono font-semibold text-indigo-600">{{ $pengajuan->kode_pengajuan }}</div>
                                </td>

                                <!-- Tanggal -->
                                <td class="px-4 py-3">
                                    @if($pengajuan->created_at)
                                        <div class="text-sm text-gray-900">{{ $pengajuan->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $pengajuan->created_at->format('H:i') }}</div>
                                    @else
                                        <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </td>

                                <!-- Anggota -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $pengajuan->anggota->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $pengajuan->anggota->no_anggota }}</div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $pengajuan->jenisPembiayaan->nama_pembiayaan }}</div>
                                </td>

                                <!-- Plafond -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-blue-600">{{ $pengajuan->jumlah_pengajuan_formatted }}</div>
                                    @if($pengajuan->jumlah_margin)
                                        <div class="text-xs text-gray-500">Margin: {{ number_format($pengajuan->jumlah_margin, 0, ',', '.') }}</div>
                                    @endif
                                </td>

                                <!-- Tenor -->
                                <td class="px-4 py-3 text-center">
                                    <div class="text-sm text-gray-900">{{ $pengajuan->tenor }} Bln</div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    @switch($pengajuan->status)
                                        @case('draft')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                <i class="fas fa-file mr-1"></i>Draft
                                            </span>
                                            @break
                                        @case('diajukan')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                <i class="fas fa-paper-plane mr-1"></i>Diajukan
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Disetujui
                                            </span>
                                            @break
                                        @case('rejected')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Ditolak
                                            </span>
                                            @break
                                        @case('cair')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                <i class="fas fa-money-bill-wave mr-1"></i>Cair
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $pengajuan->status }}
                                            </span>
                                    @endswitch
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('pengurus.pengajuan.show', $pengajuan->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 transition-colors"
                                           title="Lihat Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        @if($pengajuan->status == 'diajukan' && auth()->user()->pengurus && in_array(auth()->user()->pengurus->posisi, ['ketua', 'sekretaris', 'pengurus_lainnya']))
                                            <form action="{{ route('pengurus.pengajuan.verifikasi', $pengajuan->id) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 hover:bg-green-200 text-green-600 hover:text-green-700 transition-colors"
                                                        title="Verifikasi & Setujui"
                                                        onclick="return confirm('Apakah Anda yakin ingin memverifikasi dan menyetujui pengajuan ini?')">
                                                    <i class="fas fa-check-circle text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($pengajuan->status == 'approved' && auth()->user()->pengurus && auth()->user()->pengurus->posisi == 'bendahara')
                                            <button type="button"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 hover:bg-purple-200 text-purple-600 hover:text-purple-700 transition-colors"
                                                    title="Cairkan"
                                                    onclick="openCairkanModal({{ $pengajuan->id }}, '{{ $pengajuan->kode_pengajuan }}')">
                                                <i class="fas fa-money-bill-wave text-sm"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pengajuans->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $pengajuans->links('pagination.custom') }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-open text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Pengajuan</h3>
                    <p class="text-gray-600 mb-6 text-sm">Belum ada pengajuan pembiayaan dari anggota</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Upload Bukti Pencairan -->
<div id="cairkanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-md w-full">
            <div class="bg-purple-600 text-white px-3 py-3">
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
