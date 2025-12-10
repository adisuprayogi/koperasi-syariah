<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFontColorFieldsToKartuAnggotaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kartu_anggota_settings', function (Blueprint $table) {
            $table->string('font_color_front', 7)->nullable()->default('#ffffff')->after('text_color_front');
            $table->string('font_color_back', 7)->nullable()->default('#ffffff')->after('text_color_back');
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
            $table->dropColumn(['font_color_front', 'font_color_back']);
        });
    }
}
