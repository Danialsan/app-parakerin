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
        Schema::create('monitoring_pkl', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pembimbing_sekolah_id');
            $table->uuid('dudi_id');
            $table->enum('keperluan', ['pengantaran', 'monitoring 1', 'monitoring 2', 'monitoring 3', 'penjemputan']);
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('pembimbing_sekolah_id')->references('id')->on('pembimbing_sekolah')->onDelete('cascade');
            $table->foreign('dudi_id')->references('id')->on('dudi')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_pkl');
    }
};
