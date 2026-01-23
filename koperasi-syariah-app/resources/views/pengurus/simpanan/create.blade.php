@extends('layouts.app')

@section('title', 'Tambah Transaksi Simpanan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tambah Transaksi Simpanan</h1>
        <p class="text-gray-600 mt-2">Catat transaksi setoran atau penarikan simpanan</p>
    </div>

    <form action="{{ route('pengurus.simpanan.store') }}" method="POST" enctype="multipart/form-data" id="transaksiForm">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                data-select-saldo="true">
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                data-select-saldo="true">
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bulan Simpanan <span class="text-red-500">*</span>
                        </label>
                        <select name="bulan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Pilih Bulan --</option>
                            <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>Januari</option>
                            <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>Februari</option>
                            <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>Maret</option>
                            <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>Mei</option>
                            <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>Juni</option>
                            <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>Juli</option>
                            <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>Agustus</option>
                            <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>Oktober</option>
                            <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>Desember</option>
                        </select>
                        @error('bulan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Bulan simpanan yang dibayar</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tahun Simpanan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tahun" required
                               min="2020" max="{{ date('Y') + 1 }}"
                               value="{{ date('Y') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Tahun simpanan yang dibayar</p>
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

                <!-- Bukti Pembayaran -->
                <div class="border-t border-gray-200 pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-file-upload mr-1"></i>Bukti Pembayaran
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition-colors"
                         id="dropZone">
                        <div class="space-y-1 text-center">
                            <div class="flex justify-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            </div>
                            <div class="flex text-sm text-gray-600">
                                <label for="bukti_transaksi" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Pilih file</span>
                                    <input id="bukti_transaksi" name="bukti_transaksi" type="file"
                                           accept="image/*,.pdf"
                                           class="sr-only">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG, JPEG, PDF (Maks. 500KB)
                            </p>
                            <p id="fileName" class="text-sm text-indigo-600 font-medium hidden"></p>
                            <p id="fileError" class="text-sm text-red-600 font-medium hidden"></p>
                        </div>
                    </div>
                    @error('bukti_transaksi')
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

@push('scripts')
<script>
let currentSaldo = 0;
let saldoLoaded = false;

// Fungsi untuk mengambil saldo
function getSaldo(anggotaId, jenisSimpananId) {
    console.log('=== GET SALDO CALLED === anggotaId:', anggotaId, 'jenisSimpananId:', jenisSimpananId);

    if (!anggotaId || !jenisSimpananId) {
        return;
    }

    // Cek apakah jQuery tersedia
    if (typeof $ === 'undefined') {
        console.error('jQuery is NOT loaded! $ is undefined');
        return;
    }

    const url = '{{ route("pengurus.api.get-saldo") }}';
    console.log('Calling AJAX to:', url);

    $.ajax({
        url: url,
        method: 'GET',
        data: {
            anggota_id: anggotaId,
            jenis_simpanan_id: jenisSimpananId
        },
        success: function(response) {
            console.log('AJAX SUCCESS:', response);
            currentSaldo = parseFloat(response.saldo) || 0;
            saldoLoaded = true;
            updateSaldoInfo();
        },
        error: function(xhr, status, error) {
            console.error('AJAX ERROR:', error, 'Status:', xhr.status, 'Response:', xhr.responseText);
            currentSaldo = 0;
            saldoLoaded = true;
            updateSaldoInfo();
        }
    });
}

// Fungsi untuk update informasi saldo
function updateSaldoInfo() {
    const anggotaSelect = document.getElementById('anggota_id');
    const jenisSimpananSelect = document.getElementById('jenis_simpanan_id');
    const jumlahInput = document.getElementById('jumlah');
    const jenisTransaksiRadio = document.querySelector('input[name="jenis_transaksi"]:checked');

    console.log('=== UPDATE INFO CALLED === currentSaldo:', currentSaldo, 'saldoLoaded:', saldoLoaded);

    if (!anggotaSelect.value || !jenisSimpananSelect.value || !jenisTransaksiRadio) {
        document.getElementById('saldoInfo').classList.add('hidden');
        return;
    }

    const jenisTransaksi = jenisTransaksiRadio.value;
    const selectedAnggota = anggotaSelect.options[anggotaSelect.selectedIndex];
    const selectedJenisSimpanan = jenisSimpananSelect.options[jenisSimpananSelect.selectedIndex];
    const jumlah = parseFloat(jumlahInput.value) || 0;

    document.getElementById('saldoInfo').classList.remove('hidden');
    document.getElementById('infoNama').textContent = selectedAnggota.dataset.nama || '-';
    document.getElementById('infoNoAnggota').textContent = selectedAnggota.dataset.no || '-';
    document.getElementById('infoSaldo').textContent = 'Rp ' + currentSaldo.toLocaleString('id-ID');

    // Update perhitungan - PASTIKAN currentSaldo terupdate dengan benar
    document.getElementById('saldoPerhitungan').classList.remove('hidden');

    const saldoBeforeValue = 'Rp ' + currentSaldo.toLocaleString('id-ID');
    document.getElementById('saldoBefore').textContent = saldoBeforeValue;

    console.log('saldoBefore set to:', saldoBeforeValue);
    console.log('saldoBefore element now:', document.getElementById('saldoBefore').textContent);

    // Validasi jika jenis simpanan tidak bisa ditarik
    if (jenisTransaksi === 'tarik') {
        const bisaDitarik = selectedJenisSimpanan.dataset.bisaDitarik === '1';
        if (!bisaDitarik) {
            document.getElementById('jumlahTransaksiRow').classList.add('text-red-600');
            document.getElementById('jumlahDisplay').textContent = 'Tidak dapat ditarik';
            document.getElementById('jumlahDisplay').classList.add('text-red-600');
            document.getElementById('jumlahDisplay').classList.remove('text-green-600');
            document.getElementById('labelJumlah').textContent = 'Status:';
            document.getElementById('saldoAfter').textContent = 'Rp ' + currentSaldo.toLocaleString('id-ID');
            document.getElementById('saldoAfter').classList.remove('text-red-600');
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

    // Hitung saldo setelahnya
    let saldoAfter;
    if (jenisTransaksi === 'setor') {
        saldoAfter = currentSaldo + jumlah;
    } else {
        saldoAfter = currentSaldo - jumlah;
    }

    console.log('=== PERHITUNGAN SALDO ===');
    console.log('currentSaldo:', currentSaldo);
    console.log('jumlah:', jumlah);
    console.log('jenisTransaksi:', jenisTransaksi);
    console.log('saldoAfter:', saldoAfter);

    document.getElementById('saldoAfter').textContent = 'Rp ' + saldoAfter.toLocaleString('id-ID');

    // Tampilkan warning jika saldo tidak cukup
    if (jenisTransaksi === 'tarik' && saldoAfter < 0) {
        document.getElementById('saldoAfter').classList.add('text-red-600');
    } else {
        document.getElementById('saldoAfter').classList.remove('text-red-600');
    }
}

// Event listeners - Wrapped in DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CONTENT LOADED ===');

    const anggotaSelect = document.getElementById('anggota_id');
    const jenisSimpananSelect = document.getElementById('jenis_simpanan_id');

    console.log('anggotaSelect element:', anggotaSelect);
    console.log('jenisSimpananSelect element:', jenisSimpananSelect);

    // Event listener untuk anggota_id
    if (anggotaSelect) {
        anggotaSelect.addEventListener('change', function() {
            console.log('anggota_id changed to:', this.value);
            const jsId = jenisSimpananSelect ? jenisSimpananSelect.value : null;
            if (this.value && jsId) {
                console.log('Calling getSaldo with:', this.value, jsId);
                getSaldo(this.value, jsId);
            }
        });
        console.log('anggota_id change listener attached');
    } else {
        console.log('ERROR: anggota_id element NOT FOUND!');
    }

    // Event listener untuk jenis_simpanan_id
    if (jenisSimpananSelect) {
        jenisSimpananSelect.addEventListener('change', function() {
            console.log('jenis_simpanan_id changed to:', this.value);
            const angId = anggotaSelect ? anggotaSelect.value : null;
            if (this.value && angId) {
                console.log('Calling getSaldo with:', angId, this.value);
                getSaldo(angId, this.value);
            }
        });
        console.log('jenis_simpanan_id change listener attached');
    } else {
        console.log('ERROR: jenis_simpanan_id element NOT FOUND!');
    }

    // Cek apakah sudah ada nilai terpilih (dari old input setelah validation error)
    if (anggotaSelect && jenisSimpananSelect) {
        const anggotaId = anggotaSelect.value;
        const jenisSimpananId = jenisSimpananSelect.value;

        console.log('Initial values - anggota_id:', anggotaId, 'jenis_simpanan_id:', jenisSimpananId);

        if (anggotaId && jenisSimpananId) {
            console.log('Both values already selected, calling getSaldo directly...');
            getSaldo(anggotaId, jenisSimpananId);
        }
    }

    // Event listener untuk jumlah
    const jumlahInput = document.getElementById('jumlah');
    if (jumlahInput) {
        jumlahInput.addEventListener('input', updateSaldoInfo);
        jumlahInput.addEventListener('keyup', updateSaldoInfo);

        // Format number input
        jumlahInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = Math.round(value / 1000) * 1000; // Round to nearest 1000
            }
        });
    }

    // Event listener untuk jenis_transaksi (radio buttons)
    document.querySelectorAll('input[name="jenis_transaksi"]').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('jenis_transaksi changed to:', this.value);
            updateSaldoInfo();
        });
    });

    console.log('=== EVENT LISTENERS ATTACHED ===');
});

