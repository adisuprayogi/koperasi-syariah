<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAngsuransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('angsurans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_angsuran', 50)->unique();
            $table->foreignId('pengajuan_pembiayaan_id')->constrained('pengajuan_pembiayaans')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
            $table->integer('angsuran_ke')->comment('Urutan angsuran (1, 2, 3, ...)');

            // Jumlah angsuran
            $table->decimal('jumlah_pokok', 15, 2)->default(0)->comment('Jumlah pokok angsuran');
            $table->decimal('jumlah_margin', 15, 2)->default(0)->comment('Jumlah margin/keuntungan angsuran');
            $table->decimal('jumlah_angsuran', 15, 2)->default(0)->comment('Total angsuran (pokok + margin)');

            // Status dan tanggal
            $table->enum('status', ['pending', 'terbayar', 'terlambat'])->default('pending');
            $table->date('tanggal_jatuh_tempo')->comment('Tanggal jatuh tempo pembayaran');
            $table->date('tanggal_bayar')->nullable()->comment('Tanggal pembayaran dilakukan');
            $table->date('tanggal_jatuh_tempo_akhir')->nullable()->comment('Tanggal jatuh tempo akhir setelah denda');

            // Denda
            $table->decimal('denda', 15, 2)->default(0)->comment('Jumlah denda keterlambatan');
            $table->decimal('persentase_denda', 5, 2)->default(0)->comment('Persentase denda per hari');
            $table->integer('hari_terlambat')->default(0)->comment('Jumlah hari keterlambatan');

            // Metadata
            $table->text('keterangan')->nullable()->comment('Keterangan pembayaran');
            $table->string('bukti_pembayaran')->nullable()->comment('File bukti pembayaran');
            $table->string('bukti_pembayaran_original')->nullable()->comment('Nama asli file bukti');
            $table->foreignId('dibayar_oleh')->nullable()->constrained('users')->onDelete('set null')->comment('User yang mencatat pembayaran');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['pengajuan_pembiayaan_id', 'angsuran_ke']);
            $table->index(['anggota_id', 'status']);
            $table->index(['tanggal_jatuh_tempo', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('angsurans');
    }
}
