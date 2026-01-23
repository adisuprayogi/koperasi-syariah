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
            $table->boolean('is_blacklisted')->default(false)->after('status_keanggotaan')->comment('Anggota di-blacklist karena tunggakan');
            $table->timestamp('blacklisted_at')->nullable()->after('is_blacklisted')->comment('Tanggal di-blacklist');
            $table->text('blacklist_reason')->nullable()->after('blacklisted_at')->comment('Alasan blacklist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropColumn(['is_blacklisted', 'blacklisted_at', 'blacklist_reason']);
        });
    }
};
