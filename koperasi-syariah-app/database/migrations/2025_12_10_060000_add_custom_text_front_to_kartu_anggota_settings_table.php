<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomTextFrontToKartuAnggotaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kartu_anggota_settings', function (Blueprint $table) {
            $table->string('custom_text_front')->nullable()->after('show_valid_until_front');
            $table->boolean('show_custom_text_front')->default(true)->after('custom_text_front');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kartu_anggota_settings', function (Blueprint $table) {
            $table->dropColumn(['custom_text_front', 'show_custom_text_front']);
        });
    }
}