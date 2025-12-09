<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pembayaran Angsuran</title>
    <style>
        @page {
            size: A5;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h3 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
            font-size: 11px;
        }

        .info-box {
            border: 2px solid #333;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
            text-align: center;
            font-size: 14px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        .detail-box {
            margin-top: 20px;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .detail-table th,
        .detail-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        .detail-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .detail-table .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 40px;
            margin-top: 60px;
        }

        .stamp-area {
            position: absolute;
            right: 50px;
            top: 300px;
            width: 120px;
            height: 120px;
            border: 2px dashed #999;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #999;
            font-size: 11px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.1);
            font-weight: bold;
            pointer-events: none;
        }
    </style>
</head>
<body>
    @if($angsuran->status == 'terbayar')
    <div class="watermark">TERBAYAR</div>
    @endif

    <div class="header">
        <h3>BUKTI PEMBAYARAN ANGSURAN</h3>
        <p>Koperasi {{ auth()->user()->koperasi->nama_koperasi ?? '' }}</p>
        <p>{{ auth()->user()->koperasi->alamat ?? '' }}</p>
    </div>

    <div class="info-box">
        <div class="info-title">INFORMASI PEMBAYARAN</div>

        <div class="info-row">
            <div class="info-label">Nomor Bukti:</div>
            <div class="info-value">{{ $angsuran->kode_angsuran }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Tanggal Bayar:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($angsuran->tanggal_bayar)->format('d/m/Y') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Nama Anggota:</div>
            <div class="info-value">{{ $angsuran->pengajuanPembiayaan->anggota->nama_lengkap }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">No. Anggota:</div>
            <div class="info-value">{{ $angsuran->pengajuanPembiayaan->anggota->nomor_anggota }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Kode Pembiayaan:</div>
            <div class="info-value">{{ $angsuran->pengajuanPembiayaan->kode_pengajuan }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Jenis Pembiayaan:</div>
            <div class="info-value">{{ $angsuran->pengajuanPembiayaan->jenisPembiayaan->nama_jenis }}</div>
        </div>
    </div>

    <div class="detail-box">
        <div class="info-title">DETAIL PEMBAYARAN</div>

        <table class="detail-table">
            <tr>
                <th width="30">No</th>
                <th>Uraian</th>
                <th width="100" class="text-right">Jumlah (Rp)</th>
            </tr>
            <tr>
                <td class="text-right">1</td>
                <td>Angsuran ke-{{ $angsuran->angsuran_ke }}</td>
                <td class="text-right">{{ number_format($angsuran->jumlah_angsuran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 20px;">- Pokok</td>
                <td class="text-right">{{ number_format($angsuran->jumlah_pokok, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 20px;">- Margin</td>
                <td class="text-right">{{ number_format($angsuran->jumlah_margin, 0, ',', '.') }}</td>
            </tr>

            @if($angsuran->denda > 0)
            <tr>
                <td class="text-right">2</td>
                <td>Denda Keterlambatan ({{ $angsuran->hari_terlambat }} hari)</td>
                <td class="text-right">{{ number_format($angsuran->denda, 0, ',', '.') }}</td>
            </tr>
            @endif

            <tr class="total-row">
                <td colspan="2" class="text-right"><strong>TOTAL PEMBAYARAN</strong></td>
                <td class="text-right">
                    <strong>
                        {{ number_format($angsuran->jumlah_angsuran + $angsuran->denda, 0, ',', '.') }}
                    </strong>
                </td>
            </tr>
        </table>
    </div>

    @if($angsuran->keterangan)
    <div class="info-box" style="margin-top: 20px;">
        <div class="info-title">KETERANGAN</div>
        <p>{{ $angsuran->keterangan }}</p>
    </div>
    @endif

    <div class="footer">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p>Bendahara</p>
            <div class="signature-line"></div>
            <p>({{ auth()->user()->name }})</p>
        </div>

        <div class="signature-box">
            <p>Pemohon,</p>
            <p>Anggota</p>
            <div class="signature-line"></div>
            <p>({{ $angsuran->pengajuanPembiayaan->anggota->nama_lengkap }})</p>
        </div>
    </div>

    @if($angsuran->status == 'terbayar')
    <div class="stamp-area">
        [ STAMP<br>DIREKTUR<br>]
    </div>
    @endif

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>