<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkflowFieldsToPengajuanPembiayaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_pembiayaans', function (Blueprint $table) {
            $table->unsignedBigInteger('verifikator_id')->nullable()->after('status');
            $table->timestamp('tanggal_verifikasi')->nullable()->after('verifikator_id');
            $table->unsignedBigInteger('approver_id')->nullable()->after('tanggal_verifikasi');
            $table->timestamp('tanggal_approve')->nullable()->after('approver_id');
            $table->unsignedBigInteger('rejecter_id')->nullable()->after('tanggal_approve');
            $table->timestamp('tanggal_reject')->nullable()->after('rejecter_id');
            $table->text('alasan_reject')->nullable()->after('tanggal_reject');
            $table->unsignedBigInteger('pencair_id')->nullable()->after('alasan_reject');
            $table->timestamp('tanggal_cair')->nullable()->after('pencair_id');

            // Foreign keys
            $table->foreign('verifikator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejecter_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pencair_id')->references('id')->on('users')->onDelete('set null');
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
            $table->dropForeign(['verifikator_id']);
            $table->dropForeign(['approver_id']);
            $table->dropForeign(['rejecter_id']);
            $table->dropForeign(['pencair_id']);

            $table->dropColumn([
                'verifikator_id',
                'tanggal_verifikasi',
                'approver_id',
                'tanggal_approve',
                'rejecter_id',
                'tanggal_reject',
                'alasan_reject',
                'pencair_id',
                'tanggal_cair'
            ]);
        });
    }
}
