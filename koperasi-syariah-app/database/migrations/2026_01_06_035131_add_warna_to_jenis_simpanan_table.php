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
        Schema::table('jenis_simpanan', function (Blueprint $table) {
            $table->string('warna')->nullable()->after('status');
        });

        // Set default colors for existing jenis simpanan
        DB::table('jenis_simpanan')->updateOrInsert(
            ['nama_simpanan' => 'Simpanan Pokok'],
            ['warna' => '#16a34a']
        );
        DB::table('jenis_simpanan')->updateOrInsert(
            ['nama_simpanan' => 'Simpanan Wajib'],
            ['warna' => '#0891b2']
        );
        DB::table('jenis_simpanan')->updateOrInsert(
            ['nama_simpanan' => 'Simpanan Sukarela'],
            ['warna' => '#8b5cf6']
        );
        DB::table('jenis_simpanan')->updateOrInsert(
            ['nama_simpanan' => 'Simpanan Modal'],
            ['warna' => '#f59e0b']
        );

        // Update warna untuk data yang sudah ada
        DB::table('jenis_simpanan')
            ->where('nama_simpanan', 'Simpanan Pokok')
            ->update(['warna' => '#16a34a']); // Green

        DB::table('jenis_simpanan')
            ->where('nama_simpanan', 'Simpanan Wajib')
            ->update(['warna' => '#0891b2']); // Cyan

        DB::table('jenis_simpanan')
            ->where('nama_simpanan', 'Simpanan Sukarela')
            ->update(['warna' => '#8b5cf6']); // Purple

        DB::table('jenis_simpanan')
            ->where('nama_simpanan', 'Simpanan Modal')
            ->update(['warna' => '#f59e0b']); // Amber
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_simpanan', function (Blueprint $table) {
            $table->dropColumn('warna');
        });
    }
};
