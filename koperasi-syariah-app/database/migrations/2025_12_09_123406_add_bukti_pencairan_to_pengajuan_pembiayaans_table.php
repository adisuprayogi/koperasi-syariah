<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuktiPencairanToPengajuanPembiayaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_pembiayaans', function (Blueprint $table) {
            $table->string('bukti_pencairan')->nullable()->after('tanggal_cair');
            $table->string('bukti_pencairan_original')->nullable()->after('bukti_pencairan');
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
            $table->dropColumn(['bukti_pencairan', 'bukti_pencairan_original']);
        });
    }
}
