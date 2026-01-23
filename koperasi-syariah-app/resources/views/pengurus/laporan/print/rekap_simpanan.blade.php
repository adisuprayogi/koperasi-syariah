<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Simpanan Anggota</title>
    <style>
        @page {
            margin: 10mm 10mm 10mm 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 16pt;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12pt;
            margin: 0;
            font-weight: normal;
        }
        .header p {
            font-size: 9pt;
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        td.text-right {
            text-align: right;
        }
        td.text-center {
            text-align: center;
        }
        tfoot tr {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #000;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($koperasi)
            <h1>{{ $koperasi->nama_koperasi }}</h1>
            <p>{{ $koperasi->alamat }}</p>
            <p>Telp: {{ $koperasi->no_telepon }} | Email: {{ $koperasi->email }}</p>
        @else
            <h1>KOPERASI SYARIAH</h1>
        @endif
        <h2 style="margin-top: 10px;">LAPORAN REKAP SIMPANAN ANGGOTA</h2>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">No Anggota</th>
                <th style="width: 25%;">Nama</th>
                <th style="width: 11%;">Simpanan Pokok</th>
                <th style="width: 11%;">Simpanan Wajib</th>
                <th style="width: 11%;">Simpanan Modal</th>
                <th style="width: 11%;">Simpanan Sukarela</th>
                <th style="width: 11%;">Total Simpanan</th>
                <th style="width: 11%;">Tagihan Wajib</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekapData as $index => $data)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $data->no_anggota }}</td>
                <td>{{ $data->nama }}</td>
                <td class="text-right">{{ number_format($data->simpanan_pokok, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->simpanan_wajib, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->simpanan_modal, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->simpanan_sukarela, 0, ',', '.') }}</td>
                <td class="text-right"><strong>{{ number_format($data->total_simpanan, 0, ',', '.') }}</strong></td>
                <td class="text-right">{{ number_format($data->tagihan_wajib, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-center">TOTAL</td>
                <td class="text-right">{{ number_format($totalPokok, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalWajib, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalModal, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalSukarela, 0, ',', '.') }}</td>
                <td class="text-right"><strong>{{ number_format($totalAllSimpanan, 0, ',', '.') }}</strong></td>
                <td class="text-right">{{ number_format($totalTagihanWajib, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span>Total Anggota:</span>
            <span>{{ count($rekapData) }} orang</span>
        </div>
        <div class="summary-row">
            <span>Total Simpanan:</span>
            <span>Rp {{ number_format($totalAllSimpanan, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Tagihan Wajib:</span>
            <span>Rp {{ number_format($totalTagihanWajib, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>&copy; {{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}</p>
    </div>
</body>
</html>
