<table>
    <!-- Header Section -->
    <tr>
        <td colspan="9" style="text-align: center; background-color: #22C55E; color: white; padding: 20px; font-weight: bold; font-size: 16px;">
            LAPORAN PEMBIAYAAN ANGGOTA
        </td>
    </tr>

    <!-- Sub Header -->
    <tr>
        <td colspan="9" style="padding: 15px 5px; background-color: #F3F4F6;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;"><strong>Nama Anggota:</strong></td>
                    <td style="width: 50%;">{{ $anggota->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td><strong>Nomor Anggota:</strong></td>
                    <td>{{ $anggota->nomor_anggota }}</td>
                </tr>
                <tr>
                    <td><strong>Status Filter:</strong></td>
                    <td>{{ ucfirst($status) }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Cetak:</strong></td>
                    <td>{{ date('d F Y H:i:s') }}</td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Empty Row -->
    <tr>
        <td colspan="9" style="height: 20px;"></td>
    </tr>

    <!-- Table Header -->
    <tr>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Kode</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Jenis Pembiayaan</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Tenor</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Plafond</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Margin</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Total Pembiayaan</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Terbayar</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Sisa</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Status</td>
    </tr>

    <!-- Data Rows -->
    @foreach($reportData['pengajuan'] as $data)
        <tr>
            <td style="padding: 8px; border: 1px solid #000; vertical-align: middle;">{{ $data['pengajuan']->kode_pembiayaan }}</td>
            <td style="padding: 8px; border: 1px solid #000; vertical-align: middle;">{{ $data['pengajuan']->jenisPembiayaan->nama_pembiayaan }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: center; vertical-align: middle;">{{ $data['pengajuan']->jangka_waktu }} bulan</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['pengajuan']->jumlah_pengajuan, 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['pengajuan']->jumlah_margin, 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['total_pinjaman'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['total_dibayar'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['sisa_total'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: center; vertical-align: middle;">
                <span style="background-color: {{
                    $data['pengajuan']->status == 'cair' ? '#22C55E' :
                    ($data['pengajuan']->status == 'approved' ? '#3B82F6' :
                    ($data['pengajuan']->status == 'lunas' ? '#F59E0B' : '#EF4444'))
                }}; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: bold;">
                    {{ strtoupper($data['pengajuan']->status) }}
                </span>
            </td>
        </tr>
    @endforeach

    <!-- Summary Row -->
    <tr>
        <td colspan="3" style="padding: 10px; border: 1px solid #000; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">TOTAL</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($reportData['total_plafond'], 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($reportData['total_margin'], 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($reportData['total_pinjaman'], 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($reportData['total_dibayar'], 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($reportData['total_sisa'], 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: center; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">-</td>
    </tr>

    <!-- Footer -->
    <tr>
        <td colspan="9" style="padding: 20px 5px; vertical-align: top;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        <p style="margin: 0;"><strong>Keterangan:</strong></p>
                        <ul style="margin: 5px 0; padding-left: 20px; font-size: 12px;">
                            <li>Laporan ini menampilkan detail pembiayaan anggota</li>
                            <li>Total Pembiayaan = Plafond + Margin</li>
                            <li>Status: Diajukan = Menunggu verifikasi, Approved = Disetujui, Cair = Sudah cair dana, Lunas = Selesai</li>
                            <li>Data per {{ strtolower(date('d F Y')) }}</li>
                        </ul>
                    </td>
                    <td style="width: 40%; text-align: center;">
                        <p style="margin: 0;">Menyetujui,</p>
                        <br><br><br>
                        <p style="margin: 0; border-bottom: 1px solid black; width: 200px; display: inline-block;">&nbsp;</p>
                        <p style="margin: 0;">Pengurus Koperasi</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>