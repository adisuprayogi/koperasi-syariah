<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Harian - {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</title>
    <style>
        @media print {
            @page {
                margin: 15mm;
                size: A4;
                orientation: portrait;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 11pt;
                line-height: 1.3;
                margin: 0;
                padding: 0;
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .header {
                text-align: center;
                border-bottom: 2px solid #000;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }

            .header h1 {
                margin: 0;
                font-size: 20pt;
                color: #000;
            }

            .header p {
                margin: 3px 0;
                font-size: 10pt;
            }

            .summary-box {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 15px;
                margin-bottom: 20px;
            }

            .summary-item {
                border: 1px solid #ccc;
                padding: 10px;
                text-align: center;
                border-radius: 3px;
            }

            .summary-label {
                font-size: 9pt;
                color: #666;
                margin-bottom: 5px;
            }

            .summary-value {
                font-size: 14pt;
                font-weight: bold;
            }

            .summary-value.green {
                color: #2e7d32;
            }

            .summary-value.red {
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
                padding: 6px;
                text-align: left;
                font-size: 10pt;
            }

            table th {
                background: #f5f5f5;
                font-weight: bold;
            }

            .footer {
                margin-top: 30px;
                padding-top: 15px;
                border-top: 1px solid #ccc;
                text-align: center;
                font-size: 9pt;
                color: #666;
            }

            .text-right {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .badge-setor {
                background: #e8f5e9;
                color: #2e7d32;
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 9pt;
            }

            .badge-tarik {
                background: #ffebee;
                color: #c62828;
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 9pt;
            }
        }

        @media screen {
            .no-print {
                display: block;
            }

            body {
                background: #f0f0f0;
                padding: 20px;
            }

            .preview-container {
                background: white;
                max-width: 210mm;
                margin: 0 auto;
                padding: 15mm;
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
        <!-- Header -->
        <div class="header">
            <h1>KOPERASI SYARIAH BERSAMA</h1>
            <p>Jl. Contoh No. 123, Jakarta - Indonesia</p>
            <p>Telp: (021) 1234-5678 | Email: info@koperasi-bersama.co.id</p>
        </div>

        <!-- Title -->
        <h2 style="text-align: center; margin-bottom: 20px;">
            LAPORAN HARIAN TRANSAKSI SIMPANAN
        </h2>
        <p style="text-align: center; margin-bottom: 25px; font-size: 12pt;">
            Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
        </p>

        <!-- Summary -->
        <div class="summary-box">
            <div class="summary-item">
                <div class="summary-label">Total Setoran</div>
                <div class="summary-value green">Rp {{ number_format($totalSetor, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Penarikan</div>
                <div class="summary-value red">Rp {{ number_format($totalTarik, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Net Transaksi</div>
                <div class="summary-value {{ $netTransaksi >= 0 ? 'green' : 'red' }}">
                    Rp {{ number_format($netTransaksi, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Transaction Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">Kode</th>
                    <th style="width: 10%;">Waktu</th>
                    <th style="width: 20%;">Anggota</th>
                    <th style="width: 18%;">Jenis Simpanan</th>
                    <th style="width: 10%;">Jenis</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th style="width: 15%;">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                    <tr>
                        <td>{{ $t->kode_transaksi }}</td>
                        <td>{{ $t->tanggal_transaksi->format('H:i') }}</td>
                        <td>
                            <div>{{ $t->anggota->nama_lengkap }}</div>
                            <div style="font-size: 9pt; color: #666;">{{ $t->anggota->no_anggota }}</div>
                        </td>
                        <td>{{ $t->jenisSimpanan->nama_simpanan }}</td>
                        <td class="text-center">
                            @if($t->jenis_transaksi == 'setor')
                                <span class="badge-setor">Setor</span>
                            @else
                                <span class="badge-tarik">Tarik</span>
                            @endif
                        </td>
                        <td class="text-right {{ $t->jenis_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $t->jenis_transaksi == 'setor' ? '+' : '-' }} {{ number_format($t->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="text-right">{{ number_format($t->saldo_setelahnya, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 30px; color: #666;">
                            Tidak ada transaksi pada tanggal ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($transaksi->count() > 0)
            <tfoot>
                <tr style="font-weight: bold; background: #f9f9f9;">
                    <td colspan="5" class="text-right">TOTAL:</td>
                    <td class="text-right" style="color: #2e7d32;">
                        + {{ number_format($totalSetor, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
                <tr style="font-weight: bold; background: #f9f9f9;">
                    <td colspan="5" class="text-right"></td>
                    <td class="text-right" style="color: #c62828;">
                        - {{ number_format($totalTarik, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
                <tr style="font-weight: bold; background: #f5f5f5; border-top: 2px solid #000;">
                    <td colspan="5" class="text-right">NET TRANSAKSI:</td>
                    <td class="text-right" style="color: {{ $netTransaksi >= 0 ? '#2e7d32' : '#c62828' }}; font-size: 12pt;">
                        {{ $netTransaksi >= 0 ? '+' : '' }}{{ number_format($netTransaksi, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>

        <!-- Summary Info -->
        <div style="margin-top: 20px; display: flex; justify-content: space-between;">
            <div style="font-size: 10pt;">
                <p>Jumlah Transaksi: <strong>{{ $transaksi->count() }}</strong> transaksi</p>
            </div>
            <div style="font-size: 10pt; text-align: right;">
                <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis dari sistem Koperasi Syariah Bersama</p>
            <p>Laporan harian transaksi simpanan - Confidenstial</p>
        </div>
    </div>

    @if(request()->has('preview'))
        <script>
            window.addEventListener('load', function() {
                if (!window.location.search.includes('preview=1')) {
                    window.print();
                }
            });
        </script>
    @endif
</body>
</html>