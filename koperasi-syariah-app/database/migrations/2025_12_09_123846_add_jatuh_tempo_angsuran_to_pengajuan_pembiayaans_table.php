<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJatuhTempoAngsuranToPengajuanPembiayaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_pembiayaans', function (Blueprint $table) {
            $table->date('tanggal_jatuh_tempo_pertama')->nullable()->after('bukti_pencairan_original');
            $table->text('keterangan_jatuh_tempo')->nullable()->after('tanggal_jatuh_tempo_pertama');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengajuan_pembiayaans', function (Blueprint $table) {
            $table->dropColumn(['tanggal_jatuh_tempo_pertama', 'keterangan_jatuh_tempo']);
        });
    }
}
