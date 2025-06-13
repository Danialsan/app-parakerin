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
        Schema::create('siswa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nis')->nullable();
            $table->string('nama');
            $table->string('kelas');
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('foto')->nullable();
            $table->enum('gender', ['P', 'L'])->nullable();
            $table->foreignUuid('dudi_id')->nullable()->constrained('dudi', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignid('jurusan_id')->constrained('jurusan', 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignuuid('pembimbing_sekolah_id')->nullable()->constrained('pembimbing_sekolah', 'id')->onUpdate('cascade')->onDelete('cascade');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
