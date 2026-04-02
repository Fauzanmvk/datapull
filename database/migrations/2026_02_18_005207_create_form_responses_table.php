<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('form_responses', function (Blueprint $table) {
        $table->id();

        // basic info
        $table->timestamp('submitted_at')->nullable();
        $table->string('cagar_budaya')->nullable();

        // petugas
        $table->string('nama_petugas')->nullable();
        $table->text('kegiatan')->nullable();
        $table->string('dokumentasi_kegiatan')->nullable();

        // pengunjung
        $table->integer('jumlah_pengunjung')->nullable();
        $table->integer('wisatawan_nusantara')->nullable();
        $table->integer('pelajar_mahasiswa')->nullable();
        $table->integer('wisatawan_mancanegara')->nullable();
        $table->integer('tamu_dinas')->nullable();
        $table->string('dokumentasi_kunjungan')->nullable();

        // kerusakan
        $table->string('temuan_kerusakan')->nullable();
        $table->text('deskripsi_kerusakan')->nullable();
        $table->string('dokumentasi_kerusakan')->nullable();

        // keamanan
        $table->string('kondisi_keamanan_situs')->nullable();
        $table->text('catatan_kondisi_keamanan')->nullable();

        // raw backup
        $table->json('raw_payload')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_responses');
    }
};
