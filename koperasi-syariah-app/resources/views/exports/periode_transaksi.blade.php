<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
</head>
<body>
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="text-lg font-bold text-blue-700 mb-2">LAPORAN TRANSAKSI {{ strtoupper($tipePeriode) }}</h2>
        <p class="text-sm">
            @if($tipePeriode == 'harian')
                Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}
            @elseif($tipePeriode == 'mingguan')
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
            @elseif($tipePeriode == 'bulanan')
                Bulan: {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y') }}
            @endif
            <br>Total Transaksi: {{ $reportData['transaksi']->count() }} Data
        </p>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #EFF6FF; border: 1px solid #DBEAFE;">
                <div class="text-xs text-blue-600">Total Penyetoran</div>
                <div class="font-bold text-blue-700">{{ 'Rp ' . number_format($reportData['total_setor'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #EFF6FF; border: 1px solid #DBEAFE;">
                <div class="text-xs text-blue-600">Total Penarikan</div>
                <div class="font-bold text-blue-700">{{ 'Rp ' . number_format($reportData['total_tarik'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #EFF6FF; border: 1px solid #DBEAFE;">
                <div class="text-xs text-blue-600">Net Transaksi</div>
                <div class="font-bold text-blue-700">{{ 'Rp ' . number_format($reportData['net_transaksi'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="text-center p-2" style="background-color: #EFF6FF; border: 1px solid #DBEAFE;">
                <div class="text-xs text-blue-600">Jenis Simpanan</div>
                <div class="font-bold text-blue-700">{{ count($reportData['summary_by_jenis']) }} Jenis</div>
            </div>
        </div>
    </div>

    <!-- Summary by Jenis Simpanan -->
    <div class="mb-4">
        <h4 class="text-sm font-semibold mb-2">Rekap per Jenis Simpanan:</h4>
        <table class="w-full border-collapse mb-4">
            <thead>
                <tr style="background-color: #3B82F6; color: white;">
                    <th class="border border-gray-300 px-2 py-1 text-left">Jenis Simpanan</th>
                    <th class="border border-gray-300 px-2 py-1 text-right">Total Setor</th>
                    <th class="border border-gray-300 px-2 py-1 text-right">Total Tarik</th>
                    <th class="border border-gray-300 px-2 py-1 text-center">Jumlah Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData['summary_by_jenis'] as $summary)
                <tr>
                    <td class="border border-gray-300 px-2 py-1">{{ $summary['jenis']->nama_simpanan }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($summary['total_setor'], 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($summary['total_tarik'], 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $summary['jumlah_transaksi'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Main Transaction Table -->
    <h4 class="text-sm font-semibold mb-2">Detail Transaksi:</h4>
    <table class="w-full border-collapse">
        <thead>
            <tr style="background-color: #3B82F6; color: white;">
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 5%;">No</th>
                <th class="border border-gray-300 px-2 py-1 text-left" style="width: 10%;">Tanggal</th>
                <th class="border border-gray-300 px-2 py-1 text-left" style="width: 15%;">Kode Transaksi</th>
                <th class="border border-gray-300 px-2 py-1 text-left" style="width: 20%;">Nama Anggota</th>
                <th class="border border-gray-300 px-2 py-1 text-left" style="width: 12%;">Jenis Simpanan</th>
                <th class="border border-gray-300 px-2 py-1 text-center" style="width: 8%;">Jenis</th>
                <th class="border border-gray-300 px-2 py-1 text-right" style="width: 15%;">Jumlah</th>
                <th class="border border-gray-300 px-2 py-1 text-left" style="width: 15%;">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['transaksi'] as $index => $transaksi)
                <tr>
                    <td class="border border-gray-300 px-2 py-1 text-center">{{ $index + 1 }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y') }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $transaksi->kode_transaksi }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $transaksi->anggota->nama_lengkap }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $transaksi->jenisSimpanan->nama_simpanan }}</td>
                    <td class="border border-gray-300 px-2 py-1 text-center">
                        @if($transaksi->jenis_transaksi == 'setor')
                            <span style="color: #059669; font-weight: bold;">SETOR</span>
                        @else
                            <span style="color: #DC2626; font-weight: bold;">TARIK</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-2 py-1 text-right font-bold">{{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                    <td class="border border-gray-300 px-2 py-1">{{ $transaksi->pengurus->nama_lengkap ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="border border-gray-300 px-2 py-4 text-center">
                        Tidak ada data transaksi
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @if(count($reportData['transaksi']) > 0)
            <tr style="background-color: #EFF6FF; font-weight: bold;">
                <td colspan="6" class="border border-gray-300 px-2 py-1 text-center">TOTAL</td>
                <td class="border border-gray-300 px-2 py-1 text-right">{{ number_format($reportData['total_setor'] + $reportData['total_tarik'], 0, ',', '.') }}</td>
                <td class="border border-gray-300 px-2 py-1"></td>
            </tr>
            <tr style="font-weight: bold;">
                <td colspan="5" class="border border-gray-300 px-2 py-1 text-right">NET TRANSAKSI</td>
                <td colspan="2" class="border border-gray-300 px-2 py-1 text-right">
                    {{ number_format($reportData['net_transaksi'], 0, ',', '.') }}
                    @if($reportData['net_transaksi'] >= 0)
                        <span style="color: #059669;">(Saldo Masuk)</span>
                    @else
                        <span style="color: #DC2626;">(Saldo Keluar)</span>
                    @endif
                </td>
                <td class="border border-gray-300 px-2 py-1"></td>
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