@extends('layouts.app')

@section('title', 'Import Data Anggota')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Import Data Anggota</h1>
                                <p class="text-sm text-gray-600">Import data anggota dari file Excel</p>
                            </div>
                        </div>
                        <a href="{{ route('pengurus.anggota.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Download Template Section -->
        <div class="mb-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-blue-900 mb-1">Template Excel Import</h2>
                        <p class="text-blue-700">Download template untuk format import data anggota</p>
                    </div>
                    <a href="{{ route('pengurus.anggota.import.template') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Template
                    </a>
                </div>
            </div>
        </div>

        <!-- Nomor Anggota Format Guide -->
        <div class="mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-semibold text-blue-900 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Format Nomor Anggota: <code class="bg-blue-100 px-1 rounded">YYMM.00001</code>
                        </h4>
                        <div class="text-sm text-blue-700">
                            <span class="inline-block mr-4">
                                🔄 <strong>Auto:</strong> Kosongkan kolom K
                            </span>
                            <span class="inline-block">
                                ✏️ <strong>Manual:</strong> Isi dengan format sama
                            </span>
                        </div>
                    </div>
                    <div class="ml-4 text-xs text-blue-600">
                        <div>📅 YYMM: 2512 = Des 2025</div>
                        <div>🔢 00001: Nomor urut</div>
                        <div>🔄 Reset per bulan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Upload File Excel</h2>
                    <p class="text-sm text-gray-600">Pilih file Excel yang sudah diisi sesuai template</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('pengurus.anggota.import.store') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf

                        <div class="mb-6">
                            <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">
                                File Excel <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="file"
                                       id="excel_file"
                                       name="excel_file"
                                       accept=".xlsx,.xls"
                                       class="hidden"
                                       required
                                       onchange="handleFileSelect(this)">

                                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M24 14v20m-10-10h20" />
                                    </svg>
                                    <p class="text-gray-600 mb-2">Klik atau drag file ke area ini</p>
                                    <p class="text-sm text-gray-500">Format: .xlsx atau .xls (Maksimal 10MB)</p>
                                </div>

                                <div id="filePreview" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-gray-900" id="fileName"></p>
                                                <p class="text-sm text-gray-500" id="fileSize"></p>
                                            </div>
                                        </div>
                                        <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @error('excel_file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                </svg>
                                Import Data
                            </button>
                            <a href="{{ route('pengurus.anggota.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Format Table -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Format File Excel</h2>
                    <p class="text-sm text-gray-600">Pastikan data sesuai dengan format berikut:</p>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kolom</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wajib</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contoh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">A</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">nama_lengkap</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Text</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Wajib</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Ahmad Rizki</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">B</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">jenis_kelamin</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Pilihan</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Opsional</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">L atau P</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">C</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">tempat_lahir</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Text</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Opsional</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Jakarta</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">D</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">tanggal_lahir</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Date</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Opsional</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">1990-01-15</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">E</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">alamat</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Text</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Opsional</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Jl. Merdeka No. 123</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">F</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">no_hp</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Text</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Opsional</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">08123456789</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">G</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">pekerjaan</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Text</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Opsional</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Pegawai Swasta</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">H</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">penghasilan</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Number</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Opsional</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">5000000</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">I</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">email</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Email</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Wajib</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">email@example.com</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">J</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">password</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Text</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Wajib</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">password123</td>
                                </tr>
                                <tr class="hover:bg-gray-50 bg-blue-500">
                                    <td class="px-4 py-3 text-sm font-medium text-white">K</td>
                                    <td class="px-4 py-3 text-sm font-medium text-white">no_anggota</td>
                                    <td class="px-4 py-3 text-sm text-white">Text</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-white text-blue-600 rounded-full">YYMM.00001</span></td>
                                    <td class="px-4 py-3 text-sm text-white font-mono">2512.00001</td>
                                </tr>
                                <tr class="hover:bg-gray-50 table-warning">
                                    <td class="px-4 py-3 text-sm font-medium text-orange-900">L</td>
                                    <td class="px-4 py-3 text-sm font-medium text-orange-900">nik</td>
                                    <td class="px-4 py-3 text-sm text-orange-700">Number</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Wajib</span></td>
                                    <td class="px-4 py-3 text-sm text-orange-700">16 digit angka</td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">M</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">no_npwp</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">Text</td>
                                    <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Opsional</span></td>
                                    <td class="px-4 py-3 text-sm text-gray-600">123456789012345</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Catatan Penting -->
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800">Penting:</h4>
                                <ul class="mt-1 text-sm text-yellow-700 space-y-1">
                                    <li>• <strong>Nomor Anggota (Kolom K):</strong> Format WAJIB <code>YYMM.00001</code></li>
                                    <li>• <strong>Auto-generate:</strong> Kosongkan kolom K untuk generate otomatis</li>
                                    <li>• <strong>Manual Input:</strong> Boleh diisi dengan format yang sama (contoh: 2512.00015, 2601.00100)</li>
                                    <li>• <strong>Validasi:</strong> Format harus 4 digit + titik + 5 digit (contoh: 2512.00001)</li>
                                    <li>• <strong>Contoh Valid:</strong> 2512.00001, 2512.00100, 2601.00015</li>
                                    <li>• <strong>Contoh Invalid:</strong> ANG-001, 2512-00001, 12345</li>
                                    <li>• <strong>NIK (Kolom L):</strong> WAJIB 16 digit angka, tidak boleh duplikat</li>
                                    <li>• <strong>NPWP (Kolom M):</strong> Opsional, bisa dikosongkan</li>
                                    <li>• Email harus unique dan belum terdaftar di sistem</li>
                                    <li>• Format tanggal: YYYY-MM-DD</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Report -->
        @if(session('error_count') > 0 && session('errors'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-red-800">{{ session('error_count') }} Data Gagal Diimport</h3>
                    </div>
                    <a href="{{ route('pengurus.anggota.import.error-report') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Laporan Error
                    </a>
                </div>
                <p class="text-red-700">Beberapa data gagal diimport karena tidak sesuai format atau terdapat duplikasi data. Download laporan error untuk melihat detailnya.</p>
            </div>
        @endif
    </div>
</div>

<script>
// File handling
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('excel_file');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');

// Click to upload
dropZone.addEventListener('click', () => {
    fileInput.click();
});

// Drag and drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-blue-500', 'bg-blue-50');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-blue-500', 'bg-blue-50');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-500', 'bg-blue-50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        handleFileSelect(fileInput);
    }
});

function handleFileSelect(input) {
    const file = input.files[0];
    if (file) {
        // Check file type
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
        if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls)$/i)) {
            alert('Mohon upload file Excel (.xlsx atau .xls)');
            clearFile();
            return;
        }

        // Check file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file maksimal 10MB');
            clearFile();
            return;
        }

        // Show preview
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        dropZone.classList.add('hidden');
        filePreview.classList.remove('hidden');
    }
}

function clearFile() {
    fileInput.value = '';
    dropZone.classList.remove('hidden');
    filePreview.classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Form submission loading
document.getElementById('importForm').addEventListener('submit', function(e) {
    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memproses...
    `;
});
</script>
@endsection