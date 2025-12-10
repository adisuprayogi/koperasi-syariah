<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackgroundImagesToKartuAnggotaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kartu_anggota_settings', function (Blueprint $table) {
            $table->string('background_image_front')->nullable()->after('background_front');
            $table->string('background_image_back')->nullable()->after('background_back');
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
            $table->dropColumn(['background_image_front', 'background_image_back']);
        });
    }
}
