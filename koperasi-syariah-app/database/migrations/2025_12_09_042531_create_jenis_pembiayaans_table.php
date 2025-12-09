<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisPembiayaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_pembiayaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jenis', 10)->unique();
            $table->string('nama_pembiayaan');
            $table->enum('tipe_pembiayaan', ['murabahah', 'mudharabah', 'musyarakah', 'qardh']);
            $table->decimal('margin', 8, 2)->default(0); // Untuk Murabahah
            $table->decimal('bagi_hasil', 8, 2)->default(0); // Untuk Mudharabah/Musyarakah
            $table->enum('periode_hitung', ['bulanan', 'tahunan', 'otomatis', 'jtempo'])->default('bulanan');
            $table->decimal('minimal_pembiayaan', 15, 2)->default(0);
            $table->decimal('maksimal_pembiayaan', 15, 2)->nullable();
            $table->integer('jangka_waktu_min')->default(1); // Dalam bulan
            $table->integer('jangka_waktu_max')->default(12); // Dalam bulan
            $table->text('syarat_dukung')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
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
        Schema::dropIfExists('jenis_pembiayaans');
    }
}
