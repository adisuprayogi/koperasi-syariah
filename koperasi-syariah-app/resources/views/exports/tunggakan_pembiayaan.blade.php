<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #dc2626; color: white;">
            <th style="text-align: center; padding: 10px;">No</th>
            <th style="text-align: center; padding: 10px;">ID</th>
            <th style="text-align: center; padding: 10px;">No Anggota</th>
            <th style="text-align: center; padding: 10px;">Nama Anggota</th>
            <th style="text-align: center; padding: 10px;">Jenis Pembiayaan</th>
            <th style="text-align: right; padding: 10px;">Sisa Angsuran</th>
            <th style="text-align: center; padding: 10px;">Bulan Menunggak</th>
            <th style="text-align: right; padding: 10px;">Jumlah Menunggak</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tunggakanData as $index => $data)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td>{{ $data->id }}</td>
            <td>{{ $data->no_anggota }}</td>
            <td>{{ $data->nama_anggota }}</td>
            <td>{{ $data->jenis_pembiayaan }}</td>
            <td style="text-align: right;">{{ $data->sisa_angsuran }} periode</td>
            <td style="text-align: center;">{{ $data->bulan_menunggak }} bulan</td>
            <td style="text-align: right;">{{ number_format($data->jumlah_menunggak, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <td colspan="5" style="text-align: center;">TOTAL</td>
            <td style="text-align: right;">{{ number_format($totals['sisa_angsuran']) }} periode</td>
            <td style="text-align: center;">{{ number_format($totals['bulan_menunggak']) }} bulan</td>
            <td style="text-align: right;">{{ number_format($totals['jumlah_menunggak'], 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
