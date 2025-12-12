<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tunggakan Angsuran</title>
</head>
<body>
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="text-lg font-bold text-red-700 mb-2">LAPORAN TUNGGAKAN ANGSURAN</h2>
        <p class="text-sm">
            Tanggal Laporan: {{ \Carbon\Carbon::parse($tanggalLaporan)->format('d F Y') }}<br>
            Status: {{ strtoupper($status) }}<br>
            Total Transaksi: {{ $reportData['summary']['total_transaksi'] }} Data
        </p>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #FEF2F2; border: 1px solid #FEE2E2;">
                <div class="text-xs text-red-600">Total Pokok</div>
                <div class="font-bold text-red-700">{{ 'Rp ' . number_format($reportData['summary']['total_pokok'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #FEF2F2; border: 1px solid #FEE2E2;">
                <div class="text-xs text-red-600">Total Margin</div>
                <div class="font-bold text-red-700">{{ 'Rp ' . number_format($reportData['summary']['total_margin'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #FEF2F2; border: 1px solid #FEE2E2;">
                <div class="text-xs text-red-600">Total Denda</div>
                <div class="font-bold text-red-700">{{ 'Rp ' . number_format($reportData['summary']['total_denda'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #FEF2F2; border: 1px solid #FEE2E2;">
                <div class="text-xs text-red-600">Total Grand</div>
                <div class="font-bold text-red-700">{{ 'Rp ' . number_format($reportData['summary']['total_grand'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <table class="w-full border-collapse">
        <thead>
            <tr style="background-color: #EF4444; color: white;">
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 5%;">No</th>
                <th class="border border-gray-300 px-2 py-1" style="width: 20%;">Nama Anggota</th>
                <th class="border border-gray-300 px-2 py-1" style="width: 15%;">Kode Angsuran</th>
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 8%;">Angsuran Ke</th>
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 10%;">Jatuh Tempo</th>
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 8%;">Hari Telat</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 12%;">Pokok</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 12%;">Margin</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 10%;">Denda</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 12%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reportData['data'] as $index => $data)
                <tr>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $data['angsuran']->anggota->nama_lengkap }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $data['angsuran']->kode_angsuran }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $data['angsuran']->angsuran_ke }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ \Carbon\Carbon::parse($data['angsuran']->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $data['hari_terlambat'] }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($data['angsuran']->jumlah_pokok, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($data['angsuran']->jumlah_margin, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($data['denda'], 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right font-bold">{{ number_format($data['total_angsuran'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="border border-gray-300 px-2 py-4 text-center">
                        Tidak ada data tunggakan
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @if(count($reportData['data']) > 0)
            <tr style="background-color: #FEF2F2; font-weight: bold;">
                <td colspan="6" class="border border-gray-300 px-2 py-1 text-center">TOTAL</td>
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
        <p>Laporan ini dicotom otomatis pada {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }}</p>
        <p>Sistem Informasi Koperasi Syariah</p>
    </div>
</div>
</div>
</body>
</html>