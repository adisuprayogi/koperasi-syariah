@extends('layouts.app')

@section('title', 'Transaksi Simpanan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Transaksi Simpanan</h1>
            <p class="text-gray-600 mt-2">
                Kelola transaksi simpanan anggota
                <span class="text-sm text-indigo-600 font-medium">
                    ({{ \Carbon\Carbon::parse($tanggalDari)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalSampai)->format('d M Y') }})
                </span>
            </p>
        </div>
        <div>
            <a href="{{ route('pengurus.simpanan.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Transaksi
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h2>
        <form action="{{ route('pengurus.simpanan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                <select name="jenis_transaksi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua</option>
                    <option value="setor" {{ request('jenis_transaksi') == 'setor' ? 'selected' : '' }}>Setoran</option>
                    <option value="tarik" {{ request('jenis_transaksi') == 'tarik' ? 'selected' : '' }}>Penarikan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Simpanan</label>
                <select name="jenis_simpanan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua</option>
                    @foreach($jenisSimpanan as $js)
                        <option value="{{ $js->id }}" {{ request('jenis_simpanan_id') == $js->id ? 'selected' : '' }}>
                            {{ $js->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('pengurus.simpanan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Transaksi</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTransaksi ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Setoran</h3>
                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($totalSetoran ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Penarikan</h3>
                    <p class="text-lg font-bold text-red-600">Rp {{ number_format($totalPenarikan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Hari Ini</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalHariIni ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-3 py-3 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Transaksi</h2>
            <div class="text-sm text-gray-500">
                Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} data
            </div>
        </div>

        <!-- Mobile Responsive Table -->
        <div class="overflow-x-auto shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode
                        </th>
                        <th class="hidden sm:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Anggota
                        </th>
                        <th class="hidden md:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis
                        </th>
                        <th class="hidden lg:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="hidden xl:table-cell px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Saldo
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
                    @forelse($transaksi as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $t->kode_transaksi }}</div>
                                <div class="sm:hidden text-xs text-gray-400">
                                    {{ $t->tanggal_transaksi->format('d M') }}
                                </div>
                                @if($t->verified_at)
                                    <div class="text-xs text-gray-400">
                                        <i class="fas fa-check-circle mr-1"></i>{{ $t->verified_at->format('H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td class="hidden sm:table-cell px-3 py-3">
                                <div class="text-sm text-gray-900">{{ $t->tanggal_transaksi->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $t->tanggal_transaksi->format('H:i') }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $t->anggota->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $t->anggota->no_anggota }}</div>
                                <div class="md:hidden text-xs text-gray-400 mt-1">
                                    {{ $t->jenisSimpanan->nama }}
                                </div>
                                <div class="lg:hidden text-xs text-gray-400">
                                    <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $t->jenis_transaksi == 'setor' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $t->jenis_transaksi_label }}
                                    </span>
                                    <span class="ml-1 font-bold {{ $t->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $t->jenis_transaksi == 'setor' ? '+' : '-' }} {{ number_format($t->jumlah, 0, ',', '.') }}
                                    </span>
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-3 py-3">
                                <div class="text-sm text-gray-900 truncate">{{ $t->jenisSimpanan->nama }}</div>
                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full {{ $t->jenis_transaksi == 'setor' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $t->jenis_transaksi_label }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell px-3 py-3">
                                <div class="text-sm font-bold {{ $t->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $t->jenis_transaksi == 'setor' ? '+' : '-' }} {{ number_format($t->jumlah, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="hidden xl:table-cell px-3 py-3">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($t->saldo_setelahnya, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    Sebelum: {{ number_format($t->saldo_sebelumnya, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="hidden 2xl:table-cell px-3 py-3">
                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded-full
                                    @if($t->status == 'verified')
                                        bg-green-100 text-green-800
                                    @elseif($t->status == 'pending')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    {{ $t->status_label }}
                                </span>
                                @if($t->pengurus)
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $t->pengurus->nama_lengkap }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center text-sm font-medium">
                                <a href="{{ route('pengurus.simpanan.show', $t->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <i class="fas fa-exchange-alt text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg">Belum ada data transaksi</p>
                                <p class="text-gray-400 text-sm mt-1">
                                    <a href="{{ route('pengurus.simpanan.create') }}" class="text-indigo-600 hover:text-indigo-500">
                                        Tambah transaksi pertama
                                    </a>
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksi->hasPages())
            <div class="px-3 py-3 border-t border-gray-200">
                {{ $transaksi->links() }}
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Transaksi Simpanan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Jenis Transaksi:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Setoran:</strong> Penambahan saldo simpanan anggota</li>
                    <li><strong>Penarikan:</strong> Pengurangan saldo sesuai dengan ketentuan</li>
                    <li><strong>Verifikasi Otomatis:</strong> Transaksi langsung diverifikasi oleh pengurus</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Format Kode Transaksi:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>STS:</strong> Setoran Simpanan (contoh: STS251209001)</li>
                    <li><strong>TRK:</strong> Tarik Simpanan (contoh: TRK251209001)</li>
                    <li><strong>Struktur:</strong> Kode + Tanggal (YYMMDD) + Nomor Urut (3 digit)</li>
                </ul>
            </div>
            <div class="text-sm text-blue-700">
                <h4 class="font-semibold mb-2">Keterangan Saldo:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Saldo Sebelumnya:</strong> Saldo terakhir sebelum transaksi</li>
                    <li><strong>Saldo Setelahnya:</strong> Saldo setelah transaksi terjadi</li>
                    <li><strong>Validasi:</strong> Penarikan tidak boleh melebihi saldo yang tersedia</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh setiap 30 detik
setTimeout(function() {
    window.location.reload();
}, 30000);
</script>
@endsection