<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Neraca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .table-container {
            width: 100%;
            max-width: 500px;
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
                <td colspan="3" class="font-bold" style="font-size: 14px;">
                    LAPORAN NERACA
                </td>
            </tr>
            <tr class="bg-green text-center">
                <td colspan="3">
                    <strong>{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</strong>
                </td>
            </tr>

            <!-- Aset Section -->
            <tr class="bg-green">
                <td colspan="2" class="font-bold">ASET</td>
                <td class="font-bold text-center">JUMLAH</td>
            </tr>

            <tr>
                <td></td>
                <td>Kas</td>
                <td class="currency">{{ number_format($totalSimpanan, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td></td>
                <td>Piutang Anggota</td>
                <td class="currency">{{ number_format($totalPiutang, 0, ',', '.') }}</td>
            </tr>

            <tr class="bg-yellow">
                <td></td>
                <td class="font-bold">Total Aset</td>
                <td class="font-bold currency">{{ number_format($totalAset, 0, ',', '.') }}</td>
            </tr>

            <!-- Kewajiban Section -->
            <tr class="bg-red">
                <td colspan="2" class="font-bold">KEWAJIBAN</td>
                <td class="font-bold text-center">JUMLAH</td>
            </tr>

            <tr>
                <td></td>
                <td>Simpanan Anggota</td>
                <td class="currency">{{ number_format($kewajibanSimpanan, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td></td>
                <td>Kewajiban Lainnya</td>
                <td class="currency">{{ number_format($kewajibanLainnya, 0, ',', '.') }}</td>
            </tr>

            <tr class="bg-yellow">
                <td></td>
                <td class="font-bold">Total Kewajiban</td>
                <td class="font-bold currency">{{ number_format($totalKewajiban, 0, ',', '.') }}</td>
            </tr>

            <!-- Ekuitas Section -->
            <tr class="bg-purple">
                <td colspan="2" class="font-bold">EKUITAS</td>
                <td class="font-bold text-center">JUMLAH</td>
            </tr>

            <tr>
                <td></td>
                <td>Modal Awal</td>
                <td class="currency">{{ number_format($modalAwal, 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td></td>
                <td>SHU Berjalan</td>
                <td class="currency">{{ number_format($shuBerjalan, 0, ',', '.') }}</td>
            </tr>

            <tr class="bg-yellow">
                <td></td>
                <td class="font-bold">Total Ekuitas</td>
                <td class="font-bold currency">{{ number_format($totalEkuitas, 0, ',', '.') }}</td>
            </tr>

            <!-- Total Kewajiban + Ekuitas -->
            <tr class="bg-green">
                <td></td>
                <td class="font-bold">Total Kewajiban + Ekuitas</td>
                <td class="font-bold currency">{{ number_format($totalKewajibanEkuitas, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>