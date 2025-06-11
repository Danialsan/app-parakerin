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
        Schema::create('dudi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pimpinan_dudi')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->text('alamat_dudi')->nullable();
            $table->integer('radius_kantor')->nullable()->default(30);
            $table->string('posisi_kantor', 100)->nullable();
            $table->foreignId('user_id')->constrained('users', 'id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dudi');
    }
};
