<?php

namespace Database\Seeders;

use App\Models\Informasi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InformasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Informasi
        Informasi::create([
            'judul' => 'Informasi Untuk Siswa',
            'isi' => 'Informasi Siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


    }
}
