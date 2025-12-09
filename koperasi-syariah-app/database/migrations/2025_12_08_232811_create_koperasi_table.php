<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoperasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koperasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koperasi');
            $table->text('alamat');
            $table->string('telepon');
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('no_koperasi')->unique();
            $table->date('tanggal_berdiri');
            $table->string('no_akta_notaris');
            $table->date('tanggal_akta');
            $table->string('nama_notaris');
            $table->string('ketua_nama');
            $table->string('ketua_nik');
            $table->string('sekretaris_nama');
            $table->string('sekretaris_nik');
            $table->string('bendahara_nama');
            $table->string('bendahara_nik');
            $table->string('logo')->nullable();
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('koperasi');
    }
}
