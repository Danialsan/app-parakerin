<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user1 = User::create([
            'name' => 'siswa1',
            'email' => 'siswa@gmail.com',
            'password' => '12345',
            'role' => 'siswa'
        ]);

        Siswa::create([
            'nis' => '12345',
            'gender' => 'L',
            'dudi_id' => null,
            'user_id' => $user1->id
        ]);

        $user2 = User::create([
            'name' => 'achmad',
            'email' => 'achmad@gmail.com',
            'password' => '12345',
            'role' => 'siswa'
        ]);

        Siswa::create([
            'nis' => '54321',
            'gender' => 'P',
            'dudi_id' => null,
            'user_id' => $user2->id
        ]);

    }
}
