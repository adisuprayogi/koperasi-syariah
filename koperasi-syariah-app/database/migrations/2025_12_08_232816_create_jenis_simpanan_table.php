<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisSimpananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_simpanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jenis')->unique();
            $table->string('nama_simpanan');
            $table->enum('tipe_simpanan', ['modal', 'pokok', 'wajib', 'sukarela']);
            $table->decimal('nisbah', 5, 2)->nullable();
            $table->decimal('minimal_setor', 15, 2)->default(0);
            $table->decimal('maksimal_setor', 15, 2)->nullable();
            $table->boolean('bisa_ditarik')->default(true);
            $table->text('aturan_penarikan')->nullable();
            $table->enum('periode_hitung_bunga', ['harian', 'bulanan', 'kuartalan', 'tahunan'])->nullable();
            $table->boolean('status')->default(true);
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('jenis_simpanan');
    }
}
