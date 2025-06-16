<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalHarianTable extends Migration
{
    public function up()
    {
        Schema::create('jurnal_harian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('tanggal');
            $table->foreignUuid('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('capaian_pembelajaran_id')->constrained('capaian_pembelajaran')->onDelete('cascade');
            $table->text('kegiatan');
            $table->string('foto')->nullable();
            $table->boolean('verifikasi_pembimbing')->default(false);
            $table->text('catatan_pembimbing')->nullable();
            $table->timestamps();

            // Supaya siswa tidak bisa isi lebih dari satu jurnal per hari
            $table->unique(['siswa_id', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jurnal_harian');
    }
}

