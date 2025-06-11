<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Siswa::create([
            'nis' => '12345',
            'gender' => 'L',
            'dudi_id' => null,
            'user_id'
        ]);

    }
}
