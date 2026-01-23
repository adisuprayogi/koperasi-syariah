<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PengajuanPembiayaan;
use Illuminate\Support\Facades\DB;

echo "=== FIX STATUS PEMBIAYAAN ===\n\n";

// Ambil semua pembiayaan yang status-nya cair atau lunas
$pembiayaans = PengajuanPembiayaan::whereIn('status', ['cair', 'lunas'])->get();

foreach ($pembiayaans as $p) {
    $totalPokok = (float)$p->jumlah_pengajuan;
    $totalMargin = (float)$p->jumlah_margin;
    $totalHarusDibayar = $totalPokok + $totalMargin;

    // Hitung total yang sudah dibayar
    $totalDibayar = (float)$p->totalDibayar();

    // Cek apakah ada angsuran pending yang BELUM dibayar sama sekali
    $pendingBelumBayar = $p->angsurans()
        ->where('status', 'pending')
        ->where('jumlah_dibayar', 0)
        ->count();

    // Syarat: totalDibayar >= totalHarusDibayar DAN tidak ada pending yang belum dibayar
    $isLunas = ($totalDibayar >= ($totalHarusDibayar - 0.01)) && ($pendingBelumBayar === 0);

    $statusSeharusnya = $isLunas ? 'lunas' : 'cair';

    if ($p->status !== $statusSeharusnya) {
        echo "Pembiayaan {$p->id} ({$p->kode_pengajuan}): {$p->status} -> {$statusSeharusnya}\n";
        echo "  Total: Rp " . number_format($totalHarusDibayar, 0, ',', '.') . "\n";
        echo "  Dibayar: Rp " . number_format($totalDibayar, 0, ',', '.') . "\n";
        echo "  Progress: " . number_format(($totalDibayar / $totalHarusDibayar) * 100, 1) . "%\n";
        echo "  Pending belum bayar: {$pendingBelumBayar}\n\n";

        $p->update(['status' => $statusSeharusnya]);
    }
}

echo "\n=== SELESAI ===\n";
