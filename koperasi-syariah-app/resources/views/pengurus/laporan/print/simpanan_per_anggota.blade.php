<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Simpanan - {{ $anggota->nama_lengkap }}</title>
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

            .member-info {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
                border-left: 4px solid #1976d2;
            }

            .member-info h3 {
                margin: 0 0 10px 0;
                color: #1976d2;
                font-size: 14pt;
            }

            .member-info-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                font-size: 10pt;
            }

            .member-info-item {
                margin: 2px 0;
            }

            .member-info-label {
                font-weight: bold;
                color: #666;
            }

            .summary-box {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 15px;
                margin-bottom: 30px;
            }

            .summary-item {
                border: 1px solid #ccc;
                padding: 15px;
                text-align: center;
                border-radius: 5px;
                background: #fafafa;
            }

            .summary-label {
                font-size: 9pt;
                color: #666;
                margin-bottom: 8px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .summary-value {
                font-size: 16pt;
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

            .summary-value.purple {
                color: #7b1fa2;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 30px;
            }

            table th,
            table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
                font-size: 10pt;
            }

            table th {
                background: #f5f5f5;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 9pt;
            }

            .text-right {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #ccc;
                text-align: center;
                font-size: 9pt;
                color: #666;
            }

            .jenis-badge {
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 8pt;
                font-weight: bold;
                text-transform: uppercase;
            }

            .badge-modal { background: #e3f2fd; color: #1976d2; }
            .badge-pokok { background: #f3e5f5; color: #7b1fa2; }
            .badge-wajib { background: #fff3e0; color: #f57c00; }
            .badge-sukarela { background: #e8f5e8; color: #388e3c; }

            .total-row {
                font-weight: bold;
                background: #e3f2fd !important;
                font-size: 11pt;
            }

            .saldo-row {
                background: #f5f5f5 !important;
                font-weight: bold;
            }

            .recent-transactions {
                margin-top: 20px;
            }

            .recent-transactions h4 {
                margin: 0 0 10px 0;
                font-size: 12pt;
                color: #666;
            }

            .recent-transactions table {
                font-size: 9pt;
            }

            .recent-transactions td {
                padding: 6px;
            }

            .no-transactions {
                text-align: center;
                color: #999;
                font-style: italic;
                padding: 20px;
                background: #f9f9f9;
                border-radius: 5px;
                margin-top: 10px;
            }

            .signature-box {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 50px;
                margin-top: 50px;
            }

            .signature-item {
                text-align: center;
            }

            .signature-line {
                border-bottom: 1px solid #000;
                height: 40px;
                margin-bottom: 5px;
            }

            .signature-name {
                font-size: 10pt;
                font-weight: bold;
            }

            .signature-title {
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
                padding: 12px 24px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                margin: 0 5px;
            }

            .btn-print:hover {
                background: #0056b3;
            }

            .btn-close {
                background: #6c757d;
                color: white;
                padding: 12px 24px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                margin: 0 5px;
            }

            .btn-close:hover {
                background: #5a6268;
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
            <button class="btn-close" onclick="window.close()">
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
            <h2 style="margin: 10px 0; font-size: 16pt;">LAPORAN SIMPANAN PER ANGGOTA</h2>
            <p style="margin: 5px 0; font-size: 10pt; color: #666;">Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
        </div>

        <div class="member-info">
            <h3>Informasi Anggota</h3>
            <div class="member-info-grid">
                <div>
                    <div class="member-info-item">
                        <span class="member-info-label">Nomor Anggota:</span>
                        {{ $anggota->no_anggota }}
                    </div>
                    <div class="member-info-item">
                        <span class="member-info-label">Nama Lengkap:</span>
                        {{ $anggota->nama_lengkap }}
                    </div>
                    <div class="member-info-item">
                        <span class="member-info-label">NIK:</span>
                        {{ $anggota->nik }}
                    </div>
                    <div class="member-info-item">
                        <span class="member-info-label">Jenis Kelamin:</span>
                        {{ $anggota->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </div>
                </div>
                <div>
                    <div class="member-info-item">
                        <span class="member-info-label">No. HP:</span>
                        {{ $anggota->no_hp }}
                    </div>
                    <div class="member-info-item">
                        <span class="member-info-label">Email:</span>
                        {{ $anggota->email }}
                    </div>
                    <div class="member-info-item">
                        <span class="member-info-label">Pekerjaan:</span>
                        {{ $anggota->pekerjaan }}
                    </div>
                    <div class="member-info-item">
                        <span class="member-info-label">Status:</span>
                        <span style="color: #2e7d32; font-weight: bold;">
                            {{ ucfirst($anggota->status_keanggotaan) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="summary-box">
            <div class="summary-item">
                <div class="summary-label">Total Simpanan</div>
                <div class="summary-value purple">
                    Rp {{ number_format(collect($reportData)->sum('saldo'), 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Setoran</div>
                <div class="summary-value green">
                    Rp {{ number_format(collect($reportData)->sum('total_setor'), 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Penarikan</div>
                <div class="summary-value red">
                    Rp {{ number_format(collect($reportData)->sum('total_tarik'), 0, ',', '.') }}
                </div>
            </div>
        </div>

        <h3 style="margin: 30px 0 15px 0; font-size: 14pt; color: #333;">Rincian Simpanan per Jenis</h3>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Jenis Simpanan</th>
                    <th style="width: 15%;">Total Setoran</th>
                    <th style="width: 15%;">Total Penarikan</th>
                    <th style="width: 15%;">Saldo</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 20%;">Jenis</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $totalSetor = 0;
                    $totalTarik = 0;
                    $totalSaldo = 0;
                @endphp

                @foreach($reportData as $data)
                    @php
                        $totalSetor += $data['total_setor'];
                        $totalTarik += $data['total_tarik'];
                        $totalSaldo += $data['saldo'];
                    @endphp

                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>
                            <span class="jenis-badge badge-{{ $data['jenis']->tipe_simpanan }}">
                                {{ $data['jenis']->nama }}
                            </span>
                        </td>
                        <td class="text-right" style="color: #2e7d32; font-weight: bold;">
                            Rp {{ number_format($data['total_setor'], 0, ',', '.') }}
                        </td>
                        <td class="text-right" style="color: #c62828; font-weight: bold;">
                            Rp {{ number_format($data['total_tarik'], 0, ',', '.') }}
                        </td>
                        <td class="text-right saldo-row">
                            Rp {{ number_format($data['saldo'], 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($data['saldo'] > 0)
                                <span style="color: #2e7d32; font-weight: bold;">AKTIF</span>
                            @else
                                <span style="color: #666;">NOL</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ ucfirst($data['jenis']->tipe_simpanan) }}
                        </td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="2" style="text-align: right; font-size: 11pt;">TOTAL:</td>
                    <td class="text-right" style="color: #2e7d32;">Rp {{ number_format($totalSetor, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #c62828;">Rp {{ number_format($totalTarik, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #1976d2; font-size: 12pt;">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <!-- Recent Transactions -->
        <div class="recent-transactions">
            <h4>Transaksi Terakhir (5 Transaksi per Jenis)</h4>
            @php
                $hasTransactions = collect($reportData)->filter(function($data) {
                    return $data['recent_transaksi']->count() > 0;
                })->count() > 0;
            @endphp

            @if($hasTransactions)
                @foreach($reportData as $data)
                    @if($data['recent_transaksi']->count() > 0)
                        <h5 style="margin: 15px 0 5px 0; color: #666;">
                            <span class="jenis-badge badge-{{ $data['jenis']->tipe_simpanan }}">
                                {{ $data['jenis']->nama }}
                            </span>
                        </h5>
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 15%;">Tanggal</th>
                                    <th style="width: 15%;">Kode</th>
                                    <th style="width: 10%;">Jenis</th>
                                    <th style="width: 15%;">Jumlah</th>
                                    <th style="width: 10%;">Saldo</th>
                                    <th style="width: 15%;">Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['recent_transaksi'] as $index => $trans)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $trans->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                        <td>{{ $trans->kode_transaksi }}</td>
                                        <td class="text-center">
                                            <span class="{{ $trans->jenis_transaksi == 'setor' ? 'transaksi-setor' : 'transaksi-tarik' }}">
                                                {{ strtoupper($trans->jenis_transaksi) }}
                                            </span>
                                        </td>
                                        <td class="text-right {{ $trans->jenis_transaksi == 'setor' ? 'text-success' : 'text-danger' }}">
                                            {{ $trans->jenis_transaksi == 'setor' ? '+' : '-' }}Rp {{ number_format($trans->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="text-right">Rp {{ number_format($trans->saldo_setelahnya, 0, ',', '.') }}</td>
                                        <td>{{ $trans->pengurus->nama_lengkap ?? '-' }}</td>
                                    </tr>

                                    @if($trans->keterangan)
                                        <tr>
                                            <td colspan="8" style="background: #f9f9f9; padding: 4px;">
                                                <small style="font-size: 8pt; color: #666;">
                                                    <strong>Keterangan:</strong> {{ $trans->keterangan }}
                                                </small>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach
            @else
                <div class="no-transactions">
                    Belum ada transaksi untuk anggota ini
                </div>
            @endif
        </div>

        <!-- Signature Section -->
        <div class="signature-box">
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $anggota->nama_lengkap }}</div>
                <div class="signature-title">Anggota</div>
                <div style="margin-top: 20px; font-size: 9pt; color: #666;">
                    {{ now()->format('d F Y') }}
                </div>
            </div>
            <div class="signature-item">
                <div class="signature-line"></div>
                <div class="signature-name">Mengetahui</div>
                <div class="signature-title">Pengurus Koperasi</div>
                <div style="margin-top: 20px; font-size: 9pt; color: #666;">
                    {{ now()->format('d F Y') }}
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>Catatan:</strong> Laporan ini menunjukkan rincian simpanan anggota untuk semua jenis simpanan yang tersedia.</p>
            <p style="margin-top: 10px;">Dicetak melalui Sistem Informasi Koperasi Syariah pada {{ now()->format('d F Y H:i:s') }}</p>
            <p style="margin-top: 5px; font-size: 8pt; color: #999;">Halaman 1 dari 1</p>
            <p style="margin-top: 10px; font-size: 7pt; color: #999;">
                Laporan ini adalah dokumen resmi dan sah secara hukum
            </p>
        </div>
    </div>

    <script>
        // Auto-print when URL has print parameter
        @if(request()->has('print'))
            window.onload = function() {
                window.print();
            };
        @endif

        // Close window after printing
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>