<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoSignatureToKartuAnggotaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kartu_anggota_settings', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('custom_fields_back');
            $table->string('signature_path')->nullable()->after('logo_path');
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
            $table->dropColumn(['logo_path', 'signature_path']);
        });
    }
}