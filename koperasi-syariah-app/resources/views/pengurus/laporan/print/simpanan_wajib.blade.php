<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Simpanan Wajib - {{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }} {{ $tahun }}</title>
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
                grid-template-columns: 1fr 1fr 1fr 1fr;
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

            .summary-value.blue {
                color: #1976d2;
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

            .text-right {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .footer {
                margin-top: 30px;
                padding-top: 15px;
                border-top: 1px solid #ccc;
                text-align: center;
                font-size: 9pt;
                color: #666;
            }

            .nama-bulan {
                text-transform: capitalize;
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
            <h2 style="margin: 10px 0; font-size: 16pt;">LAPORAN SIMPANAN WAJIB</h2>
            <p style="margin: 5px 0; font-size: 12pt;">Periode: <span class="nama-bulan">{{ \Carbon\Carbon::createFromFormat('m', $bulan)->locale('id')->format('F') }}</span> {{ $tahun }}</p>
            <p style="margin: 5px 0; font-size: 10pt; color: #666;">Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
        </div>

        <div class="summary-box">
            <div class="summary-item">
                <div class="summary-label">Total Anggota</div>
                <div class="summary-value blue">{{ count($reportData) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Wajib</div>
                <div class="summary-value">Rp {{ number_format($totalTerhutang, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Terbayar</div>
                <div class="summary-value green">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Tunggakan</div>
                <div class="summary-value red">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="summary-box" style="margin-bottom: 30px;">
            <div class="summary-item" style="grid-column: span 4;">
                <div class="summary-label">Persentase Pembayaran</div>
                <div class="summary-value" style="font-size: 18pt;">
                    {{ number_format($persentasePembayaran, 1) }}%
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Nomor Anggota</th>
                    <th style="width: 25%;">Nama Lengkap</th>
                    <th style="width: 15%;">Simpanan Wajib</th>
                    <th style="width: 15%;">Terbayar</th>
                    <th style="width: 15%;">Tunggakan</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $anggotaLunas = 0;
                    $anggotaBelumLunas = 0;
                @endphp

                @foreach($reportData as $data)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $data['anggota']->no_anggota }}</td>
                        <td>{{ $data['anggota']->nama_lengkap }}</td>
                        <td class="text-right">Rp {{ number_format($data['terhutang'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($data['terbayar'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($data['tunggakan'], 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($data['tunggakan'] <= 0)
                                <span style="color: #2e7d32; font-weight: bold;">✓ LUNAS</span>
                                @php($anggotaLunas++)@
                            @else
                                <span style="color: #c62828; font-weight: bold;">BELUM</span>
                                @php($anggotaBelumLunas++)@
                            @endif
                        </td>
                    </tr>

                    @if(isset($data['transaksi']) && $data['transaksi'] !== null)
                        <tr>
                            <td colspan="7" style="background: #f9f9f9; padding: 5px;">
                                <small style="font-size: 9pt; color: #666;">
                                    <strong>Riwayat Pembayaran:</strong>
                                    {{ $data['transaksi']->tanggal_transaksi->format('d/m/Y H:i') }}: Rp {{ number_format($data['transaksi']->jumlah, 0, ',', '.') }}
                                    @if($data['transaksi']->keterangan)
                                        - {{ $data['transaksi']->keterangan }}
                                    @endif
                                </small>
                            </td>
                        </tr>
                    @endif
                @endforeach

                @if(count($reportData) == 0)
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 20px; color: #666;">
                            Tidak ada data simpanan wajib untuk periode ini
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if(count($reportData) > 0)
        <table style="margin-top: 10px; border: 2px solid #000;">
            <thead>
                <tr style="background: #e3f2fd;">
                    <th colspan="2" class="text-center" style="font-size: 12pt;">REKAPITULASI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 50%; font-weight: bold;">Total Anggota:</td>
                    <td class="text-right">{{ count($reportData) }} orang</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Anggota Lunas:</td>
                    <td class="text-right" style="color: #2e7d32;">{{ $anggotaLunas }} orang ({{ number_format(($anggotaLunas / count($reportData)) * 100, 1) }}%)</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Anggota Belum Lunas:</td>
                    <td class="text-right" style="color: #c62828;">{{ $anggotaBelumLunas }} orang ({{ number_format(($anggotaBelumLunas / count($reportData)) * 100, 1) }}%)</td>
                </tr>
                <tr style="border-top: 2px solid #000; font-weight: bold;">
                    <td>Total Simpanan Wajib:</td>
                    <td class="text-right">Rp {{ number_format($totalTerhutang, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold;">
                    <td>Total Terbayar:</td>
                    <td class="text-right" style="color: #2e7d32;">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</td>
                </tr>
                <tr style="font-weight: bold;">
                    <td>Total Tunggakan:</td>
                    <td class="text-right" style="color: #c62828;">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</td>
                </tr>
                <tr style="background: #e3f2fd; font-weight: bold; font-size: 12pt;">
                    <td>Persentase Pembayaran:</td>
                    <td class="text-right">{{ number_format($persentasePembayaran, 1) }}%</td>
                </tr>
            </tbody>
        </table>
        @endif

        <div class="footer">
            <p><strong>Catatan:</strong> Laporan ini menunjukkan status pembayaran simpanan wajib bulanan untuk periode yang dipilih.</p>
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