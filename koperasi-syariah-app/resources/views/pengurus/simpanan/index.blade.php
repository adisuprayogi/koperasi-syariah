@extends('layouts.app')

@section('title', 'Transaksi Simpanan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Transaksi Simpanan</h1>
            <p class="text-gray-600 mt-2">
                Kelola transaksi simpanan anggota
                <span class="text-sm text-primary-600 font-medium">
                    ({{ \Carbon\Carbon::parse($tanggalDari)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalSampai)->format('d M Y') }})
                </span>
            </p>
        </div>
        <a href="{{ route('pengurus.simpanan.create') }}"
           class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Transaksi
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Data</h2>
        <form action="{{ route('pengurus.simpanan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ $tanggalDari }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ $tanggalSampai }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                <select name="jenis_transaksi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua</option>
                    <option value="setor" {{ request('jenis_transaksi') == 'setor' ? 'selected' : '' }}>Setoran</option>
                    <option value="tarik" {{ request('jenis_transaksi') == 'tarik' ? 'selected' : '' }}>Penarikan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Simpanan</label>
                <select name="jenis_simpanan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua</option>
                    @foreach($jenisSimpanan as $js)
                        <option value="{{ $js->id }}" {{ request('jenis_simpanan_id') == $js->id ? 'selected' : '' }}>
                            {{ $js->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-4 flex justify-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('pengurus.simpanan.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Transaksi</h3>
                    <p class="text-lg font-bold text-gray-900">{{ $totalTransaksi ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-down text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Setoran</h3>
                    <p class="text-lg font-bold text-green-600">{{ number_format($totalSetoran ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-up text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Total Penarikan</h3>
                    <p class="text-lg font-bold text-red-600">{{ number_format($totalPenarikan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-day text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <h3 class="text-xs font-medium text-gray-500">Hari Ini</h3>
                    <p class="text-lg font-bold text-purple-600">{{ $totalHariIni ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <!-- Table Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold mb-1 flex items-center text-gray-900">
                        <i class="fas fa-list-alt mr-2 text-primary-600"></i>
                        Daftar Transaksi
                    </h2>
                    <p class="text-gray-500 text-xs">Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} data</p>
                </div>
                @if($transaksi->count() > 0)
                    <div class="mt-3 sm:mt-0 bg-primary-50 rounded-lg px-4 py-2">
                        <p class="text-2xl font-bold text-center text-primary-600">{{ $transaksi->count() }}</p>
                        <p class="text-xs text-gray-600 text-center">Transaksi</p>
                    </div>
                @endif
            </div>
        </div>

        @if($transaksi->count() > 0)
            <!-- Responsive Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Saldo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($transaksi as $t)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Kode -->
                                <td class="px-4 py-3">
                                    <div class="text-xs font-mono font-semibold text-indigo-600">{{ $t->kode_transaksi }}</div>
                                    @if($t->verified_at)
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-check-circle text-green-500 mr-1"></i>{{ $t->verified_at->format('H:i') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Tanggal -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $t->tanggal_transaksi->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $t->tanggal_transaksi->format('H:i') }}</div>
                                </td>

                                <!-- Anggota -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $t->anggota->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $t->anggota->no_anggota }}</div>
                                </td>

                                <!-- Jenis -->
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900">{{ $t->jenisSimpanan->nama }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full {{ $t->jenis_transaksi == 'setor' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i class="fas {{ $t->jenis_transaksi == 'setor' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                                        {{ $t->jenis_transaksi_label }}
                                    </span>
                                </td>

                                <!-- Jumlah -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold {{ $t->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $t->jenis_transaksi == 'setor' ? '+' : '-' }} {{ number_format($t->jumlah, 0, ',', '.') }}
                                    </div>
                                </td>

                                <!-- Saldo -->
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-gray-900">{{ number_format($t->saldo_setelahnya, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">Before: {{ number_format($t->saldo_sebelumnya, 0, ',', '.') }}</div>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                        @if($t->status == 'verified')
                                            bg-green-100 text-green-800
                                        @elseif($t->status == 'pending')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-red-100 text-red-800
                                        @endif">
                                        @if($t->status == 'verified')
                                            <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                                        @elseif($t->status == 'pending')
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        @else
                                            <i class="fas fa-times-circle mr-1"></i>Ditolak
                                        @endif
                                    </span>
                                    @if($t->pengurus)
                                        <div class="text-xs text-gray-500 mt-1">{{ $t->pengurus->nama_lengkap }}</div>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('pengurus.simpanan.show', $t->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 hover:bg-primary-200 text-primary-600 hover:text-primary-700 transition-colors"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transaksi->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $transaksi->links('pagination.custom') }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="py-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exchange-alt text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Transaksi</h3>
                    <p class="text-gray-600 mb-6 text-sm">Tambahkan transaksi pertama</p>
                    <a href="{{ route('pengurus.simpanan.create') }}" class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-primary-600 to-emerald-600 hover:from-primary-700 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Transaksi
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Informasi Transaksi Simpanan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-700">
            <div>
                <h4 class="font-semibold mb-2">Jenis Transaksi:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Setoran:</strong> Penambahan saldo simpanan</li>
                    <li><strong>Penarikan:</strong> Pengurangan sesuai ketentuan</li>
                    <li><strong>Verifikasi:</strong> Transaksi langsung diverifikasi</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Format Kode:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>STS:</strong> Setoran (STS251209001)</li>
                    <li><strong>TRK:</strong> Tarik (TRK251209001)</li>
                    <li><strong>Struktur:</strong> Kode + Tanggal + Urut</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Keterangan Saldo:</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Saldo Awal:</strong> Sebelum transaksi</li>
                    <li><strong>Saldo Akhir:</strong> Setelah transaksi</li>
                    <li><strong>Validasi:</strong> Tarik ≤ saldo tersedia</li>
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
