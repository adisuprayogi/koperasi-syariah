<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembiayaan per Anggota - {{ $anggota->nama_lengkap }}</title>
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
                background: #f0f8ff;
                border: 1px solid #cce7ff;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
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

            .summary-value.purple {
                color: #7b1fa2;
            }

            .summary-value.green {
                color: #2e7d32;
            }

            .summary-value.red {
                color: #c62828;
            }

            .summary-value.indigo {
                color: #3949ab;
            }

            .pembiayaan-item {
                border: 1px solid #ddd;
                margin-bottom: 20px;
                border-radius: 5px;
                overflow: hidden;
            }

            .pembiayaan-header {
                background: #f5f5f5;
                padding: 10px 15px;
                border-bottom: 1px solid #ddd;
                display: flex;
                justify-content: between;
                align-items: center;
            }

            .pembiayaan-body {
                padding: 15px;
            }

            .detail-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                margin-bottom: 15px;
            }

            .detail-item {
                padding: 5px 0;
            }

            .detail-label {
                font-size: 9pt;
                color: #666;
                margin-bottom: 2px;
            }

            .detail-value {
                font-weight: bold;
                font-size: 10pt;
            }

            .angsuran-summary {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 10px;
                margin: 15px 0;
            }

            .angsuran-item {
                text-align: center;
                padding: 8px;
                background: #f9f9f9;
                border-radius: 3px;
            }

            .recent-angsuran {
                border-top: 1px solid #eee;
                padding-top: 10px;
                margin-top: 10px;
            }

            .recent-angsuran-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 5px 0;
                border-bottom: 1px solid #f0f0f0;
                font-size: 9pt;
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

            .status-badge {
                padding: 2px 8px;
                border-radius: 3px;
                font-size: 8pt;
                font-weight: bold;
            }

            .status-approved { background: #e8f5e8; color: #388e3c; }
            .status-cair { background: #e3f2fd; color: #1976d2; }
            .status-lunas { background: #f3e5f5; color: #7b1fa2; }
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
            <h2 style="margin: 10px 0; font-size: 16pt;">LAPORAN PEMBIAYAAN PER ANGGOTA</h2>
            <p style="margin: 5px 0; font-size: 12pt;">Status: {{ $status == 'all' ? 'Semua Status' : ucfirst($status) }}</p>
            <p style="margin: 5px 0; font-size: 10pt; color: #666;">Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
        </div>

        @if($anggota)
        <div class="member-info">
            <h3 style="margin: 0 0 10px 0; font-size: 12pt; color: #1976d2;">Informasi Anggota</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div>
                    <div class="detail-label">Nomor Anggota</div>
                    <div class="detail-value">{{ $anggota->no_anggota }}</div>
                </div>
                <div>
                    <div class="detail-label">Nama Lengkap</div>
                    <div class="detail-value">{{ $anggota->nama_lengkap }}</div>
                </div>
                <div>
                    <div class="detail-label">Status Keanggotaan</div>
                    <div class="detail-value">{{ ucfirst($anggota->status_keanggotaan) }}</div>
                </div>
            </div>
        </div>

        @if(isset($reportData['pengajuan']) && count($reportData['pengajuan']) > 0)
        <div class="summary-box">
            <div class="summary-item">
                <div class="summary-label">Total Pembiayaan</div>
                <div class="summary-value purple">Rp {{ number_format($reportData['total_pinjaman'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Margin</div>
                <div class="summary-value indigo">Rp {{ number_format($reportData['total_margin'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Dibayar</div>
                <div class="summary-value green">Rp {{ number_format($reportData['total_dibayar'], 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Sisa Pinjaman</div>
                <div class="summary-value red">Rp {{ number_format($reportData['total_sisa'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            @foreach($reportData['pengajuan'] as $item)
            <div class="pembiayaan-item">
                <div class="pembiayaan-header">
                    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                        <div>
                            <strong style="font-size: 11pt;">{{ $item['pengajuan']->kode_pengajuan }}</strong>
                            <span style="margin-left: 10px; color: #666; font-size: 9pt;">{{ $item['pengajuan']->jenisPembiayaan->nama_pembiayaan }}</span>
                        </div>
                        <div>
                            <span class="status-badge status-{{ $item['pengajuan']->status }}">
                                {{ strtoupper($item['pengajuan']->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="pembiayaan-body">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Jumlah Pengajuan</div>
                            <div class="detail-value">Rp {{ number_format($item['pengajuan']->jumlah_pengajuan, 0, ',', '.') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Margin</div>
                            <div class="detail-value">Rp {{ number_format($item['pengajuan']->jumlah_margin, 0, ',', '.') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Total Pembiayaan</div>
                            <div class="detail-value" style="color: #7b1fa2;">Rp {{ number_format($item['pengajuan']->jumlah_pengajuan + $item['pengajuan']->jumlah_margin, 0, ',', '.') }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tenor</div>
                            <div class="detail-value">{{ $item['pengajuan']->tenor }} Bulan</div>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item" style="grid-column: span 4;">
                            <div class="detail-label">Tanggal Cair</div>
                            <div class="detail-value">{{ $item['pengajuan']->tanggal_cair ? $item['pengajuan']->tanggal_cair->format('d/m/Y') : '-' }}</div>
                        </div>
                    </div>

                    <div class="angsuran-summary">
                        <div class="angsuran-item">
                            <div class="detail-label">Total Pembiayaan</div>
                            <div class="summary-value purple" style="font-size: 11pt;">Rp {{ number_format($item['pengajuan']->jumlah_pengajuan + $item['pengajuan']->jumlah_margin, 0, ',', '.') }}</div>
                        </div>
                        <div class="angsuran-item">
                            <div class="detail-label">Total Dibayar</div>
                            <div class="summary-value green" style="font-size: 11pt;">Rp {{ number_format($item['pengajuan']->totalDibayar(), 0, ',', '.') }}</div>
                        </div>
                        <div class="angsuran-item">
                            <div class="detail-label">Sisa Pinjaman</div>
                            <div class="summary-value red" style="font-size: 11pt;">Rp {{ number_format($item['pengajuan']->sisaTotal(), 0, ',', '.') }}</div>
                        </div>
                    </div>

                    @if($item['pengajuan']->status == 'cair')
                    <div style="margin: 10px 0; padding: 8px; background: #f8f9fa; border-radius: 3px;">
                        <div style="font-size: 9pt; color: #666;">
                            Angsuran Dibayar: <strong>{{ $item['pengajuan']->angsurans()->where('status', 'terbayar')->count() }}</strong> / {{ $item['pengajuan']->angsurans()->count() }}
                        </div>
                    </div>
                    @endif

                    @if($item['recent_angsuran']->count() > 0)
                    <div class="recent-angsuran">
                        <div style="font-weight: bold; margin-bottom: 8px; font-size: 10pt;">Angsuran Terakhir Dibayar:</div>
                        @foreach($item['recent_angsuran'] as $angsuran)
                        <div class="recent-angsuran-item">
                            <div>
                                <strong>{{ $angsuran->kode_angsuran }}</strong>
                                <span style="margin: 0 5px; color: #999;">•</span>
                                {{ $angsuran->tanggal_bayar->format('d/m/Y') }}
                            </div>
                            <div>
                                <span style="color: #666;">Pokok:</span>
                                <span style="color: #2e7d32; font-weight: bold;">Rp {{ number_format($angsuran->jumlah_pokok, 0, ',', '.') }}</span>
                                <span style="margin: 0 8px; color: #666;">Margin:</span>
                                <span style="color: #7b1fa2; font-weight: bold;">Rp {{ number_format($angsuran->jumlah_margin, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <p style="font-size: 12pt;">Anggota yang dipilih tidak memiliki riwayat pembiayaan</p>
        </div>
        @endif
        @endif

        <div class="footer">
            <p><strong>Catatan:</strong> Laporan ini menunjukkan status dan riwayat pembiayaan untuk anggota yang dipilih.</p>
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