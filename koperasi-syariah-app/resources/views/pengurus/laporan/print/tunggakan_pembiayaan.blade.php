<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tunggakan Pembiayaan</title>
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
            background-color: #dc2626;
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
        .warning {
            color: #dc2626;
            font-weight: bold;
        }
        .warning-row {
            background-color: #fef2f2;
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
        <h2 style="margin-top: 10px;">LAPORAN TUNGGAKAN PEMBIAYAAN</h2>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">ID</th>
                <th style="width: 20%;">Nama Anggota</th>
                <th style="width: 18%;">Jenis Pembiayaan</th>
                <th style="width: 12%;">Sisa Angsuran</th>
                <th style="width: 13%;">Bulan Menunggak</th>
                <th style="width: 20%;">Jumlah Menunggak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tunggakanData as $index => $data)
            <tr class="{{ $data->bulan_menunggak >= 3 ? 'warning-row' : '' }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $data->kode_pengajuan }}</td>
                <td>
                    {{ $data->nama_anggota }}
                    <br><small>{{ $data->no_anggota }}</small>
                </td>
                <td>{{ $data->jenis_pembiayaan }}</td>
                <td class="text-right">{{ $data->sisa_angsuran }} periode</td>
                <td class="text-center {{ $data->bulan_menunggak >= 3 ? 'warning' : '' }}">
                    {{ $data->bulan_menunggak }} bulan
                </td>
                <td class="text-right {{ $data->bulan_menunggak >= 3 ? 'warning' : '' }}">
                    Rp {{ number_format($data->jumlah_menunggak, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">TOTAL</td>
                <td class="text-right">{{ number_format($totalSisaAngsuran) }} periode</td>
                <td class="text-center">{{ number_format($totalBulanMenunggak) }} bulan</td>
                <td class="text-right">Rp {{ number_format($totalJumlahMenunggak, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span>Total Pembiayaan Menunggak:</span>
            <span>{{ count($tunggakanData) }} pembiayaan</span>
        </div>
        <div class="summary-row">
            <span>Total Bulan Menunggak:</span>
            <span>{{ number_format($totalBulanMenunggak) }} bulan</span>
        </div>
        <div class="summary-row">
            <span>Total Jumlah Menunggak:</span>
            <span class="warning">Rp {{ number_format($totalJumlahMenunggak, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        <p>&copy; {{ $koperasi->nama_koperasi ?? 'Koperasi Syariah' }}</p>
    </div>
</body>
</html>
