@extends('layouts.app')

@section('title', 'Tambah Transaksi Simpanan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Transaksi Simpanan</h1>
        <p class="text-gray-600 mt-2">Catat transaksi setoran atau penarikan simpanan</p>
    </div>

    <form action="{{ route('pengurus.simpanan.store') }}" method="POST" id="transaksiForm">
        @csrf

        <!-- Main Form Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-exchange-alt mr-2"></i>Detail Transaksi
                </h2>
            </div>

            <div class="px-6 py-6 space-y-6">
                <!-- Data Anggota -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Anggota <span class="text-red-500">*</span>
                        </label>
                        <select name="anggota_id" id="anggota_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($anggota as $a)
                                <option value="{{ $a->id }}" data-nama="{{ $a->nama_lengkap }}" data-no="{{ $a->no_anggota }}">
                                    {{ $a->no_anggota }} - {{ $a->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Simpanan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_simpanan_id" id="jenis_simpanan_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Pilih Jenis Simpanan --</option>
                            @foreach($jenisSimpanan as $js)
                                <option value="{{ $js->id }}"
                                        data-bisa-ditarik="{{ $js->bisa_ditarik ? '1' : '0' }}"
                                        data-nama="{{ $js->nama }}">
                                    {{ $js->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_simpanan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jenis Transaksi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Transaksi <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="jenis_transaksi" value="setor" required
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                       checked>
                                <span class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-arrow-down text-green-600 mr-1"></i>
                                    Setoran
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="jenis_transaksi" value="tarik" required
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-arrow-up text-red-600 mr-1"></i>
                                    Penarikan
                                </span>
                            </label>
                        </div>
                        @error('jenis_transaksi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" name="jumlah" id="jumlah" required
                                   min="1000" step="1000"
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="0">
                        </div>
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimal Rp 1.000</p>
                    </div>
                </div>

                <!-- Tanggal Transaksi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Transaksi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_transaksi" required
                               max="{{ date('Y-m-d') }}"
                               value="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('tanggal_transaksi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan
                    </label>
                    <textarea name="keterangan" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Tambahkan keterangan (opsional)"></textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Saldo Info Card -->
        <div id="saldoInfo" class="bg-white rounded-lg shadow overflow-hidden hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-semibold text-blue-900">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Saldo
                </h3>
            </div>
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Anggota</h4>
                        <p class="text-lg font-semibold text-gray-900" id="infoNama">-</p>
                        <p class="text-sm text-gray-500" id="infoNoAnggota">-</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Saldo Saat Ini</h4>
                        <p class="text-lg font-semibold text-gray-900" id="infoSaldo">Rp 0</p>
                    </div>
                </div>

                <div id="saldoPerhitungan" class="mt-4 p-4 bg-gray-50 rounded-lg hidden">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Perhitungan Saldo</h4>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span>Saldo Sebelumnya:</span>
                            <span id="saldoBefore">Rp 0</span>
                        </div>
                        <div class="flex justify-between font-semibold" id="jumlahTransaksiRow">
                            <span id="labelJumlah">Jumlah:</span>
                            <span id="jumlahDisplay" class="text-green-600">+Rp 0</span>
                        </div>
                        <div class="border-t pt-1 flex justify-between">
                            <span class="font-semibold">Saldo Setelahnya:</span>
                            <span id="saldoAfter" class="font-bold text-lg">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('pengurus.simpanan.index') }}"
               class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <div class="space-x-3">
                <button type="reset"
                        class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                    <i class="fas fa-redo mr-2"></i>Reset
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Transaksi
                </button>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
let currentSaldo = 0;

// Fungsi untuk mengambil saldo
function getSaldo(anggotaId, jenisSimpananId) {
    if (!anggotaId || !jenisSimpananId) {
        return;
    }

    $.get('{{ route("pengurus.api.get-saldo") }}', {
        anggota_id: anggotaId,
        jenis_simpanan_id: jenisSimpananId
    })
    .done(function(response) {
        currentSaldo = response.saldo;
        updateSaldoInfo();
    })
    .fail(function() {
        currentSaldo = 0;
        updateSaldoInfo();
    });
}

// Fungsi untuk update informasi saldo
function updateSaldoInfo() {
    const anggotaSelect = document.getElementById('anggota_id');
    const jenisSimpananSelect = document.getElementById('jenis_simpanan_id');
    const jumlahInput = document.getElementById('jumlah');
    const jenisTransaksi = document.querySelector('input[name="jenis_transaksi"]:checked').value;

    const selectedAnggota = anggotaSelect.options[anggotaSelect.selectedIndex];
    const selectedJenisSimpanan = jenisSimpananSelect.options[jenisSimpananSelect.selectedIndex];
    const jumlah = parseFloat(jumlahInput.value) || 0;

    if (anggotaSelect.value && jenisSimpananSelect.value) {
        document.getElementById('saldoInfo').classList.remove('hidden');
        document.getElementById('infoNama').textContent = selectedAnggota.dataset.nama || '-';
        document.getElementById('infoNoAnggota').textContent = selectedAnggota.dataset.no || '-';
        document.getElementById('infoSaldo').textContent = 'Rp ' + currentSaldo.toLocaleString('id-ID');

        // Update perhitungan
        document.getElementById('saldoPerhitungan').classList.remove('hidden');
        document.getElementById('saldoBefore').textContent = 'Rp ' + currentSaldo.toLocaleString('id-ID');

        // Validasi jika jenis simpanan tidak bisa ditarik
        if (jenisTransaksi === 'tarik') {
            const bisaDitarik = selectedJenisSimpanan.dataset.bisaDitarik === '1';
            if (!bisaDitarik) {
                document.getElementById('jumlahTransaksiRow').classList.add('text-red-600');
                document.getElementById('jumlahDisplay').textContent = 'Tidak dapat ditarik';
                document.getElementById('jumlahDisplay').classList.add('text-red-600');
                document.getElementById('jumlahDisplay').classList.remove('text-green-600');
                document.getElementById('labelJumlah').textContent = 'Status:';
                return;
            }
        }

        document.getElementById('jumlahTransaksiRow').classList.remove('text-red-600');
        document.getElementById('jumlahDisplay').classList.remove('text-red-600');

        if (jenisTransaksi === 'setor') {
            document.getElementById('labelJumlah').textContent = 'Jumlah Setoran:';
            document.getElementById('jumlahDisplay').textContent = '+Rp ' + jumlah.toLocaleString('id-ID');
            document.getElementById('jumlahDisplay').classList.add('text-green-600');
            document.getElementById('jumlahDisplay').classList.remove('text-red-600');
        } else {
            document.getElementById('labelJumlah').textContent = 'Jumlah Penarikan:';
            document.getElementById('jumlahDisplay').textContent = '-Rp ' + jumlah.toLocaleString('id-ID');
            document.getElementById('jumlahDisplay').classList.add('text-red-600');
            document.getElementById('jumlahDisplay').classList.remove('text-green-600');
        }

        const saldoAfter = jenisTransaksi === 'setor' ?
            currentSaldo + jumlah :
            currentSaldo - jumlah;

        document.getElementById('saldoAfter').textContent = 'Rp ' + saldoAfter.toLocaleString('id-ID');

        // Tampilkan warning jika saldo tidak cukup
        if (jenisTransaksi === 'tarik' && saldoAfter < 0) {
            document.getElementById('saldoAfter').classList.add('text-red-600');
        } else {
            document.getElementById('saldoAfter').classList.remove('text-red-600');
        }
    } else {
        document.getElementById('saldoInfo').classList.add('hidden');
    }
}

// Event listeners
document.getElementById('anggota_id').addEventListener('change', function() {
    const jenisSimpananId = document.getElementById('jenis_simpanan_id').value;
    if (jenisSimpananId) {
        getSaldo(this.value, jenisSimpananId);
    }
});

document.getElementById('jenis_simpanan_id').addEventListener('change', function() {
    const anggotaId = document.getElementById('anggota_id').value;
    if (anggotaId) {
        getSaldo(anggotaId, this.value);
    }
});

document.getElementById('jumlah').addEventListener('input', updateSaldoInfo);

document.querySelectorAll('input[name="jenis_transaksi"]').forEach(radio => {
    radio.addEventListener('change', updateSaldoInfo);
});

// Format number input
document.getElementById('jumlah').addEventListener('blur', function() {
    const value = parseFloat(this.value);
    if (!isNaN(value)) {
        this.value = Math.round(value / 1000) * 1000; // Round to nearest 1000
    }
});
</script>
@endsection
@endsection