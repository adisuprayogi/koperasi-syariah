<table>
    <!-- Header Section -->
    <tr>
        <td colspan="7" style="text-align: center; background-color: #22C55E; color: white; padding: 20px; font-weight: bold; font-size: 16px;">
            REKAPITULASI SIMPANAN KOPERASI
            @if($koperasi)
                <br><span style="font-size: 14px; font-weight: normal;">{{ $koperasi->nama_koperasi }}</span>
            @endif
        </td>
    </tr>

    <!-- Sub Header -->
    <tr>
        <td colspan="7" style="padding: 15px 5px; background-color: #F3F4F6;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;"><strong>Periode:</strong></td>
                    <td style="width: 50%;">Semua Periode</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Cetak:</strong></td>
                    <td>{{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td><strong>Total Anggota Aktif:</strong></td>
                    <td>{{ $totalData['total_anggota'] }} orang</td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- Empty Row -->
    <tr>
        <td colspan="7" style="height: 20px;"></td>
    </tr>

    <!-- Table Header -->
    <tr>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Jenis Simpanan</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Jumlah Anggota</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Total Setoran</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Total Penarikan</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Saldo</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">Rata-rata/Anggota</td>
        <td style="background-color: #16A34A; color: white; padding: 10px; text-align: center; border: 1px solid #000; font-weight: bold;">% Total</td>
    </tr>

    <!-- Data Rows -->
    @foreach($reportData as $data)
        @php
            $persentase = $totalData['total_saldo'] > 0 ? ($data['saldo'] / $totalData['total_saldo']) * 100 : 0;
        @endphp

        <tr>
            <td style="padding: 8px; border: 1px solid #000; vertical-align: middle;">{{ $data['jenis']->nama_simpanan }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: center; vertical-align: middle;">{{ $data['jumlah_anggota'] }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['total_setor'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['total_tarik'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['saldo'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($data['rata_rata'], 0, ',', '.') }}</td>
            <td style="padding: 8px; border: 1px solid #000; text-align: right; vertical-align: middle;">{{ number_format($persentase, 2, ',', '.') }}%</td>
        </tr>
    @endforeach

    <!-- Summary Row -->
    <tr>
        <td style="padding: 10px; border: 1px solid #000; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">GRAND TOTAL</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: center; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">-</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($totalData['total_setor'], 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($totalData['total_tarik'], 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($totalData['total_saldo'], 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">{{ number_format($totalData['total_anggota'] > 0 ? $totalData['total_saldo'] / $totalData['total_anggota'] : 0, 0, ',', '.') }}</td>
        <td style="padding: 10px; border: 1px solid #000; text-align: right; vertical-align: middle; font-weight: bold; background-color: #F3F4F6;">100.00%</td>
    </tr>

    <!-- Footer -->
    <tr>
        <td colspan="7" style="padding: 20px 5px; vertical-align: top;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        <p style="margin: 0;"><strong>Keterangan:</strong></p>
                        <ul style="margin: 5px 0; padding-left: 20px; font-size: 12px;">
                            <li>Laporan ini menampilkan rekapitulasi simpanan per jenis</li>
                            <li>Rata-rata = Total Saldo / Jumlah Anggota yang memiliki simpanan jenis tersebut</li>
                            <li>% Total = Persentase saldo jenis simpanan terhadap total seluruh simpanan</li>
                            <li>Data per {{ strtolower(\Carbon\Carbon::now()->format('d F Y')) }}</li>
                        </ul>
                    </td>
                    <td style="width: 40%; text-align: center;">
                        <p style="margin: 0;">Menyetujui,</p>
                        <br><br><br>
                        <p style="margin: 0; border-bottom: 1px solid black; width: 200px; display: inline-block;">&nbsp;</p>
                        <p style="margin: 0;">Ketua Koperasi</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>