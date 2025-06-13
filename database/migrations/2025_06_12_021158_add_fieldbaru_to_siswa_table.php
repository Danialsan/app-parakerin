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
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nama');
            $table->string('kelas');
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('foto')->nullable();
            $table->foreignid('jurusan_id')->constrained('jurusan','id')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreignUuid('dudi_id')->constrained('dudi','id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignuuid('pembimbing_sekolah_id')->nullable()->constrained('pembimbing_sekolah','id')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('nama');
            $table->dropColumn('kelas');
            $table->dropColumn('alamat');
            $table->dropColumn('telepon');
            $table->dropColumn('foto');
            // $table->dropForeign(['jurusan_id']);
            // $table->dropForeign(['dudi_id']);
            $table->dropForeign(['pembimbing_sekolah_id']);
        });
    }
};
