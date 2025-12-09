@extends('layouts.app')

@section('title', 'Edit Pengajuan - ' . $pengajuan->kode_pengajuan)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Pengajuan Pembiayaan</h1>
        <p class="text-gray-600 mt-2">Perbarui data pengajuan: {{ $pengajuan->kode_pengajuan }}</p>
    </div>

    <!-- Alert -->
    @if($pengajuan->status == 'rejected')
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Pengajuan Ditolak</h3>
                <div class="mt-2 text-sm text-red-700">
                    <p>Alasan penolakan: {{ $pengajuan->alasan_penolakan }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('anggota.pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="space-y-8">
            <!-- Informasi Anggota -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Anggota</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->anggota->nama_lengkap }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Anggota</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->anggota->no_anggota }}</p>
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
                                <option value="{{ $jenis->id }}" {{ $pengajuan->jenis_pembiayaan_id == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama_pembiayaan }}
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
                                <option value="{{ $key }}" {{ $pengajuan->tujuan_pembiayaan == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
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
                               min="1000000" step="100000" value="{{ old('jumlah_pengajuan', $pengajuan->jumlah_pengajuan) }}"
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
                               min="1" max="60" value="{{ old('tenor', $pengajuan->tenor) }}"
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
                              placeholder="Jelaskan tujuan pengajuan pembiayaan Anda secara detail...">{{ old('deskripsi', $pengajuan->deskripsi) }}</textarea>
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
                               value="{{ old('no_rekening', $pengajuan->no_rekening) }}"
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
                               value="{{ old('atas_nama', $pengajuan->atas_nama) }}"
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
                <p class="text-sm text-gray-600 mb-4">Upload ulang dokumen yang diperlukan. Kosongkan jika tidak ingin mengubah.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="ktp_file" class="block text-sm font-medium text-gray-700">
                            Scan KTP <span class="text-red-500">*</span>
                            @if($pengajuan->ktp_file)
                                <a href="{{ asset('storage/' . $pengajuan->ktp_file) }}" target="_blank"
                                   class="text-xs text-blue-600 hover:text-blue-800 ml-2">Lihat file sekarang</a>
                            @endif
                        </label>
                        <input type="file" id="ktp_file" name="ktp_file"
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF. Max: 2MB</p>
                        @error('ktp_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kk_file" class="block text-sm font-medium text-gray-700">
                            Scan KK
                            @if($pengajuan->kk_file)
                                <a href="{{ asset('storage/' . $pengajuan->kk_file) }}" target="_blank"
                                   class="text-xs text-blue-600 hover:text-blue-800 ml-2">Lihat file sekarang</a>
                            @endif
                        </label>
                        <input type="file" id="kk_file" name="kk_file"
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF. Max: 2MB</p>
                        @error('kk_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slip_gaji_file" class="block text-sm font-medium text-gray-700">
                            Slip Gaji
                            @if($pengajuan->slip_gaji_file)
                                <a href="{{ asset('storage/' . $pengajuan->slip_gaji_file) }}" target="_blank"
                                   class="text-xs text-blue-600 hover:text-blue-800 ml-2">Lihat file sekarang</a>
                            @endif
                        </label>
                        <input type="file" id="slip_gaji_file" name="slip_gaji_file"
                               accept=".jpg,.jpeg,.png,.pdf"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF. Max: 2MB</p>
                        @error('slip_gaji_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="proposal_file" class="block text-sm font-medium text-gray-700">
                            Proposal Bisnis
                            @if($pengajuan->proposal_file)
                                <a href="{{ asset('storage/' . $pengajuan->proposal_file) }}" target="_blank"
                                   class="text-xs text-blue-600 hover:text-blue-800 ml-2">Lihat file sekarang</a>
                            @endif
                        </label>
                        <input type="file" id="proposal_file" name="proposal_file"
                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF, DOC, DOCX. Max: 5MB</p>
                        @error('proposal_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('anggota.pengajuan.show', $pengajuan->id) }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Pengajuan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahInput = document.getElementById('jumlah_pengajuan');
    const tenorInput = document.getElementById('tenor');

    function updateValues() {
        // Auto-update values if needed
        // Add any auto-calculation logic here
    }

    jumlahInput.addEventListener('input', updateValues);
    tenorInput.addEventListener('input', updateValues);
});
</script>
@endsection