<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Simpanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .header p {
            margin: 5px 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
        }

        td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .summary-section {
            margin-top: 30px;
        }

        .summary-section h3 {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .summary-table {
            width: 50%;
            margin-left: auto;
        }

        .summary-table td {
            border: none;
            padding: 4px 8px;
        }

        .summary-table .label {
            font-weight: normal;
        }

        .summary-table .value {
            text-align: right;
            font-weight: bold;
        }

        .summary-table .total-label {
            border-top: 1px solid #000;
            font-weight: bold;
            padding-top: 8px;
        }

        .summary-table .total-value {
            border-top: 1px solid #000;
            font-weight: bold;
            padding-top: 8px;
        }

        .footer {
            margin-top: 50px;
        }

        .signature-table {
            width: 100%;
            border: none;
        }

        .signature-table td {
            border: none;
            padding: 10px;
            vertical-align: top;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 200px;
            margin-top: 50px;
        }

        .page-break {
            page-break-before: always;
        }

        .no-border {
            border: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN TRANSAKSI SIMPANAN</h1>
        <p>
            @if($startDate && $endDate)
                Periode: {{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}
            @elseif($startDate)
                Periode: {{ date('d F Y', strtotime($startDate)) }} - Sekarang
            @elseif($endDate)
                Periode: Awal - {{ date('d F Y', strtotime($endDate)) }}
            @else
                Semua Periode
            @endif

            @if($jenisSimpanan)
                <br>Jenis Simpanan: {{ $jenisSimpanan->nama_simpanan }}
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th class="text-center" width="12%">Tanggal</th>
                <th class="text-center" width="15%">Kode Transaksi</th>
                <th class="text-center" width="20%">Nama Anggota</th>
                <th class="text-center" width="12%">No. Anggota</th>
                <th class="text-center" width="15%">Jenis Simpanan</th>
                <th class="text-center" width="12%">Debit (Rp)</th>
                <th class="text-center" width="12%">Kredit (Rp)</th>
                <th class="text-center" width="15%">Keterangan</th>
                <th class="text-center" width="10%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @php($no = 1)
            @foreach($transaksi as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $item->tanggal_transaksi->format('d/m/Y') }}</td>
                <td class="text-center">{{ $item->kode_transaksi }}</td>
                <td>{{ $item->anggota->nama_lengkap }}</td>
                <td class="text-center">{{ $item->anggota->no_anggota }}</td>
                <td>{{ $item->jenisSimpanan->nama_simpanan }}</td>
                <td class="text-right">
                    @if($item->jenis_transaksi == 'debit')
                        {{ number_format($item->jumlah, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">
                    @if($item->jenis_transaksi == 'kredit')
                        {{ number_format($item->jumlah, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
            </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="6" class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>{{ number_format($transaksi->where('jenis_transaksi', 'debit')->sum('jumlah'), 0, ',', '.') }}</strong></td>
                <td class="text-right"><strong>{{ number_format($transaksi->where('jenis_transaksi', 'kredit')->sum('jumlah'), 0, ',', '.') }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="summary-section">
        <h3>RINGKASAN JENIS SIMPANAN:</h3>
        <table class="summary-table">
            <tr>
                <td class="label">Simpanan Wajib:</td>
                <td class="value">{{ number_format($totalSimpananWajib, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Simpanan Sukarela:</td>
                <td class="value">{{ number_format($totalSimpananSukarela, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Simpanan Wajib Bulanan:</td>
                <td class="value">{{ number_format($totalSimpananWajibBulanan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="total-label">TOTAL SELURUH SIMPANAN:</td>
                <td class="total-value">{{ number_format($totalSimpananWajib + $totalSimpananSukarela + $totalSimpananWajibBulanan, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <table class="signature-table">
            <tr>
                <td width="50%">
                    <div style="text-align: center;">
                        <p>Mengetahui,</p>
                        <div class="signature-line"></div>
                        <p><strong>Pengurus</strong></p>
                    </div>
                </td>
                <td width="50%">
                    <div style="text-align: center;">
                        <p>{{ date('d F Y') }}</p>
                        <div class="signature-line"></div>
                        <p><strong>Petugas</strong></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>