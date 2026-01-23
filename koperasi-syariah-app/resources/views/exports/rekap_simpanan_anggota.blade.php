<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #4CAF50; color: white;">
            <th style="text-align: center; padding: 10px;">No</th>
            <th style="text-align: center; padding: 10px;">No Anggota</th>
            <th style="text-align: center; padding: 10px;">Nama</th>
            <th style="text-align: right; padding: 10px;">Simpanan Pokok</th>
            <th style="text-align: right; padding: 10px;">Simpanan Wajib</th>
            <th style="text-align: right; padding: 10px;">Simpanan Modal</th>
            <th style="text-align: right; padding: 10px;">Simpanan Sukarela</th>
            <th style="text-align: right; padding: 10px;">Total Simpanan</th>
            <th style="text-align: right; padding: 10px;">Tagihan Wajib</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rekapData as $index => $data)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td>{{ $data->no_anggota }}</td>
            <td>{{ $data->nama }}</td>
            <td style="text-align: right;">{{ number_format($data->simpanan_pokok, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($data->simpanan_wajib, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($data->simpanan_modal, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($data->simpanan_sukarela, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($data->total_simpanan, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($data->tagihan_wajib, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <td colspan="3" style="text-align: center;">TOTAL</td>
            <td style="text-align: right;">{{ number_format($totals['pokok'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totals['wajib'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totals['modal'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totals['sukarela'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totals['all'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totals['tagihan'], 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