// Show file name when file is selected
document.getElementById('bukti_transaksi').addEventListener('change', function() {
    const fileName = document.getElementById('fileName');
    const fileError = document.getElementById('fileError');
    const dropZone = document.getElementById('dropZone');

    // Reset states
    fileName.classList.add('hidden');
    fileError.classList.add('hidden');
    dropZone.classList.remove('border-red-500', 'bg-red-50');

    if (this.files && this.files[0]) {
        const file = this.files[0];

        // Validate file size (500KB = 500 * 1024 bytes)
        const maxSize = 500 * 1024;
        if (file.size > maxSize) {
            fileError.textContent = 'Ukuran file terlalu besar! Maksimal 500KB.';
            fileError.classList.remove('hidden');
            dropZone.classList.add('border-red-500', 'bg-red-50');
            this.value = ''; // Clear the file input
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!validTypes.includes(file.type)) {
            fileError.textContent = 'Format file tidak didukung! Gunakan PNG, JPG, JPEG, atau PDF.';
            fileError.classList.remove('hidden');
            dropZone.classList.add('border-red-500', 'bg-red-50');
            this.value = ''; // Clear the file input
            return;
        }

        // Show file name
        fileName.textContent = 'File terpilih: ' + file.name + ' (' + formatFileSize(file.size) + ')';
        fileName.classList.remove('hidden');
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
    }
});

// Drag and drop functionality
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('bukti_transaksi');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
}

function unhighlight(e) {
    dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    dropZone.classList.remove('border-red-500', 'bg-red-50');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;

    if (files && files.length > 0) {
        fileInput.files = files;
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        fileInput.dispatchEvent(event);
    }
}

// Helper function to format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>
@endpush
@endsection