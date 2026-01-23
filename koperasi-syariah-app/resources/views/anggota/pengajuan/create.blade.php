@extends('layouts.app')

@section('title', 'Ajukan Pembiayaan Baru')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Ajukan Pembiayaan Baru</h1>
        <p class="text-gray-600 mt-2">Isi form di bawah untuk mengajukan pembiayaan</p>
    </div>

    <!-- Progress Indicator -->
    <div class="mb-8">
        <div class="flex items-center">
            <div class="flex items-center text-blue-600">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-medium">1</span>
                <span class="ml-2 text-sm font-medium">Data Pengajuan</span>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>
            <div class="flex items-center text-gray-400">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-white text-sm font-medium">2</span>
                <span class="ml-2 text-sm font-medium">Upload Dokumen</span>
            </div>
            <div class="flex-1 h-0.5 bg-gray-300 mx-4"></div>
            <div class="flex items-center text-gray-400">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-300 text-white text-sm font-medium">3</span>
                <span class="ml-2 text-sm font-medium">Konfirmasi</span>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('anggota.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="w-full"
          id="pengajuanForm" novalidate onsubmit="return handleFormSubmit(event)">
        @csrf
        <div class="space-y-6">
            <!-- Informasi Anggota -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Anggota</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $anggota->nama_lengkap }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Anggota</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $anggota->no_anggota }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. HP</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $anggota->no_hp }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Keanggotaan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($anggota->status_keanggotaan) }}</p>
                    </div>
                </div>
            </div>

            <!-- Data Pembiayaan -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Data Pembiayaan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis_pembiayaan_id" class="block text-sm font-medium text-gray-700">
                            Jenis Pembiayaan <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_pembiayaan_id" name="jenis_pembiayaan_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Pilih Jenis Pembiayaan</option>
                            @foreach($jenisPembiayaan as $jenis)
                                <option value="{{ $jenis->id }}" {{ old('jenis_pembiayaan_id') == $jenis->id ? 'selected' : '' }}
                                        data-margin="{{ $jenis->margin }}"
                                        data-min="{{ $jenis->minimal_pembiayaan }}"
                                        data-max="{{ $jenis->maksimal_pembiayaan }}"
                                        data-tenor-min="{{ $jenis->jangka_waktu_min }}"
                                        data-tenor-max="{{ $jenis->jangka_waktu_max }}">
                                    {{ $jenis->nama_pembiayaan }} ({{ $jenis->margin }}%)
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_pembiayaan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tujuan_pembiayaan" class="block text-sm font-medium text-gray-700">
                            Tujuan Pembiayaan <span class="text-red-500">*</span>
                        </label>
                        <select id="tujuan_pembiayaan" name="tujuan_pembiayaan" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Pilih Tujuan</option>
                            @foreach($tujuanOptions as $key => $value)
                                <option value="{{ $key }}" {{ old('tujuan_pembiayaan') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('tujuan_pembiayaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_pengajuan" class="block text-sm font-medium text-gray-700">
                            Jumlah Pengajuan (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="jumlah_pengajuan" name="jumlah_pengajuan" required
                               min="1000000" step="10000" value="{{ old('jumlah_pengajuan') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Minimal 1.000.000">
                        <p id="jumlah-hint" class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="jumlah-hint-text">Pilih jenis pembiayaan terlebih dahulu</span>
                        </p>
                        @error('jumlah_pengajuan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tenor" class="block text-sm font-medium text-gray-700">
                            Jangka Waktu (bulan) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="tenor" name="tenor" required
                               min="1" max="60" value="{{ old('tenor') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="1-60 bulan">
                        <p id="tenor-hint" class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="tenor-hint-text">Pilih jenis pembiayaan terlebih dahulu</span>
                        </p>
                        @error('tenor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tipe_angsuran" class="block text-sm font-medium text-gray-700">
                            Tipe Angsuran <span class="text-red-500">*</span>
                        </label>
                        <select id="tipe_angsuran" name="tipe_angsuran" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="flat" {{ old('tipe_angsuran', 'flat') == 'flat' ? 'selected' : '' }}>
                                Flat (Tetap setiap bulan)
                            </option>
                            <option value="menurun" {{ old('tipe_angsuran') == 'menurun' ? 'selected' : '' }}>
                                Menurun (Declining)
                            </option>
                            <option value="menaik" {{ old('tipe_angsuran') == 'menaik' ? 'selected' : '' }}>
                                Menaik (Stepped)
                            </option>
                        </select>
                        @error('tipe_angsuran')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Flat: tetap, Menurun: awal lebih besar, Menaik: awal lebih kecil
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                        Deskripsi Pengajuan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" required
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                              placeholder="Jelaskan tujuan pengajuan pembiayaan Anda secara detail...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informasi Rekening -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Rekening</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="no_rekening" class="block text-sm font-medium text-gray-700">
                            No. Rekening <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="no_rekening" name="no_rekening" required
                               value="{{ old('no_rekening') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Nomor rekening Anda">
                        @error('no_rekening')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="atas_nama" class="block text-sm font-medium text-gray-700">
                            Atas Nama <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="atas_nama" name="atas_nama" required
                               value="{{ old('atas_nama') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Nama pemilik rekening">
                        @error('atas_nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dokumen Pendukung -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Dokumen Pendukung</h2>

                {{-- Info File yang Diupload Sebelumnya (setelah validation error) --}}
                @if(session('uploadedFileNames'))
                    <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-md">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-blue-800">File yang perlu diupload ulang</h3>
                                <p class="mt-1 text-sm text-blue-700">Mohon upload kembali file-file berikut:</p>
                                <ul class="mt-2 list-disc list-inside text-sm text-blue-700 space-y-1">
                                    @foreach(session('uploadedFileNames') as $field => $fileName)
                                        @if(is_array($fileName))
                                            @foreach($fileName as $f)
                                                <li>{{ $f }}</li>
                                            @endforeach
                                        @else
                                            <li>{{ $fileName }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-blue-400 hover:text-blue-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-file-upload
                        name="ktp_file"
                        label="Scan KTP"
                        accept=".jpg,.jpeg,.png,.pdf"
                        :required="true"
                        maxSize="2MB" />

                    <x-file-upload
                        name="kk_file"
                        label="Scan KK"
                        accept=".jpg,.jpeg,.png,.pdf"
                        maxSize="2MB" />

                    <x-file-upload
                        name="slip_gaji_file"
                        label="Slip Gaji"
                        accept=".jpg,.jpeg,.png,.pdf"
                        maxSize="2MB" />

                    <x-file-upload
                        name="proposal_file"
                        label="Proposal Bisnis"
                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                        maxSize="5MB" />
                </div>

                <!-- Dokumen Jaminan Tambahan -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Dokumen Jaminan (Opsional)</h4>
                    <x-multiple-file-upload
                        name="jaminan_files"
                        label="Upload Dokumen Jaminan"
                        accept=".jpg,.jpeg,.png,.pdf"
                        :maxFiles="3"
                        maxSize="2MB"
                        help="Upload Sertifikat, BPKB, atau dokumen jaminan lainnya" />
                </div>

                <!-- Dokumen Lainnya -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Dokumen Pendukung Lainnya (Opsional)</h4>
                    <x-multiple-file-upload
                        name="dokumen_lainnya_files"
                        label="Upload Dokumen Pendukung"
                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                        :maxFiles="5"
                        maxSize="2MB"
                        help="Upload dokumen pendukung lainnya seperti surat keterangan, rekening listrik, dll" />
                </div>
            </div>

            <!-- Simulation Results -->
            <div id="simulation" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 hidden">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Simulasi Angsuran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-gray-600">Informasi Pinjaman</span>
                            <i class="fas fa-info-circle text-gray-400"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Pokok Pinjaman</span>
                                <span id="sim-pokok" class="text-sm font-bold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Margin (<span id="margin-percent">10</span>%)</span>
                                <span id="sim-margin" class="text-sm font-bold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t">
                                <span class="text-sm font-medium text-gray-700">Total Pinjaman</span>
                                <span id="sim-total" class="text-base font-bold text-blue-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-green-200 shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-gray-600">Informasi Angsuran</span>
                            <i class="fas fa-calculator text-green-400"></i>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Angsuran Pokok/bln</span>
                                <span id="sim-angsuran-pokok" class="text-sm font-bold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs text-gray-500">Angsuran Margin/bln</span>
                                <span id="sim-angsuran-margin" class="text-sm font-bold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-green-200">
                                <span class="text-sm font-medium text-gray-700">Total Angsuran/bln</span>
                                <span id="sim-total-angsuran" class="text-base font-bold text-green-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('anggota.pengajuan.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit" id="submit-pengajuan-btn"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Ajukan Sekarang
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahInput = document.getElementById('jumlah_pengajuan');
    const tenorInput = document.getElementById('tenor');
    const jenisPembiayaanSelect = document.getElementById('jenis_pembiayaan_id');
    const simulation = document.getElementById('simulation');
    const marginPercentSpan = document.getElementById('margin-percent');
    const jumlahHintText = document.getElementById('jumlah-hint-text');
    const tenorHintText = document.getElementById('tenor-hint-text');

    function getMarginPercent() {
        const selectedOption = jenisPembiayaanSelect.options[jenisPembiayaanSelect.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            return 0; // Return 0 if no option selected
        }
        const margin = parseFloat(selectedOption.getAttribute('data-margin'));
        return !isNaN(margin) ? margin : 0;
    }

    function updateMinMax() {
        const selectedOption = jenisPembiayaanSelect.options[jenisPembiayaanSelect.selectedIndex];

        if (selectedOption && selectedOption.value) {
            const minJumlah = parseFloat(selectedOption.getAttribute('data-min')) || 0;
            const maxJumlah = parseFloat(selectedOption.getAttribute('data-max')) || 0;
            const tenorMin = parseInt(selectedOption.getAttribute('data-tenor-min')) || 1;
            const tenorMax = parseInt(selectedOption.getAttribute('data-tenor-max')) || 60;

            // Update jumlah input
            jumlahInput.min = minJumlah;
            if (maxJumlah > 0) {
                jumlahInput.max = maxJumlah;
                jumlahHintText.textContent = `Minimal: Rp ${minJumlah.toLocaleString('id-ID')}, Maksimal: Rp ${maxJumlah.toLocaleString('id-ID')}`;
            } else {
                jumlahInput.removeAttribute('max');
                jumlahHintText.textContent = `Minimal: Rp ${minJumlah.toLocaleString('id-ID')}`;
            }

            // Update tenor input
            tenorInput.min = tenorMin;
            tenorInput.max = tenorMax;
            tenorHintText.textContent = `Minimal: ${tenorMin} bulan, Maksimal: ${tenorMax} bulan`;

            // Clear values if outside new range
            if (parseFloat(jumlahInput.value) < minJumlah || (maxJumlah > 0 && parseFloat(jumlahInput.value) > maxJumlah)) {
                jumlahInput.value = '';
            }
            if (parseInt(tenorInput.value) < tenorMin || parseInt(tenorInput.value) > tenorMax) {
                tenorInput.value = '';
            }
        } else {
            // Reset to defaults
            jumlahInput.min = 0;
            jumlahInput.removeAttribute('max');
            tenorInput.min = 1;
            tenorInput.max = 60;
            jumlahHintText.textContent = 'Pilih jenis pembiayaan terlebih dahulu';
            tenorHintText.textContent = 'Pilih jenis pembiayaan terlebih dahulu';
        }
    }

    function calculateSimulation() {
        const jumlah = parseFloat(jumlahInput.value) || 0;
        const tenor = parseInt(tenorInput.value) || 0;
        const marginPercent = getMarginPercent();

        // Only show simulation if jenis pembiayaan is selected
        const selectedOption = jenisPembiayaanSelect.options[jenisPembiayaanSelect.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            simulation.classList.add('hidden');
            return;
        }

        if (jumlah > 0 && tenor > 0) {
            // Rumus BARU: Margin per bulan dikalikan tenor (sesuai backend)
            const marginPerBulan = jumlah * (marginPercent / 100);
            const jumlahMargin = marginPerBulan * tenor;
            const totalPembiayaan = jumlah + jumlahMargin;
            const angsuranPokok = jumlah / tenor;
            const angsuranMargin = marginPerBulan;
            const totalAngsuran = angsuranPokok + angsuranMargin;

            // Update margin percentage display
            marginPercentSpan.textContent = marginPercent + '%';

            // Update simulation display
            document.getElementById('sim-pokok').textContent = 'Rp ' + formatNumber(jumlah);
            document.getElementById('sim-margin').textContent = 'Rp ' + formatNumber(jumlahMargin);
            document.getElementById('sim-total').textContent = 'Rp ' + formatNumber(totalPembiayaan);
            document.getElementById('sim-angsuran-pokok').textContent = 'Rp ' + formatNumber(angsuranPokok);
            document.getElementById('sim-angsuran-margin').textContent = 'Rp ' + formatNumber(angsuranMargin);
            document.getElementById('sim-total-angsuran').textContent = 'Rp ' + formatNumber(totalAngsuran);

            simulation.classList.remove('hidden');
        } else {
            simulation.classList.add('hidden');
        }
    }

    function formatNumber(num) {
        return Math.round(num).toLocaleString('id-ID');
    }

    // Update min/max when jenis pembiayaan changes
    jenisPembiayaanSelect.addEventListener('change', function() {
        updateMinMax();
        calculateSimulation();
    });

    // Calculate on input change
    jumlahInput.addEventListener('input', calculateSimulation);
    tenorInput.addEventListener('input', calculateSimulation);

    // Initialize on page load
    updateMinMax();
});

// Handle form submit
function handleFormSubmit(event) {
    console.log('Form submit triggered');
    const isValid = validateRequiredFiles();

    if (!isValid) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        console.log('Form submission prevented - missing files');
        return false;
    }

    console.log('Form validation passed - submitting');
    return true;
}

// Validasi file wajib sebelum submit
function validateRequiredFiles() {
    const requiredFiles = document.querySelectorAll('input[type="file"][required]');
    console.log('Required files found:', requiredFiles.length);

    const missingFiles = [];

    requiredFiles.forEach(function(input) {
        console.log('Checking file input:', input.name, 'Files:', input.files ? input.files.length : 'none');

        if (!input.files || input.files.length === 0) {
            // Fallback ke nama field yang lebih deskriptif
            const nameMapping = {
                'ktp_file': 'Scan KTP',
                'kk_file': 'Scan KK',
                'slip_gaji_file': 'Slip Gaji',
                'proposal_file': 'Proposal Bisnis'
            };
            const labelName = nameMapping[input.name] || input.name;

            missingFiles.push({
                name: labelName,
                element: input
            });
        }
    });

    console.log('Missing files:', missingFiles.length);

    if (missingFiles.length > 0) {
        // Disable tombol submit sementara
        const submitBtn = document.getElementById('submit-pengajuan-btn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        // Tampilkan alert dengan daftar file yang belum diupload
        showFileAlert(missingFiles.map(f => f.name));

        // Scroll ke file pertama yang belum diisi
        if (missingFiles[0].element) {
            const container = missingFiles[0].element.closest('.space-y-2, .space-y-3, .border-2');
            if (container) {
                setTimeout(() => {
                    container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Highlight dengan border merah sementara
                    container.classList.add('ring-2', 'ring-red-500');
                    setTimeout(() => {
                        container.classList.remove('ring-2', 'ring-red-500');
                    }, 3000);
                }, 300);
            }
        }

        return false;
    }

    return true;
}

function showFileAlert(missingFiles) {
    // Hapus modal lama jika ada
    const existingModal = document.getElementById('file-alert-modal');
    if (existingModal) {
        existingModal.remove();
    }

    // Buat modal overlay
    const overlay = document.createElement('div');
    overlay.id = 'file-alert-modal';
    overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4';
    overlay.innerHTML = `
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="bg-red-50 px-6 py-4 border-b border-red-200 rounded-t-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-bold text-red-800">
                            File Wajib Belum Diupload!
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-4">
                <p class="text-sm text-gray-700 mb-3">Mohon upload file berikut sebelum submit:</p>
                <ul class="space-y-2">
                    ${missingFiles.map(f => `
                        <li class="flex items-start">
                            <i class="fas fa-times-circle text-red-500 mt-0.5 mr-2 flex-shrink-0"></i>
                            <span class="text-sm font-medium text-gray-800">${f}</span>
                        </li>
                    `).join('')}
                </ul>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 rounded-b-lg">
                <div class="flex justify-end">
                    <button type="button" id="file-alert-ok-btn" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <i class="fas fa-check mr-1"></i> Mengerti
                    </button>
                </div>
            </div>
        </div>
    `;

    // Tambahkan event listener untuk close ketika klik overlay
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeFileAlert();
        }
    });

    // Tambahkan event listener untuk tombol OK (lebih aman daripada onclick inline)
    const okBtn = overlay.querySelector('#file-alert-ok-btn');
    if (okBtn) {
        okBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeFileAlert();
        });
    }

    // Tambahkan event listener untuk tombol ESC
    document.addEventListener('keydown', handleEscKey);

    document.body.appendChild(overlay);

    // Disable scroll pada body
    document.body.style.overflow = 'hidden';
}

function handleEscKey(e) {
    if (e.key === 'Escape') {
        closeFileAlert();
    }
}

function closeFileAlert() {
    const modal = document.getElementById('file-alert-modal');
    if (modal) {
        modal.remove();
    }

    // Re-enable scroll pada body
    document.body.style.overflow = '';

    // Hapus event listener ESC
    document.removeEventListener('keydown', handleEscKey);

    // Hapus juga alert box lama jika ada (untuk kompatibilitas)
    const alertBox = document.getElementById('file-alert-box');
    if (alertBox) {
        alertBox.remove();
    }

    // Blur dan re-enable tombol submit
    const submitBtn = document.getElementById('submit-pengajuan-btn');
    if (submitBtn) {
        submitBtn.blur();
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        submitBtn.innerHTML = 'Ajukan Sekarang';
    }

    return false;
}
</script>
@endsection