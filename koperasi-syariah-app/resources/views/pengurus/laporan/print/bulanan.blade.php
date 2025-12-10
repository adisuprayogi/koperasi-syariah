<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan - {{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }} {{ $tahun }}</title>
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

            .jenis-badge {
                padding: 2px 6px;
                border-radius: 3px;
                font-size: 8pt;
                font-weight: bold;
            }

            .badge-modal { background: #e3f2fd; color: #1976d2; }
            .badge-pokok { background: #f3e5f5; color: #7b1fa2; }
            .badge-wajib { background: #fff3e0; color: #f57c00; }
            .badge-sukarela { background: #e8f5e8; color: #388e3c; }

            .transaksi-setor { color: #2e7d32; font-weight: bold; }
            .transaksi-tarik { color: #c62828; font-weight: bold; }

            .nama-bulan {
                text-transform: capitalize;
            }

            .page-break {
                page-break-before: always;
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
            <h2 style="margin: 10px 0; font-size: 16pt;">LAPORAN TRANSAKSI BULANAN</h2>
            <p style="margin: 5px 0; font-size: 12pt;">Bulan: <span class="nama-bulan">{{ \Carbon\Carbon::createFromFormat('m', $bulan)->locale('id')->format('F') }}</span> {{ $tahun }}</p>
            <p style="margin: 5px 0; font-size: 10pt; color: #666;">Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
        </div>

        <div class="summary-box">
            <div class="summary-item">
                <div class="summary-label">Total Transaksi</div>
                <div class="summary-value blue">{{ $transaksi->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Setoran</div>
                <div class="summary-value green">Rp {{ number_format($totalSetor, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Penarikan</div>
                <div class="summary-value red">Rp {{ number_format($totalTarik, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="summary-box" style="margin-bottom: 30px;">
            <div class="summary-item" style="grid-column: span 3;">
                <div class="summary-label">Netto Transaksi</div>
                <div class="summary-value" style="font-size: 18px; {{ $netTransaksi >= 0 ? 'color: #2e7d32;' : 'color: #c62828;' }}">
                    Rp {{ number_format($netTransaksi, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Detail Transactions Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 10%;">Tanggal</th>
                    <th style="width: 15%;">Kode Transaksi</th>
                    <th style="width: 20%;">Anggota</th>
                    <th style="width: 15%;">Jenis Simpanan</th>
                    <th style="width: 8%;">Jenis</th>
                    <th style="width: 12%;">Jumlah</th>
                    <th style="width: 10%;">Saldo</th>
                    <th style="width: 5%;">Petugas</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $totalByJenis = [];
                    $transaksiByHari = [];
                @endphp

                @foreach($transaksi as $trx)
                    @php
                        $jenisKey = $trx->jenis_simpanan_id . '-' . $trx->jenis_transaksi;
                        if (!isset($totalByJenis[$jenisKey])) {
                            $totalByJenis[$jenisKey] = [
                                'jenis_nama' => $trx->jenisSimpanan->nama,
                                'jenis_transaksi' => $trx->jenis_transaksi,
                                'total' => 0
                            ];
                        }
                        $totalByJenis[$jenisKey]['total'] += $trx->jumlah;

                        $hariKey = $trx->tanggal_transaksi->format('Y-m-d');
                        if (!isset($transaksiByHari[$hariKey])) {
                            $transaksiByHari[$hariKey] = [
                                'tanggal' => $trx->tanggal_transaksi->format('d F Y'),
                                'count' => 0,
                                'total' => 0
                            ];
                        }
                        $transaksiByHari[$hariKey]['count']++;
                        $transaksiByHari[$hariKey]['total'] += $trx->jumlah;
                    @endphp

                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $trx->tanggal_transaksi->format('d/m') }}</td>
                        <td>{{ $trx->kode_transaksi }}</td>
                        <td>{{ $trx->anggota->no_anggota }} - {{ $trx->anggota->nama_lengkap }}</td>
                        <td>
                            <span class="jenis-badge badge-{{ $trx->jenisSimpanan->tipe_simpanan }}">
                                {{ $trx->jenisSimpanan->nama }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($trx->jenis_transaksi == 'setor')
                                <span class="transaksi-setor">SETOR</span>
                            @else
                                <span class="transaksi-tarik">TARIK</span>
                            @endif
                        </td>
                        <td class="text-right {{ $trx->jenis_transaksi == 'setor' ? 'transaksi-setor' : 'transaksi-tarik' }}">
                            @if($trx->jenis_transaksi == 'setor')
                                +Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                            @else
                                -Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($trx->saldo_setelahnya, 0, ',', '.') }}</td>
                        <td>{{ $trx->pengurus->nama_lengkap ?? '-' }}</td>
                    </tr>

                    @if($trx->keterangan)
                        <tr>
                            <td colspan="9" style="background: #f9f9f9; padding: 3px;">
                                <small style="font-size: 9pt; color: #666;">
                                    <strong>Keterangan:</strong> {{ $trx->keterangan }}
                                </small>
                            </td>
                        </tr>
                    @endif
                @endforeach

                @if($transaksi->count() == 0)
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 20px; color: #666;">
                            Tidak ada data transaksi untuk bulan {{ \Carbon\Carbon::createFromFormat('m', $bulan)->locale('id')->format('F') }} {{ $tahun }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if($transaksi->count() > 0)
        <!-- Recapitulation by Type -->
        <div class="page-break">
            <h3 style="text-align: center; margin: 30px 0 20px 0; font-size: 14pt;">REKAPITULASI PER JENIS SIMPANAN</h3>

            <table style="border: 2px solid #000;">
                <thead>
                    <tr style="background: #e3f2fd;">
                        <th colspan="2" class="text-center" style="font-size: 12pt;">RINGKASAN TRANSAKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($totalByJenis as $jenisKey => $data)
                        <tr>
                            <td style="width: 60%;">
                                {{ $data['jenis_nama'] }} -
                                <span class="{{ $data['jenis_transaksi'] == 'setor' ? 'transaksi-setor' : 'transaksi-tarik' }}">
                                    {{ strtoupper($data['jenis_transaksi']) }}
                                </span>
                            </td>
                            <td class="text-right {{ $data['jenis_transaksi'] == 'setor' ? 'transaksi-setor' : 'transaksi-tarik' }}" style="font-weight: bold;">
                                {{ $data['jenis_transaksi'] == 'setor' ? '+' : '-' }}Rp {{ number_format($data['total'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach

                    <tr style="border-top: 2px solid #000; font-weight: bold; background: #e3f2fd;">
                        <td style="font-size: 12pt;">NETTO SELURUH TRANSAKSI</td>
                        <td class="text-right" style="font-size: 14pt; {{ $netTransaksi >= 0 ? 'color: #2e7d32;' : 'color: #c62828;' }}">
                            Rp {{ number_format($netTransaksi, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Daily Statistics -->
            <h3 style="text-align: center; margin: 30px 0 20px 0; font-size: 14pt;">STATISTIK HARIAN</h3>

            <table style="border: 1px solid #ccc;">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th style="width: 30%;">Tanggal</th>
                        <th style="width: 20%;">Jumlah Transaksi</th>
                        <th style="width: 25%;">Total Nominal</th>
                        <th style="width: 25%;">Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksiByHari as $hariKey => $data)
                        <tr>
                            <td>{{ $data['tanggal'] }}</td>
                            <td class="text-center">{{ $data['count'] }} transaksi</td>
                            <td class="text-right">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Monthly Statistics -->
            <h3 style="text-align: center; margin: 30px 0 20px 0; font-size: 14pt;">STATISTIK BULANAN</h3>

            <table style="border: 1px solid #ccc;">
                <thead>
                    <tr style="background: #f5f5f5;">
                        <th colspan="2" class="text-center" style="font-size: 11pt;">INFORMASI STATISTIK</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 50%;">Jumlah Hari Aktif:</td>
                        <td class="text-right">{{ count($transaksiByHari) }} hari</td>
                    </tr>
                    <tr>
                        <td>Rata-rata Transaksi per Hari:</td>
                        <td class="text-right">{{ number_format(count($transaksiByHari) > 0 ? $transaksi->count() / count($transaksiByHari) : 0, 1) }} transaksi</td>
                    </tr>
                    <tr>
                        <td>Rata-rata Nominal Transaksi:</td>
                        <td class="text-right">Rp {{ number_format($transaksi->count() > 0 ? $transaksi->sum('jumlah') / $transaksi->count() : 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Transaksi Terbesar:</td>
                        <td class="text-right">Rp {{ number_format($transaksi->max('jumlah'), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Transaksi Terkecil:</td>
                        <td class="text-right">Rp {{ number_format($transaksi->min('jumlah'), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Anggota Bertransaksi:</td>
                        <td class="text-right">{{ $transaksi->pluck('anggota_id')->unique()->count() }} orang</td>
                    </tr>
                    <tr>
                        <td>Total Saldo Akhir Bulan:</td>
                        <td class="text-right" style="font-weight: bold; color: #1976d2;">
                            Rp {{ number_format($transaksi->last()->saldo_setelahnya ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        <div class="footer">
            <p><strong>Catatan:</strong> Laporan ini menunjukkan semua transaksi simpanan yang terjadi selama bulan {{ \Carbon\Carbon::createFromFormat('m', $bulan)->locale('id')->format('F') }} {{ $tahun }}.</p>
            <p style="margin-top: 10px;">Dicetak melalui Sistem Informasi Koperasi Syariah pada {{ now()->format('d F Y H:i:s') }}</p>
            <p style="margin-top: 5px; font-size: 8pt; color: #999;">Halaman 1 dari 2</p>
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