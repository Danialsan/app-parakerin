<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presensi_siswa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('absensi', ['hadir', 'izin', 'sakit', 'libur', 'tidak hadir']);
            $table->text('keterangan')->nullable();
            $table->string('posisi_masuk')->nullable();
            $table->string('posisi_pulang')->nullable();
            $table->timestamp('waktu_masuk')->nullable();
            $table->timestamp('waktu_pulang')->nullable();
            $table->foreignUuid('siswa_id')->constrained('siswa', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_siswa');
    }
};
