<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .table-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .font-bold {
            font-weight: bold;
        }
        .bg-green {
            background-color: #059669 !important;
            color: white;
        }
        .bg-red {
            background-color: #DC2626 !important;
            color: white;
        }
        .bg-purple {
            background-color: #7C3AED !important;
            color: white;
        }
        .bg-yellow {
            background-color: #FCD34D !important;
            color: #78350F;
        }
        .currency {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <table>
            <!-- Header Utama -->
            <tr class="bg-green text-center">
                <td colspan="4" class="font-bold" style="font-size: 14px;">
                    LAPORAN LABA RUGI
                </td>
            </tr>
            <tr class="bg-green text-center">
                <td colspan="4">
                    <strong>{{ ucfirst($namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$bulan-1] ?? 'Tidak Diketahui') }} {{ $tahun }}</strong>
                </td>
            </tr>

            <!-- Pendapatan Section -->
            <tr class="bg-green">
                <td colspan="2" class="font-bold">PENDAPATAN</td>
                <td class="font-bold text-center">JUMLAH</td>
                <td class="font-bold text-center">%</td>
            </tr>

            <tr>
                <td></td>
                <td>Margin Pembiayaan</td>
                <td class="currency">{{ number_format($marginReceived, 0, ',', '.') }}</td>
                <td class="text-center">100.00%</td>
            </tr>

            <tr>
                <td></td>
                <td>Pendapatan Lainnya</td>
                <td class="currency">{{ number_format($otherIncome, 0, ',', '.') }}</td>
                <td class="text-center">0.00%</td>
            </tr>

            <tr class="bg-yellow">
                <td></td>
                <td class="font-bold">Total Pendapatan</td>
                <td class="font-bold currency">{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                <td class="font-bold text-center">100.00%</td>
            </tr>

            <!-- Beban Section -->
            <tr class="bg-red">
                <td colspan="2" class="font-bold">BEBAN</td>
                <td class="font-bold text-center">JUMLAH</td>
                <td class="font-bold text-center">%</td>
            </tr>

            <tr>
                <td></td>
                <td>Beban Operasional</td>
                <td class="currency">{{ number_format($bebanOperasional, 0, ',', '.') }}</td>
                <td class="text-center">0.00%</td>
            </tr>

            <tr>
                <td></td>
                <td>Beban Administrasi</td>
                <td class="currency">{{ number_format($bebanAdministrasi, 0, ',', '.') }}</td>
                <td class="text-center">0.00%</td>
            </tr>

            <tr class="bg-yellow">
                <td></td>
                <td class="font-bold">Total Beban</td>
                <td class="font-bold currency">{{ number_format($totalBeban, 0, ',', '.') }}</td>
                <td class="font-bold text-center">0.00%</td>
            </tr>

            <!-- SHU Section -->
            <tr class="bg-purple">
                <td colspan="2" class="font-bold">SHU (SISA HASIL USAHA)</td>
                <td class="font-bold text-center">JUMLAH</td>
                <td class="font-bold text-center">%</td>
            </tr>

            <tr>
                <td></td>
                <td>SHU Sebelum Pajak (5%)</td>
                <td class="currency">{{ number_format($shuSebelumPajak, 0, ',', '.') }}</td>
                <td class="text-center">100.00%</td>
            </tr>

            <tr>
                <td></td>
                <td>Pajak</td>
                <td class="currency">{{ number_format($pajak, 0, ',', '.') }}</td>
                <td class="text-center">5.00%</td>
            </tr>

            <tr class="bg-green">
                <td></td>
                <td class="font-bold">SHU Setelah Pajak</td>
                <td class="font-bold currency">{{ number_format($shuSetelahPajak, 0, ',', '.') }}</td>
                <td class="font-bold text-center">95.00%</td>
            </tr>
        </table>
    </div>
</body>
</html>