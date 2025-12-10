@extends('layouts.app')

@section('title', 'Ajukan Pembiayaan Baru')

@section('content')
<div class="max-w-4xl mx-auto">
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
    <form action="{{ route('anggota.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-8">
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
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_pembiayaan }}</option>
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
                                <option value="{{ $key }}">{{ $value }}</option>
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
                               min="1000000" step="100000" value="{{ old('jumlah_pengajuan') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Minimal 1.000.000">
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
                        @error('tenor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Pokok Pinjaman</p>
                        <p id="sim-pokok" class="text-lg font-bold text-gray-900">Rp 0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Margin (10%)</p>
                        <p id="sim-margin" class="text-lg font-bold text-gray-900">Rp 0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Pinjaman</p>
                        <p id="sim-total" class="text-lg font-bold text-gray-900">Rp 0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Angsuran Pokok/bln</p>
                        <p id="sim-angsuran-pokok" class="text-lg font-bold text-gray-900">Rp 0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Angsuran Margin/bln</p>
                        <p id="sim-angsuran-margin" class="text-lg font-bold text-gray-900">Rp 0</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Angsuran/bln</p>
                        <p id="sim-total-angsuran" class="text-lg font-bold text-green-600">Rp 0</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('anggota.pengajuan.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit"
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
    const simulation = document.getElementById('simulation');

    function calculateSimulation() {
        const jumlah = parseFloat(jumlahInput.value) || 0;
        const tenor = parseInt(tenorInput.value) || 0;

        if (jumlah > 0 && tenor > 0) {
            const marginPercent = 10; // Default 10%
            const jumlahMargin = jumlah * (marginPercent / 100);
            const totalPembiayaan = jumlah + jumlahMargin;
            const angsuranPokok = jumlah / tenor;
            const angsuranMargin = jumlahMargin / tenor;
            const totalAngsuran = angsuranPokok + angsuranMargin;

            // Update simulation display
            document.getElementById('sim-pokok').textContent = 'Rp ' + formatNumber(jumlah);
            document.getElementById('sim-margin').textContent = 'Rp ' + formatNumber(jumlahMargin);
            document.getElementById('sim-total').textContent = 'Rp ' + formatNumber(totalPembiayaan);
            document.getElementById('sim-angsuran-pokok').textContent = 'Rp ' + formatNumber(angsuranPokok);
            document.getElementById('sim-angsuran-margin').textContent = 'Rp ' + formatNumber(angsuranMargin);
            document.getElementById('sim-total-angsuran').textContent = 'Rp ' + formatNumber(totalAngsuran);

            simulation.classList.remove('hidden');
        }
    }

    function formatNumber(num) {
        return Math.round(num).toLocaleString('id-ID');
    }

    // Calculate on input change
    jumlahInput.addEventListener('input', calculateSimulation);
    tenorInput.addEventListener('input', calculateSimulation);
});
</script>
@endsection