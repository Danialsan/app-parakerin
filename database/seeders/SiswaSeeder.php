<?php

namespace Database\Seeders;

use App\Models\Jurusan;
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
            'name' => 'coba',
            'email' => 'coba@gmail.com',
            'password' => '12345',
            'role' => 'siswa'
        ]);

        $jurusan1 = Jurusan::create([
            'kode_jurusan' => 'RPl',
            'nama_jurusan' => 'Rekayasa Perangkat Lunak',
        ]);

        Siswa::create([
            'nis' => '12345',
            'gender' => 'L',
            'user_id' => $user1->id,
            'pembimbing_sekolah_id' => null,
            'jurusan_id' => $jurusan1->id,
            'alamat' => 'Nambangan',
            'telepon' => '081234567890',
            'foto' => null,
            'nama' => 'Coba',
            'kelas' => 'XIII',
            'dudi_id' => null,
        ]);

    }
}
