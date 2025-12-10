<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Neraca - {{ Carbon\Carbon::parse($tanggal)->format('d F Y') }}</title>
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
                font-size: 18pt;
                color: #000;
            }

            .header p {
                margin: 3px 0;
                font-size: 10pt;
            }

            .report-title {
                text-align: center;
                margin: 20px 0;
            }

            .report-title h2 {
                margin: 0;
                font-size: 16pt;
                font-weight: bold;
            }

            .report-title p {
                margin: 5px 0;
                font-size: 11pt;
                color: #666;
            }

            .balance-sheet-container {
                display: table;
                width: 100%;
                margin: 20px 0;
            }

            .balance-sheet-column {
                display: table-cell;
                width: 50%;
                vertical-align: top;
            }

            .balance-sheet-column:first-child {
                padding-right: 15px;
                border-right: 2px solid #ccc;
            }

            .balance-sheet-column:last-child {
                padding-left: 15px;
            }

            .balance-sheet-table {
                width: 100%;
                border-collapse: collapse;
                margin: 0;
            }

            .balance-sheet-table th {
                background: #f0f0f0;
                padding: 8px;
                text-align: left;
                font-weight: bold;
                border: 1px solid #ccc;
            }

            .balance-sheet-table td {
                padding: 8px;
                border: 1px solid #ccc;
            }

            .balance-sheet-table .amount {
                text-align: right;
                font-weight: bold;
            }

            .balance-sheet-table .section-header {
                background: #e8f4f8;
                font-weight: bold;
            }

            .balance-sheet-table .total-row {
                background: #f0f0f0;
                font-weight: bold;
                font-size: 12pt;
            }

            .balance-summary {
                margin: 20px 0;
                padding: 15px;
                background: #e8f5e8;
                border: 2px solid #28a745;
                border-radius: 5px;
            }

            .balance-summary h3 {
                margin: 0 0 10px 0;
                text-align: center;
                font-size: 14pt;
                color: #155724;
            }

            .balance-summary table {
                width: 100%;
                border-collapse: collapse;
            }

            .balance-summary td {
                padding: 8px;
                text-align: center;
                font-size: 12pt;
                font-weight: bold;
            }

            .balance-summary .amount {
                font-size: 14pt;
                color: #155724;
            }

            .footer {
                margin-top: 30px;
                padding-top: 15px;
                border-top: 1px solid #ccc;
                text-align: center;
                font-size: 9pt;
                color: #666;
            }
        }

        @media screen {
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                background: #f5f5f5;
            }

            .print-container {
                background: white;
                padding: 30px;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .no-print {
                margin-bottom: 20px;
                text-align: center;
            }

            .btn-print {
                background: #007bff;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
            }

            .btn-print:hover {
                background: #0056b3;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="no-print">
            <button class="btn-print" onclick="window.print()">
                🖨️ Cetak Laporan
            </button>
            <button class="btn-print" onclick="window.close()" style="margin-left: 10px; background: #6c757d;">
                ❌ Tutup
            </button>
        </div>

        <div class="header">
            @if(isset($koperasi))
                <h1>{{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}</h1>
                <p>{{ $koperasi->alamat ?? '' }}</p>
                <p>Telepon: {{ $koperasi->telepon ?? '' }} | Email: {{ $koperasi->email ?? '' }}</p>
            @else
                <h1>Koperasi Syariah</h1>
                <p>Laporan Keuangan Syariah</p>
            @endif
            <hr style="margin: 10px 0; border: none; border-top: 1px solid #ccc;">
        </div>

        <div class="report-title">
            <h2>LAPORAN NERACA</h2>
            <p>Tanggal: {{ Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
            <p style="font-size: 9pt; color: #666;">Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
        </div>

        <div class="balance-sheet-container">
            <!-- ASET -->
            <div class="balance-sheet-column">
                <h3 style="text-align: center; margin-bottom: 15px;">ASET</h3>
                <table class="balance-sheet-table">
                    <tr>
                        <th>Aset Lancar</th>
                        <th class="amount">Jumlah</th>
                    </tr>
                    <tr>
                        <td>Kas</td>
                        <td class="amount">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Piutang Anggota</td>
                        <td class="amount">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Aset Lancar</td>
                        <td class="amount">Rp {{ number_format($totalAset, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height: 20px; border: none;"></td>
                    </tr>
                    <tr class="total-row" style="background: #d4edda;">
                        <td><strong>TOTAL ASET</strong></td>
                        <td class="amount"><strong>Rp {{ number_format($totalAset, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>

            <!-- KEWAJIBAN DAN EKUITAS -->
            <div class="balance-sheet-column">
                <h3 style="text-align: center; margin-bottom: 15px;">KEWAJIBAN DAN EKUITAS</h3>
                <table class="balance-sheet-table">
                    <tr class="section-header">
                        <th>Kewajiban</th>
                        <th class="amount">Jumlah</th>
                    </tr>
                    <tr>
                        <td>Simpanan Anggota</td>
                        <td class="amount">Rp {{ number_format($kewajibanSimpanan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Kewajiban</td>
                        <td class="amount">Rp {{ number_format($kewajibanSimpanan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height: 10px; border: none;"></td>
                    </tr>
                    <tr class="section-header">
                        <th>Ekuitas</th>
                        <th class="amount">Jumlah</th>
                    </tr>
                    <tr>
                        <td>Modal Awal</td>
                        <td class="amount">Rp {{ number_format($modalAwal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>SHU Berjalan</td>
                        <td class="amount">Rp {{ number_format($shuBerjalan, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Total Ekuitas</td>
                        <td class="amount">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height: 10px; border: none;"></td>
                    </tr>
                    <tr class="total-row" style="background: #d4edda;">
                        <td><strong>TOTAL KEWAJIBAN & EKUITAS</strong></td>
                        <td class="amount"><strong>Rp {{ number_format($totalKewajibanEkuitas, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="balance-summary">
            <h3>REKAPITULASI NERACA</h3>
            <table>
                <tr>
                    <td style="width: 40%;">Total Aset</td>
                    <td style="width: 20%;">:</td>
                    <td style="width: 40%;" class="amount">Rp {{ number_format($totalAset, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Kewajiban & Ekuitas</td>
                    <td>:</td>
                    <td class="amount">Rp {{ number_format($totalKewajibanEkuitas, 0, ',', '.') }}</td>
                </tr>
                @if($totalAset != $totalKewajibanEkuitas)
                <tr style="color: #dc3545;">
                    <td>Selisih</td>
                    <td>:</td>
                    <td class="amount">Rp {{ number_format(abs($totalAset - $totalKewajibanEkuitas), 0, ',', '.') }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="footer">
            <p><strong>Catatan:</strong> Laporan ini menunjukkan posisi keuangan koperasi pada tanggal tertentu dengan prinsip keseimbangan aset = kewajiban + ekuitas.</p>
            <p style="margin-top: 10px;">Dicetak melalui Sistem Informasi Koperasi Syariah pada {{ now()->format('d F Y H:i:s') }}</p>
            <p style="margin-top: 5px; font-size: 8pt; color: #999;">Halaman 1 dari 1</p>
        </div>
    </div>

    @if(request()->has('print'))
        <script>
            window.onload = function() {
                window.print();
            };
        </script>
    @endif
</body>
</html>