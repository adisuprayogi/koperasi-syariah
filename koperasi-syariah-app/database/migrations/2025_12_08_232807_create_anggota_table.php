<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('no_anggota')->unique();
            $table->string('nama_lengkap');
            $table->string('nik')->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp');
            $table->string('email')->unique();
            $table->text('alamat_lengkap');
            $table->string('pekerjaan');
            $table->string('penghasilan')->nullable();
            $table->string('no_npwp')->nullable();
            $table->enum('status_keanggotaan', ['aktif', 'tidak_aktif', 'keluar'])->default('aktif');
            $table->date('tanggal_gabung');
            $table->enum('jenis_anggota', ['biasa', 'luar_biasa', 'kehormatan'])->default('biasa');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('anggota');
    }
}
