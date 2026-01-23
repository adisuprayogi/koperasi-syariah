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
        // Add catatan column
        Schema::table('angsurans', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('keterangan');
        });

        // Add 'lunas_lebih_cepat' to status enum
        DB::statement("ALTER TABLE angsurans MODIFY COLUMN status ENUM('pending', 'terbayar', 'terlambat', 'lunas_lebih_cepat') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('angsurans', function (Blueprint $table) {
            $table->dropColumn('catatan');
        });

        // Revert status enum
        DB::statement("ALTER TABLE angsurans MODIFY COLUMN status ENUM('pending', 'terbayar', 'terlambat') DEFAULT 'pending'");
    }
};
