<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanPembiayaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_pembiayaans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengajuan', 50)->unique();
            $table->foreignId('anggota_id')->constrained('anggota')->onDelete('cascade');
            $table->foreignId('jenis_pembiayaan_id')->constrained('jenis_pembiayaans')->onDelete('restrict');
            $table->decimal('jumlah_pengajuan', 15, 2);
            $table->integer('tenor')->comment('jangka waktu dalam bulan');
            $table->decimal('margin_percent', 5, 2)->default(0)->comment('persentase margin');
            $table->decimal('jumlah_margin', 15, 2)->default(0);
            $table->decimal('angsuran_pokok', 15, 2)->default(0);
            $table->decimal('angsuran_margin', 15, 2)->default(0);
            $table->decimal('total_angsuran', 15, 2)->default(0);
            $table->enum('tujuan_pembiayaan', [
                'modal_kerja',
                'investasi',
                'konsumtif',
                'pendidikan',
                'renovasi',
                'lainnya'
            ])->default('modal_kerja');
            $table->text('deskripsi')->nullable();
            $table->enum('status', [
                'draft',
                'diajukan',
                'verifikasi',
                'approved',
                'rejected',
                'cair',
                'lunas',
                'batal'
            ])->default('draft');

            // Verification fields
            $table->foreignId('verified_by')->nullable()->constrained('pengurus')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();

            // Approval fields
            $table->foreignId('approved_by')->nullable()->constrained('pengurus')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_approval')->nullable();
            $table->enum('keputusan', ['approved', 'rejected'])->nullable();

            // Disbursement fields
            $table->foreignId('disbursed_by')->nullable()->constrained('pengurus')->onDelete('set null');
            $table->timestamp('disbursed_at')->nullable();
            $table->decimal('jumlah_cair', 15, 2)->nullable();
            $table->string('no_rekening', 50)->nullable();
            $table->string('atas_nama', 100)->nullable();
            $table->string('bukti_transfer')->nullable();

            // Document attachments
            $table->string('ktp_file')->nullable();
            $table->string('kk_file')->nullable();
            $table->string('slip_gaji_file')->nullable();
            $table->string('proposal_file')->nullable();
            $table->string('jaminan_file')->nullable();
            $table->json('dokumen_lainnya')->nullable();

            $table->text('alasan_penolakan')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_pembiayaans');
    }
}
