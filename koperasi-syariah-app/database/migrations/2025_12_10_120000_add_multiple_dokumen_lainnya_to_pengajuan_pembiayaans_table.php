<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleDokumenLainnyaToPengajuanPembiayaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_pembiayaans', function (Blueprint $table) {
            // Add separate columns for additional documents
            $table->string('jaminan_file_2')->nullable()->after('jaminan_file');
            $table->string('jaminan_file_3')->nullable()->after('jaminan_file_2');
            $table->string('dokumen_lainnya_1')->nullable()->after('jaminan_file_3');
            $table->string('dokumen_lainnya_2')->nullable()->after('dokumen_lainnya_1');
            $table->string('dokumen_lainnya_3')->nullable()->after('dokumen_lainnya_2');
            $table->string('dokumen_lainnya_4')->nullable()->after('dokumen_lainnya_3');
            $table->string('dokumen_lainnya_5')->nullable()->after('dokumen_lainnya_4');
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
            $table->dropColumn([
                'jaminan_file_2',
                'jaminan_file_3',
                'dokumen_lainnya_1',
                'dokumen_lainnya_2',
                'dokumen_lainnya_3',
                'dokumen_lainnya_4',
                'dokumen_lainnya_5'
            ]);
        });
    }
}