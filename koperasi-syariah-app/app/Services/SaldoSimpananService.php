<?php

namespace App\Services;

use App\Models\TransaksiSimpanan;
use App\Models\Anggota;
use App\Models\JenisSimpanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaldoSimpananService
{
    /**
     * Recalculate saldo for all anggota
     *
     * @param int|null $anggotaId
     * @param int|null $jenisSimpananId
     * @return array
     */
    public function recalculateSaldo($anggotaId = null, $jenisSimpananId = null)
    {
        $result = [
            'success' => true,
            'message' => 'Saldo berhasil dihitung ulang',
            'total_updated' => 0,
            'anggota_processed' => 0,
            'details' => []
        ];

        try {
            // Build query for anggota
            $anggotaQuery = Anggota::where('status_keanggotaan', 'aktif');
            if ($anggotaId) {
                $anggotaQuery->where('id', $anggotaId);
            }
            $anggotas = $anggotaQuery->get();

            // Build query for jenis simpanan
            $jenisSimpananQuery = JenisSimpanan::where('status', 1);
            if ($jenisSimpananId) {
                $jenisSimpananQuery->where('id', $jenisSimpananId);
            }
            $jenisSimpanans = $jenisSimpananQuery->get();

            $totalUpdated = 0;
            $anggotaProcessed = 0;

            foreach ($anggotas as $anggota) {
                $anggotaDetails = [];

                foreach ($jenisSimpanans as $jenisSimpanan) {
                    $updated = $this->recalculateSaldoPerAnggotaPerJenis(
                        $anggota->id,
                        $jenisSimpanan->id
                    );

                    if ($updated > 0) {
                        $anggotaDetails[] = [
                            'jenis_simpanan' => $jenisSimpanan->nama_simpanan,
                            'updated' => $updated
                        ];
                        $totalUpdated += $updated;
                    }
                }

                if (count($anggotaDetails) > 0) {
                    $result['details'][] = [
                        'anggota_id' => $anggota->id,
                        'nama_anggota' => $anggota->nama_lengkap,
                        'no_anggota' => $anggota->no_anggota,
                        'updates' => $anggotaDetails
                    ];
                    $anggotaProcessed++;
                }
            }

            $result['total_updated'] = $totalUpdated;
            $result['anggota_processed'] = $anggotaProcessed;

            Log::info('Saldo recalculation completed', [
                'total_updated' => $totalUpdated,
                'anggota_processed' => $anggotaProcessed
            ]);

        } catch (\Exception $e) {
            $result['success'] = false;
            $result['message'] = 'Error: ' . $e->getMessage();
            Log::error('Saldo recalculation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $result;
    }

    /**
     * Recalculate saldo for specific anggota and jenis simpanan
     *
     * @param int $anggotaId
     * @param int $jenisSimpananId
     * @return int Number of records updated
     */
    protected function recalculateSaldoPerAnggotaPerJenis($anggotaId, $jenisSimpananId)
    {
        // Get all verified transactions for this anggota and jenis simpanan
        // Order by created_at to ensure correct sequence
        $transaksi = TransaksiSimpanan::where('anggota_id', $anggotaId)
            ->where('jenis_simpanan_id', $jenisSimpananId)
            ->where('status', 'verified')
            ->orderBy('tanggal_transaksi')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        if ($transaksi->isEmpty()) {
            return 0;
        }

        $updatedCount = 0;
        $runningSaldo = 0;

        foreach ($transaksi as $trx) {
            // Store saldo before
            $saldoSebelumnya = $runningSaldo;

            // Calculate saldo after based on transaction type
            if ($trx->jenis_transaksi == 'setor') {
                $runningSaldo += $trx->jumlah;
            } elseif ($trx->jenis_transaksi == 'tarik') {
                $runningSaldo -= $trx->jumlah;
            }

            $saldoSetelahnya = $runningSaldo;

            // Update if values are different
            if (
                $trx->saldo_sebelumnya != $saldoSebelumnya ||
                $trx->saldo_setelahnya != $saldoSetelahnya
            ) {
                $trx->saldo_sebelumnya = $saldoSebelumnya;
                $trx->saldo_setelahnya = $saldoSetelahnya;
                $trx->save();
                $updatedCount++;
            }
        }

        return $updatedCount;
    }

    /**
     * Get current saldo summary for an anggota
     *
     * @param int $anggotaId
     * @return array
     */
    public function getSaldoSummary($anggotaId)
    {
        $jenisSimpanans = JenisSimpanan::where('status', 1)->get();
        $summary = [];

        foreach ($jenisSimpanans as $jenis) {
            $lastTransaksi = TransaksiSimpanan::where('anggota_id', $anggotaId)
                ->where('jenis_simpanan_id', $jenis->id)
                ->where('status', 'verified')
                ->orderBy('tanggal_transaksi', 'desc')
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $saldo = $lastTransaksi ? $lastTransaksi->saldo_setelahnya : 0;

            $summary[] = [
                'jenis_simpanan_id' => $jenis->id,
                'nama_simpanan' => $jenis->nama_simpanan,
                'tipe_simpanan' => $jenis->tipe_simpanan,
                'saldo' => $saldo
            ];
        }

        return $summary;
    }

    /**
     * Fix transactions with incorrect saldo calculation
     * This method identifies and fixes transactions where saldo calculation might be wrong
     *
     * @param int|null $limit
     * @return array
     */
    public function fixIncorrectSaldo($limit = null)
    {
        $result = [
            'success' => true,
            'message' => 'Saldo incorrect berhasil diperbaiki',
            'total_checked' => 0,
            'total_fixed' => 0,
            'errors' => []
        ];

        try {
            // Get all anggota
            $anggotas = Anggota::where('status_keanggotaan', 'aktif')
                ->when($limit, function($q) use ($limit) {
                    return $q->limit($limit);
                })
                ->get();

            $totalChecked = 0;
            $totalFixed = 0;

            foreach ($anggotas as $anggota) {
                $jenisSimpanans = JenisSimpanan::where('status', 1)->get();

                foreach ($jenisSimpanans as $jenis) {
                    $transaksi = TransaksiSimpanan::where('anggota_id', $anggota->id)
                        ->where('jenis_simpanan_id', $jenis->id)
                        ->where('status', 'verified')
                        ->orderBy('tanggal_transaksi')
                        ->orderBy('created_at')
                        ->orderBy('id')
                        ->get();

                    if ($transaksi->isEmpty()) {
                        continue;
                    }

                    $totalChecked++;
                    $runningSaldo = 0;
                    $hasError = false;

                    foreach ($transaksi as $index => $trx) {
                        $expectedSaldoSebelumnya = $runningSaldo;

                        if ($trx->jenis_transaksi == 'setor') {
                            $runningSaldo += $trx->jumlah;
                        } elseif ($trx->jenis_transaksi == 'tarik') {
                            $runningSaldo -= $trx->jumlah;
                        }

                        $expectedSaldoSetelahnya = $runningSaldo;

                        // Check if current values match expected
                        if (
                            $trx->saldo_sebelumnya != $expectedSaldoSebelumnya ||
                            $trx->saldo_setelahnya != $expectedSaldoSetelahnya
                        ) {
                            $hasError = true;
                            // Fix it
                            $trx->saldo_sebelumnya = $expectedSaldoSebelumnya;
                            $trx->saldo_setelahnya = $expectedSaldoSetelahnya;
                            $trx->save();
                        }
                    }

                    if ($hasError) {
                        $totalFixed++;
                    }
                }
            }

            $result['total_checked'] = $totalChecked;
            $result['total_fixed'] = $totalFixed;

            Log::info('Saldo fix completed', [
                'total_checked' => $totalChecked,
                'total_fixed' => $totalFixed
            ]);

        } catch (\Exception $e) {
            $result['success'] = false;
            $result['message'] = 'Error: ' . $e->getMessage();
            $result['errors'][] = $e->getMessage();
            Log::error('Saldo fix failed', [
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }
}
