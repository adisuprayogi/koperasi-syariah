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
        // Drop existing unique constraint on no_anggota
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropUnique(['no_anggota']);
        });

        // Create composite unique index that ignores soft-deleted records
        // Using NULL for deleted_at in active records, and a value for deleted records
        Schema::table('anggota', function (Blueprint $table) {
            $table->unique(['no_anggota', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop composite unique index
        Schema::table('anggota', function (Blueprint $table) {
            $table->dropUnique(['no_anggota', 'deleted_at']);
        });

        // Restore original unique constraint on no_anggota
        Schema::table('anggota', function (Blueprint $table) {
            $table->unique(['no_anggota']);
        });
    }
};
