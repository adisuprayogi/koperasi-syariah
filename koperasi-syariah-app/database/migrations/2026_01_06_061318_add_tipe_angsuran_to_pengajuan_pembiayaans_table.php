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
        Schema::table('pengajuan_pembiayaans', function (Blueprint $table) {
            $table->enum('tipe_angsuran', ['flat', 'menurun', 'menaik'])->default('flat')->after('tenor');
            $table->boolean('gunakan_jadwal')->default(true)->after('tipe_angsuran'); // Flag untuk menggunakan sistem jadwal baru
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_pembiayaans', function (Blueprint $table) {
            $table->dropColumn(['tipe_angsuran', 'gunakan_jadwal']);
        });
    }
};
