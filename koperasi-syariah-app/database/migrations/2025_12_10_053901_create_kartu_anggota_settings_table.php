<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKartuAnggotaSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kartu_anggota_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koperasi')->nullable();
            $table->string('alamat_koperasi')->nullable();
            $table->string('telepon_koperasi')->nullable();
            $table->string('email_koperasi')->nullable();
            $table->string('website_koperasi')->nullable();
            $table->string('nama_ketua')->nullable();
            $table->string('jabatan_ketua')->default('Ketua Koperasi');

            // Front card settings
            $table->string('background_front')->default('gradient-blue');
            $table->string('primary_color_front')->default('#1e40af');
            $table->string('secondary_color_front')->default('#3b82f6');
            $table->string('text_color_front')->default('#ffffff');
            $table->boolean('show_logo_front')->default(true);
            $table->boolean('show_nomor_anggota_front')->default(true);
            $table->boolean('show_nama_anggota_front')->default(true);
            $table->boolean('show_foto_anggota_front')->default(true);
            $table->boolean('show_tanggal_masuk_front')->default(false);
            $table->boolean('show_barcode_front')->default(true);
            $table->boolean('show_valid_until_front')->default(true);

            // Back card settings
            $table->string('background_back')->default('gradient-blue');
            $table->string('primary_color_back')->default('#1e40af');
            $table->string('secondary_color_back')->default('#3b82f6');
            $table->string('text_color_back')->default('#ffffff');
            $table->boolean('show_nama_ketua_back')->default(true);
            $table->boolean('show_tanda_tangan_back')->default(true);
            $table->boolean('show_syarat_ketentuan_back')->default(true);
            $table->text('syarat_ketentuan')->nullable();
            $table->text('custom_text_back')->nullable();

            // Position settings (percentage from top-left)
            $table->json('positions_front')->nullable(); // {logo, nomor_anggota, nama_anggota, foto, tanggal_masuk, barcode}
            $table->json('positions_back')->nullable();  // {nama_ketua, tanda_tangan, syarat_ketentuan, custom_text}

            // Font settings
            $table->string('font_family')->default('Arial');
            $table->json('font_sizes_front')->nullable(); // {title, subtitle, body, small}
            $table->json('font_sizes_back')->nullable();  // {title, subtitle, body, small}

            // Custom fields
            $table->json('custom_fields_front')->nullable(); // [{name, label, show}]
            $table->json('custom_fields_back')->nullable();  // [{name, label, show}]

            $table->timestamps();
        });

        // Insert default settings
        \DB::table('kartu_anggota_settings')->insert([
            'nama_koperasi' => 'Koperasi Syariah',
            'alamat_koperasi' => 'Jl. Contoh No. 123, Jakarta',
            'telepon_koperasi' => '021-1234567',
            'email_koperasi' => 'info@koperasi.com',
            'website_koperasi' => 'www.koperasi.com',
            'nama_ketua' => 'Ahmad Hidayat',
            'positions_front' => json_encode([
                'logo' => ['x' => 10, 'y' => 10, 'width' => 80],
                'nomor_anggota' => ['x' => 10, 'y' => 50],
                'nama_anggota' => ['x' => 10, 'y' => 70],
                'foto' => ['x' => 250, 'y' => 40, 'width' => 60],
                'tanggal_masuk' => ['x' => 10, 'y' => 90],
                'barcode' => ['x' => 200, 'y' => 130, 'width' => 40]
            ]),
            'positions_back' => json_encode([
                'nama_ketua' => ['x' => 10, 'y' => 140],
                'tanda_tangan' => ['x' => 200, 'y' => 135, 'width' => 80],
                'syarat_ketentuan' => ['x' => 10, 'y' => 20],
                'custom_text' => ['x' => 10, 'y' => 100]
            ]),
            'font_sizes_front' => json_encode([
                'title' => 16,
                'subtitle' => 14,
                'body' => 12,
                'small' => 10
            ]),
            'font_sizes_back' => json_encode([
                'title' => 16,
                'subtitle' => 14,
                'body' => 12,
                'small' => 10
            ]),
            'syarat_ketentuan' => 'Kartu ini berlaku sebagai identitas resmi anggota koperasi. Harap disimpan dengan baik dan dilaporkan jika hilang atau rusak.',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kartu_anggota_settings');
    }
}
