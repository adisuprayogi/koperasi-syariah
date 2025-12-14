<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Angsuran</title>
</head>
<body>
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="text-lg font-bold text-green-700 mb-2">LAPORAN ANGSURAN</h2>
        <p class="text-sm">
            Tanggal Laporan: {{ \Carbon\Carbon::parse($tanggalLaporan)->format('d F Y') }}<br>
            Status: {{ $status }}<br>
            @if($tanggalMulai && $tanggalSelesai)
                Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}<br>
            @endif
            Total Transaksi: {{ $reportData['summary']['total_transaksi'] }} Data
        </p>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #D1FAE5; border: 1px solid #A7F3D0;">
                <div class="text-xs text-green-600">Total Pokok</div>
                <div class="font-bold text-green-700">{{ 'Rp ' . number_format($reportData['summary']['total_pokok'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #D1FAE5; border: 1px solid #A7F3D0;">
                <div class="text-xs text-green-600">Total Margin</div>
                <div class="font-bold text-green-700">{{ 'Rp ' . number_format($reportData['summary']['total_margin'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #D1FAE5; border: 1px solid #A7F3D0;">
                <div class="text-xs text-green-600">Total Denda</div>
                <div class="font-bold text-green-700">{{ 'Rp ' . number_format($reportData['summary']['total_denda'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #D1FAE5; border: 1px solid #A7F3D0;">
                <div class="text-xs text-green-600">Total Grand</div>
                <div class="font-bold text-green-700">{{ 'Rp ' . number_format($reportData['summary']['total_grand'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Summary by Status -->
    @if(count($reportData['summary_by_status']) > 0)
    <div class="mb-4">
        <h4 class="text-sm font-semibold mb-2">Rekap per Status:</h4>
        <table class="w-full border-collapse mb-4">
            <thead>
                <tr style="background-color: #059669; color: white;">
                    <th class="border border-gray-300 px-2 py-1 text-left">Status</th>
                    <th class="border border-gray-300 px-2 py-1 text-center">Jumlah</th>
                    <th class="border border-gray-300 px-2 py-1 text-right">Total Pokok</th>
                    <th class="border border-gray-300 px-2 py-1 text-right">Total Margin</th>
                    <th class="border border-gray-300 px-2 py-1 text-right">Total Denda</th>
                    <th class="border border-gray-300 px-2 py-1 text-right">Total Grand</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['summary_by_status'] as $summary)
                <tr>
                    <td class="border border-gray-300 px-2 py-1">{{ $summary['status_label'] }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $summary['jumlah'] }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($summary['total_pokok'], 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($summary['total_margin'], 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($summary['total_denda'], 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($summary['total_grand'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Main Table -->
    <table class="w-full border-collapse">
        <thead>
            <tr style="background-color: #059669; color: white;">
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 5%;">No</th>
                <th class="border border-gray-300 px-2 py-1" style="width: 20%;">Nama Anggota</th>
                <th class="border border-gray-300 px-2 py-1" style="width: 18%;">Kode Angsuran</th>
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 8%;">Angsuran Ke</th>
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 10%;">Jatuh Tempo</th>
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 10%;">Status</th>
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 8%;">Hari Telat</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 12%;">Pokok</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 12%;">Margin</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 10%;">Denda</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 12%;">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reportData['data'] as $index => $data)
                <?php
                $totalBayar = $data->jumlah_angsuran + $data->denda;
                $statusColor = '#059669'; // Default green
                if ($data->status == 'pending') {
                    $statusColor = '#EAB308'; // Yellow
                } elseif ($data->status == 'terlambat') {
                    $statusColor = '#DC2626'; // Red
                }
                ?>
                <tr>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $data->anggota->nama_lengkap }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $data->kode_angsuran }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $data->angsuran_ke }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ \Carbon\Carbon::parse($data->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">
                        <span style="color: {{ $statusColor }}; font-weight: bold;">
                            @if($data->status == 'pending')
                                MENUNGGU
                            @elseif($data->status == 'terbayar')
                                TERBAYAR
                            @else
                                TERLAMBAT
                            @endif
                        </span>
                    </td>
                    <td class="border border-gray-300 px-2 py-1 text-center">
                        @if($data->status == 'terbayar')
                            -
                        @else
                            {{ $data->hari_terlambat ?? 0 }}
                        @endif
                    </td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($data->jumlah_pokok, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($data->jumlah_margin, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($data->denda, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right font-bold">{{ number_format($totalBayar, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="border border-gray-300 px-2 py-4 text-center">
                        Tidak ada data angsuran
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @if(count($reportData['data']) > 0)
            <tr style="background-color: #D1FAE5; font-weight: bold;">
                <td colspan="7" class="border border-gray-300 px-2 py-1 text-center">TOTAL</td>
                <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($reportData['summary']['total_pokok'], 0, ',', '.') }}</td>
                <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($reportData['summary']['total_margin'], 0, ',', '.') }}</td>
                <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($reportData['summary']['total_denda'], 0, ',', '.') }}</td>
                <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($reportData['summary']['total_grand'], 0, ',', '.') }}</td>
            </tr>
            @endif
        </tfoot>
    </table>

    <!-- Footer -->
    <div class="mt-6 text-center text-xs text-gray-600">
        <p>Laporan ini dicetak otomatis pada {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
        <p>Sistem Informasi Koperasi Syariah</p>
    </div>
</div>
</body>
</html>