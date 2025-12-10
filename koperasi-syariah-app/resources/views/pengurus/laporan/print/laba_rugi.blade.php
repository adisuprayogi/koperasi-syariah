<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi - {{ Carbon\Carbon::createFromFormat('m', $bulan)->locale('id')->monthName }} {{ $tahun }}</title>
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

            .profit-loss-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }

            .profit-loss-table th {
                background: #f0f0f0;
                padding: 8px;
                text-align: left;
                font-weight: bold;
                border: 1px solid #ccc;
            }

            .profit-loss-table td {
                padding: 8px;
                border: 1px solid #ccc;
            }

            .profit-loss-table .amount {
                text-align: right;
                font-weight: bold;
            }

            .profit-loss-table .section-header {
                background: #e8f4f8;
                font-weight: bold;
            }

            .profit-loss-table .total-row {
                background: #f0f0f0;
                font-weight: bold;
            }

            .profit-loss-table .profit-row {
                background: #e8f5e8;
            }

            .profit-loss-table .loss-row {
                background: #ffe8e8;
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
            <h2>LAPORAN LABA RUGI</h2>
            <p>Periode: {{ Carbon\Carbon::createFromFormat('m', $bulan)->locale('id')->monthName }} {{ $tahun }}</p>
            <p style="font-size: 9pt; color: #666;">Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
        </div>

        <table class="profit-loss-table">
            <!-- PENDAPATAN -->
            <tr>
                <td colspan="2" class="section-header">PENDAPATAN</td>
            </tr>
            <tr>
                <td>Margin Pembiayaan</td>
                <td class="amount">Rp {{ number_format($marginReceived, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pendapatan Lainnya</td>
                <td class="amount">Rp {{ number_format($otherIncome, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row profit-row">
                <td>Total Pendapatan</td>
                <td class="amount">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>

            <!-- BEBAN -->
            <tr>
                <td colspan="2" class="section-header" style="margin-top: 20px;">BEBAN</td>
            </tr>
            <tr>
                <td>Beban Operasional</td>
                <td class="amount">Rp {{ number_format($bebanOperasional, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Beban Administrasi</td>
                <td class="amount">Rp {{ number_format($bebanAdministrasi, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row loss-row">
                <td>Total Beban</td>
                <td class="amount">Rp {{ number_format($totalBeban, 0, ',', '.') }}</td>
            </tr>

            <!-- SHU SEBELUM PAJAK -->
            <tr>
                <td colspan="2" class="section-header">SHU SEBELUM PAJAK</td>
            </tr>
            <tr class="total-row">
                <td>SHU Sebelum Pajak</td>
                <td class="amount">Rp {{ number_format($shuSebelumPajak, 0, ',', '.') }}</td>
            </tr>

            <!-- PAJAK -->
            <tr>
                <td>Pajak (5%)</td>
                <td class="amount">Rp {{ number_format($pajak, 0, ',', '.') }}</td>
            </tr>

            <!-- SHU SETELAH PAJAK -->
            <tr>
                <td colspan="2" class="section-header" style="background: #d4edda;">SHU SETELAH PAJAK</td>
            </tr>
            <tr class="total-row" style="background: #d4edda; font-size: 14pt;">
                <td>SHU Setelah Pajak</td>
                <td class="amount">Rp {{ number_format($shuSetelahPajak, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            <p><strong>Catatan:</strong> Laporan ini menunjukkan pendapatan, beban, dan Sisa Hasil Usaha (SHU) koperasi untuk periode yang dipilih.</p>
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