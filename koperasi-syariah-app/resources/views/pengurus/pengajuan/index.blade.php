@extends('layouts.app')

@section('title', 'Pengajuan Pembiayaan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pengajuan Pembiayaan</h1>
        <p class="text-gray-600 mt-2">Kelola pengajuan pembiayaan anggota</p>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-gray-100 rounded-full">
                    <i class="fas fa-list text-gray-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pengajuans->total() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-paper-plane text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Menunggu Verifikasi</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ $stats['total_diajukan'] }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-search text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Sedang Diverifikasi</p>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ $stats['total_verifikasi'] }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Disetujui</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $stats['total_approved'] }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ditolak</p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ $stats['total_rejected'] }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('pengurus.pengajuan.index') }}"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                Semua
            </a>
            <a href="?status=diajukan"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Menunggu Verifikasi
            </a>
            <a href="?status=verifikasi"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Sedang Diverifikasi
            </a>
            <a href="?status=approved"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Disetujui
            </a>
            <a href="?status=rejected"
               class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
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
                       class="w-full px-3 py-2 border border-gray-300 rounded-md"
                       placeholder="Cari kode, nama anggota...">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="cair" {{ request('status') == 'cair' ? 'selected' : '' }}>Cair</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('pengurus.pengajuan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                Reset
            </a>
        </form>
    </div>

    <!-- Pengajuan List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Anggota
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Pembiayaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tenor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengajuans as $pengajuan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $pengajuan->kode_pengajuan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $pengajuan->anggota->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $pengajuan->anggota->no_anggota }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengajuan->jenisPembiayaan->nama_pembiayaan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $pengajuan->jumlah_pengajuan_formatted }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengajuan->tenor }} bulan
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pengajuan->created_at_formatted }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {!! $pengajuan->status_label !!}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('pengurus.pengajuan.show', $pengajuan->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($pengajuan->status == 'diajukan')
                                    <form action="{{ route('pengurus.pengajuan.verifikasi', $pengajuan->id) }}"
                                          method="POST" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900"
                                                title="Verifikasi"
                                                onclick="return confirm('Apakah Anda yakin ingin memverifikasi pengajuan ini?')">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($pengajuan->status == 'verifikasi')
                                    <form action="{{ route('pengurus.pengajuan.approve', $pengajuan->id) }}"
                                          method="POST" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900"
                                                title="Setujui"
                                                onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('pengurus.pengajuan.reject', $pengajuan->id) }}"
                                          method="POST" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                title="Tolak"
                                                onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($pengajuan->status == 'approved')
                                    <button type="button" class="text-purple-600 hover:text-purple-900 ml-2"
                                            title="Cairkan"
                                            onclick="openCairkanModal({{ $pengajuan->id }}, '{{ $pengajuan->kode_pengajuan }}')">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg">Belum ada pengajuan pembiayaan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pengajuans->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>
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
    document.getElementById('cairkanForm').action = '{{ route("pengurus.pengajuan.cairkan", "") }}'.replace('/cairkan', '/cairkan/' + id);

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