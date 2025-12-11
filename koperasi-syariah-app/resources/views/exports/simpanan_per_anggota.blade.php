<table>
    <!-- Header Section -->
    <tr>
        <td colspan="4" style="text-align: center; background-color: #22C55E; color: white; padding: 20px; font-weight: bold; font-size: 16px;">
            LAPORAN SIMPANAN ANGGOTA
        </td>
    </tr>

    <!-- Sub Header -->
    <tr>
        <td colspan="4" style="padding: 15px 5px; background-color: #F3F4F6;">
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
                    <td><strong>Periode:</strong></td>
                    <td>
                        @if($startDate && $endDate)
                            {{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}
                        @elseif($startDate)
                            Dari {{ date('d F Y', strtotime($startDate)) }} sampai Sekarang
                        @elseif($endDate)
                            Sampai {{ date('d F Y', strtotime($endDate)) }}
                        @else
                            Semua Periode
                        @endif
                    </td>
                </tr>
                @if($jenisSimpananId)
                <tr>
                    <td><strong>Jenis Simpanan:</strong></td>
                    <td>{{ \App\Models\JenisSimpanan::find($jenisSimpananId)->nama ?? 'Semua Jenis' }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Tanggal Cetak:</strong></td>
                    <td>{{ date('d F Y H:i:s') }}</td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Empty Row -->
    <tr>
        <td colspan="4" style="height: 20px;"></td>
    </tr>

    <!-- Table Header -->
    <tr>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Jenis Simpanan</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Total Setoran</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Total Penarikan</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Saldo</td>
    </tr>

    @php
        $totalSimpanan = 0;
        $totalPenarikan = 0;
        $totalSaldo = 0;
    @endphp

    <!-- Data Rows -->
    @foreach($reportData as $data)
        @php
            $totalSimpanan += $data['total_setor'];
            $totalPenarikan += $data['total_tarik'];
            $totalSaldo += $data['saldo'];
        @endphp

        <tr>
            <td style="padding: 8px; border: 1px solid #000; vertical-align: middle;">{{ $data['jenis']->nama_simpanan }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['total_setor'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['total_tarik'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['saldo'], 0, ',', '.') }}</td>
        </tr>
    @endforeach

    <!-- Summary Row -->
    <tr>
        <td style="padding: 10px; border: 1px solid #000; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">TOTAL</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($totalSimpanan, 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($totalPenarikan, 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($totalSaldo, 0, ',', '.') }}</td>
    </tr>

    <!-- Footer -->
    <tr>
        <td colspan="4" style="padding: 20px 5px; vertical-align: top;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        <p style="margin: 0;"><strong>Keterangan:</strong></p>
                        <ul style="margin: 5px 0; padding-left: 20px; font-size: 12px;">
                            <li>Laporan ini menampilkan rekapitulasi simpanan anggota per jenis</li>
                            <li>Saldo = Total Setoran - Total Penarikan</li>
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