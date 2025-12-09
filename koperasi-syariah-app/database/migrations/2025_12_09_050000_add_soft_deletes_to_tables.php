<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add soft deletes to pengurus table
        Schema::table('pengurus', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to anggota table
        Schema::table('anggota', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to koperasi table
        Schema::table('koperasi', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to jenis_simpanan table
        Schema::table('jenis_simpanan', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add soft deletes to jenis_pembiayaan table
        Schema::table('jenis_pembiayaans', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove soft deletes from tables
        Schema::table('jenis_pembiayaans', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('jenis_simpanan', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('koperasi', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('anggota', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pengurus', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
