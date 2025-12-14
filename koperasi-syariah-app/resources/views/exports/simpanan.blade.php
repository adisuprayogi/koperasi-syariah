<table>
    <!-- Header -->
    <tr>
        <td colspan="10" style="text-align: center; font-size: 16px; font-weight: bold;">
            LAPORAN TRANSAKSI SIMPANAN
        </td>
    </tr>

    <!-- Period Information -->
    <tr>
        <td colspan="10" style="text-align: center;">
            @if($startDate && $endDate)
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            @elseif($startDate)
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - Sekarang
            @elseif($endDate)
                Periode: Awal - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            @else
                Semua Periode
            @endif

            @if($jenisSimpanan)
                <br>Jenis Simpanan: {{ $jenisSimpanan->nama_simpanan }}
            @endif
        </td>
    </tr>

    <!-- Space -->
    <tr>
        <td colspan="10">&nbsp;</td>
    </tr>

    <!-- Column Headers -->
    <tr>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">No</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">Tanggal</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">Kode Transaksi</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">Nama Anggota</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">No. Anggota</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">Jenis Simpanan</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">Debit (Rp)</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">Kredit (Rp)</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">Keterangan</th>
        <th style="text-align: center; border: 1px solid #000; padding: 8px;">Petugas</th>
    </tr>

    <!-- Data Rows -->
    @php($no = 1)
    @foreach($transaksi as $item)
    <tr>
        <td style="text-align: center; border: 1px solid #000; padding: 8px;">{{ $no++ }}</td>
        <td style="text-align: center; border: 1px solid #000; padding: 8px;">{{ $item->tanggal_transaksi->format('d/m/Y') }}</td>
        <td style="text-align: center; border: 1px solid #000; padding: 8px;">{{ $item->kode_transaksi }}</td>
        <td style="border: 1px solid #000; padding: 8px;">{{ $item->anggota->nama_lengkap }}</td>
        <td style="text-align: center; border: 1px solid #000; padding: 8px;">{{ $item->anggota->no_anggota }}</td>
        <td style="border: 1px solid #000; padding: 8px;">{{ $item->jenisSimpanan->nama_simpanan }}</td>
        <td style="text-align: right; border: 1px solid #000; padding: 8px;">
            @if($item->jenis_transaksi == 'setor')
                {{ number_format($item->jumlah, 0, ',', '.') }}
            @else
                -
            @endif
        </td>
        <td style="text-align: right; border: 1px solid #000; padding: 8px;">
            @if($item->jenis_transaksi == 'tarik')
                {{ number_format($item->jumlah, 0, ',', '.') }}
            @else
                -
            @endif
        </td>
        <td style="border: 1px solid #000; padding: 8px;">{{ $item->keterangan ?? '-' }}</td>
        <td style="border: 1px solid #000; padding: 8px;">{{ $item->pengurus->nama_lengkap ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Total Row -->
    <tr class="total">
        <td colspan="6" style="text-align: right; border: 1px solid #000; padding: 8px; font-weight: bold;">
            TOTAL:
        </td>
        <td style="text-align: right; border: 1px solid #000; padding: 8px; font-weight: bold;">
            {{ number_format($transaksi->where('jenis_transaksi', 'setor')->sum('jumlah'), 0, ',', '.') }}
        </td>
        <td style="text-align: right; border: 1px solid #000; padding: 8px; font-weight: bold;">
            {{ number_format($transaksi->where('jenis_transaksi', 'tarik')->sum('jumlah'), 0, ',', '.') }}
        </td>
        <td colspan="2" style="border: 1px solid #000; padding: 8px;"></td>
    </tr>

    <!-- Summary Section -->
    <tr>
        <td colspan="10">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="3" style="font-weight: bold; padding: 8px;">RINGKASAN JENIS SIMPANAN:</td>
        <td colspan="7"></td>
    </tr>

    <tr>
        <td colspan="3" style="padding: 8px;">Simpanan Wajib:</td>
        <td colspan="7" style="text-align: right; padding: 8px;">{{ number_format($totalSimpananWajib, 0, ',', '.') }}</td>
    </tr>

    <tr>
        <td colspan="3" style="padding: 8px;">Simpanan Sukarela:</td>
        <td colspan="7" style="text-align: right; padding: 8px;">{{ number_format($totalSimpananSukarela, 0, ',', '.') }}</td>
    </tr>

    <tr>
        <td colspan="3" style="padding: 8px;">Simpanan Wajib Bulanan:</td>
        <td colspan="7" style="text-align: right; padding: 8px;">{{ number_format($totalSimpananWajibBulanan, 0, ',', '.') }}</td>
    </tr>

    <tr>
        <td colspan="3" style="font-weight: bold; padding: 8px; border-top: 1px solid #000;">TOTAL SELURUH SIMPANAN:</td>
        <td colspan="7" style="text-align: right; font-weight: bold; padding: 8px; border-top: 1px solid #000;">
            {{ number_format($totalSimpananWajib + $totalSimpananSukarela + $totalSimpananWajibBulanan, 0, ',', '.') }}
        </td>
    </tr>

    <!-- Footer Space -->
    <tr>
        <td colspan="10">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="5" style="padding: 8px;">
            Mengetahui,<br><br><br><br>
            <strong>_________________________</strong><br>
            Pengurus
        </td>
        <td colspan="5" style="text-align: center; padding: 8px;">
            {{ \Carbon\Carbon::now()->format('d F Y') }}<br><br><br><br>
            <strong>_________________________</strong><br>
            Petugas
        </td>
    </tr>
</table>