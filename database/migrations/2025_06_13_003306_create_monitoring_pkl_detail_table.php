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
        Schema::create('monitoring_pkl_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('monitoring_pkl_id');
            $table->uuid('siswa_id');
            $table->tinyInteger('kehadiran')->nullable(); // 0-100 atau skala 1-4
            $table->tinyInteger('sikap')->nullable();
            $table->tinyInteger('progres')->nullable();
            $table->tinyInteger('kesesuaian')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('monitoring_pkl_id')->references('id')->on('monitoring_pkl')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_pkl_detail');
    }
};
