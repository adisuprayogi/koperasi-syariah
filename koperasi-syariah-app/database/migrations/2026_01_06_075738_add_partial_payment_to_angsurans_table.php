<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('angsurans', function (Blueprint $table) {
            // Add partial payment columns
            $table->decimal('jumlah_dibayar', 15, 2)->default(0)->after('jumlah_angsuran')->comment('Jumlah yang sudah dibayar untuk angsuran ini');
            $table->decimal('sisa_dibawa', 15, 2)->default(0)->after('jumlah_dibayar')->comment('Sisa yang dibawa ke periode berikutnya');
            $table->boolean('is_perpanjangan')->default(false)->after('catatan')->comment('Apakah ini periode perpanjangan');
        });

        // Add 'partial_bayar' to status enum
        DB::statement("ALTER TABLE angsurans MODIFY COLUMN status ENUM('pending', 'terbayar', 'terlambat', 'lunas_lebih_cepat', 'partial_bayar') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angsurans', function (Blueprint $table) {
            $table->dropColumn(['jumlah_dibayar', 'sisa_dibawa', 'is_perpanjangan']);
        });

        // Revert status enum
        DB::statement("ALTER TABLE angsurans MODIFY COLUMN status ENUM('pending', 'terbayar', 'terlambat', 'lunas_lebih_cepat') DEFAULT 'pending'");
    }
};
