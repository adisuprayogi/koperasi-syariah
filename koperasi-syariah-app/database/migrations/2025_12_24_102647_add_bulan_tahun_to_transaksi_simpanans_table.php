<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi_simpanans', function (Blueprint $table) {
            $table->unsignedTinyInteger('bulan')->nullable()->after('tanggal_transaksi')->comment('Bulan simpanan (1-12)');
            $table->year('tahun')->nullable()->after('bulan')->comment('Tahun simpanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_simpanans', function (Blueprint $table) {
            $table->dropColumn(['bulan', 'tahun']);
        });
    }
};
