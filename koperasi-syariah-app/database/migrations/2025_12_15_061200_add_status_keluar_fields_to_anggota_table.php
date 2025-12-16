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
        Schema::table('anggota', function (Blueprint $table) {
            $table->date('tanggal_keluar')->nullable()->after('status_keanggotaan');
            $table->text('alasan_keluar')->nullable()->after('tanggal_keluar');
            $table->enum('status_keanggotaan', ['aktif', 'tidak_aktif', 'keluar'])->default('aktif')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn(['tanggal_keluar', 'alasan_keluar']);
            $table->enum('status_keanggotaan', ['aktif', 'tidak_aktif'])->default('aktif')->change();
        });
    }
};