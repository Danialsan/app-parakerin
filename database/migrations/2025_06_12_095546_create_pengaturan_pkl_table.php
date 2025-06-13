<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan_pkl', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('siswa_id');
            $table->uuid('dudi_id');
            $table->uuid('pembimbing_sekolah_id');
            $table->unsignedBigInteger('jurusan_id');
            $table->date('mulai');
            $table->date('selesai');
            $table->timestamps();

            // Foreign keys (opsional tapi disarankan)
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('dudi_id')->references('id')->on('dudi')->onDelete('cascade');
            $table->foreign('pembimbing_sekolah_id')->references('id')->on('pembimbing_sekolah')->onDelete('cascade');
            $table->foreign('jurusan_id')->references('id')->on('jurusan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_pkl');
    }
};
