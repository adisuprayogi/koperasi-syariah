<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Transaksi Simpanan - {{ $transaksi->kode_transaksi }}</title>
    <style>
        @media print {
            @page {
                margin: 20mm;
                size: A4;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 12pt;
                line-height: 1.4;
                margin: 0;
                padding: 0;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .header {
                text-align: center;
                border-bottom: 2px solid #000;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }

            .header h1 {
                margin: 0;
                font-size: 24pt;
                color: #000;
            }

            .header p {
                margin: 5px 0;
                font-size: 11pt;
            }

            .transaction-info {
                background: #f5f5f5;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            .grid-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-bottom: 20px;
            }

            .info-section {
                margin-bottom: 20px;
            }

            .info-section h3 {
                background: #e0e0e0;
                padding: 8px 12px;
                margin: 0 0 10px 0;
                font-size: 14pt;
                border-radius: 3px;
            }

            .info-row {
                display: flex;
                justify-content: space-between;
                padding: 5px 0;
                border-bottom: 1px dotted #ccc;
            }

            .info-row:last-child {
                border-bottom: none;
            }

            .info-label {
                font-weight: bold;
            }

            .amount {
                font-weight: bold;
                font-size: 16pt;
            }

            .amount-positive {
                color: #2e7d32;
            }

            .amount-negative {
                color: #c62828;
            }

            .status-badge {
                display: inline-block;
                padding: 4px 12px;
                border-radius: 20px;
                font-weight: bold;
                font-size: 11pt;
            }

            .status-verified {
                background: #e8f5e9;
                color: #2e7d32;
            }

            .status-pending {
                background: #fff3e0;
                color: #f57c00;
            }

            .status-rejected {
                background: #ffebee;
                color: #c62828;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            table th,
            table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            table th {
                background: #f5f5f5;
                font-weight: bold;
            }

            .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #ccc;
                text-align: center;
                font-size: 10pt;
                color: #666;
            }

            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 120pt;
                color: #f0f0f0;
                z-index: -1;
                font-weight: bold;
            }
        }

        @media screen {
            .print-only {
                display: none;
            }

            body {
                background: #f0f0f0;
                padding: 20px;
            }

            .preview-container {
                background: white;
                max-width: 210mm;
                margin: 0 auto;
                padding: 20mm;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>
    @if(request()->has('preview'))
        <div class="no-print" style="text-align: center; margin-bottom: 20px;">
            <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded">
                <i class="fas fa-print"></i> Cetak
            </button>
            <button onclick="window.close()" class="px-6 py-2 bg-gray-600 text-white rounded ml-2">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    @endif

    <div class="preview-container">
        <div class="watermark">KOPERASI SYARIAH</div>

        <!-- Header -->
        <div class="header">
            <h1>KOPERASI SYARIAH BERSAMA</h1>
            <p>Jl. Contoh No. 123, Jakarta - Indonesia</p>
            <p>Telp: (021) 1234-5678 | Email: info@koperasi-bersama.co.id</p>
            <p>Website: www.koperasi-bersama.co.id</p>
        </div>

        <!-- Judul -->
        <h2 style="text-align: center; margin-bottom: 30px;">
            BUKTI TRANSAKSI SIMPANAN
        </h2>

        <!-- Info Transaksi -->
        <div class="transaction-info">
            <div class="info-row">
                <span class="info-label">Kode Transaksi:</span>
                <span><strong>{{ $transaksi->kode_transaksi }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Transaksi:</span>
                <span>{{ $transaksi->tanggal_transaksi->format('d F Y H:i') }} WIB</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Transaksi:</span>
                <span>
                    <span class="status-badge {{ $transaksi->jenis_transaksi == 'setor' ? 'amount-positive' : 'amount-negative' }}">
                        {{ $transaksi->jenis_transaksi_label }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Simpanan:</span>
                <span>{{ $transaksi->jenisSimpanan->nama_simpanan }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span>
                    <span class="status-badge status-{{ $transaksi->status }}">
                        {{ $transaksi->status_label }}
                    </span>
                </span>
            </div>
        </div>

        <div class="grid-2">
            <!-- Info Anggota -->
            <div class="info-section">
                <h3>INFORMASI ANGGOTA</h3>
                <div class="info-row">
                    <span class="info-label">Nama Lengkap:</span>
                    <span>{{ $transaksi->anggota->nama_lengkap }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Anggota:</span>
                    <span>{{ $transaksi->anggota->no_anggota }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIK:</span>
                    <span>{{ $transaksi->anggota->nik }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. HP:</span>
                    <span>{{ $transaksi->anggota->no_hp }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span>{{ $transaksi->anggota->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat:</span>
                    <span>{{ $transaksi->anggota->alamat_lengkap }}</span>
                </div>
            </div>

            <!-- Info Saldo -->
            <div class="info-section">
                <h3>DETAIL TRANSAKSI</h3>
                <div class="info-row">
                    <span class="info-label">Jumlah:</span>
                    <span class="amount {{ $transaksi->jenis_transaksi == 'setor' ? 'amount-positive' : 'amount-negative' }}">
                        {{ $transaksi->jenis_transaksi == 'setor' ? '+' : '-' }} Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Saldo Sebelumnya:</span>
                    <span>Rp {{ number_format($transaksi->saldo_sebelumnya, 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Saldo Setelahnya:</span>
                    <span class="amount">Rp {{ number_format($transaksi->saldo_setelahnya, 0, ',', '.') }}</span>
                </div>

                @if($transaksi->keterangan)
                    <div style="margin-top: 15px;">
                        <div class="info-label">Keterangan:</div>
                        <p style="margin: 5px 0 0 0;">{{ $transaksi->keterangan }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Verifikasi -->
        @if($transaksi->verified_at)
            <div class="info-section">
                <h3>INFORMASI VERIFIKASI</h3>
                <div class="info-row">
                    <span class="info-label">Diverifikasi oleh:</span>
                    <span>{{ $transaksi->pengurus->nama_lengkap }} ({{ $transaksi->pengurus->posisi }})</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Verifikasi:</span>
                    <span>{{ $transaksi->verified_at->format('d F Y H:i') }} WIB</span>
                </div>
                @if($transaksi->catatan_verifikasi)
                    <div class="info-row">
                        <span class="info-label">Catatan:</span>
                        <span>{{ $transaksi->catatan_verifikasi }}</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Riwayat Transaksi -->
        @if($relatedTransaksi->count() > 0)
            <div class="info-section">
                <h3>RIWAYAT TRANSAKSI TERKAIT</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($relatedTransaksi as $t)
                            <tr>
                                <td>{{ $t->kode_transaksi }}</td>
                                <td>{{ $t->tanggal_transaksi->format('d/m/Y') }}</td>
                                <td>{{ $t->jenis_transaksi_label }}</td>
                                <td class="{{ $t->jenis_transaksi == 'setor' ? 'amount-positive' : 'amount-negative' }}">
                                    {{ $t->jenis_transaksi == 'setor' ? '+' : '-' }} {{ number_format($t->jumlah, 0, ',', '.') }}
                                </td>
                                <td>{{ number_format($t->saldo_setelahnya, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Tanda Tangan -->
        <div class="grid-2" style="margin-top: 50px;">
            <div>
                <p style="margin-bottom: 50px;">Anggota,</p>
                <p style="border-top: 1px solid #000; padding-top: 5px; width: 200px;">
                    ({{ $transaksi->anggota->nama_lengkap }})
                </p>
            </div>
            <div>
                <p style="margin-bottom: 50px;">Pengurus,</p>
                <p style="border-top: 1px solid #000; padding-top: 5px; width: 200px;">
                    ({{ $transaksi->pengurus->nama_lengkap }})
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis dari sistem Koperasi Syariah Bersama</p>
            <p>Tanggal Cetak: {{ now()->format('d F Y H:i') }} WIB</p>
        </div>
    </div>

    @if(request()->has('preview'))
        <script>
            // Auto print when preview mode is off
            window.addEventListener('load', function() {
                if (!window.location.search.includes('preview=1')) {
                    window.print();
                }
            });
        </script>
    @endif
</body>
</html>