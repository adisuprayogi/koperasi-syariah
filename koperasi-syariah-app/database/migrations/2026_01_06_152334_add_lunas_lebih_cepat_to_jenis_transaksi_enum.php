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
        // Add 'lunas_lebih_cepat' to the jenis_transaksi enum
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN jenis_transaksi ENUM('simpanan','angsuran','pencairan','lunas_lebih_cepat') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE transaksis MODIFY COLUMN jenis_transaksi ENUM('simpanan','angsuran','pencairan') NOT NULL");
    }
};
