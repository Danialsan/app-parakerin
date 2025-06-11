<?php

namespace Database\Seeders;

use App\Models\Dudi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'dudi 1',
            'email' => 'dudi@gmail.com',
            'password' => '12345',
            'role' => 'dudi'
        ]);

        Dudi::create([
            'pimpinan_dudi' => 'Web tech',
            'nama_perusahaan' => 'Webtech',
            'alamat_dudi' => 'Surabaya',
            'radius_kantor' => null,
            'posisi_kantor' => null,
            'user_id' => $user1->id
        ]);
    }
}
