<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Cari jurusan berdasarkan nama
        $jurusan = Jurusan::where('nama_jurusan', $row['nama_jurusan'])->first();

        // Cek apakah user dengan email ini sudah ada
        $user = User::where('email', $row['email'])->first();
        if (!$user) {
            $user = User::create([
                'name' => $row['nama'],
                'email' => $row['email'],
                'password' => Hash::make('siswa123'), // default password
                'role' => 'siswa', // pastikan kolom ini ada di tabel users
            ]);
        }
        // dd($user);
        return new Siswa([
            'nis' => $row['nis'],
            'gender' => $row['gender'],
            'user_id' => $user->id,
            'jurusan_id' => $jurusan ? $jurusan->id : null,
            'nama' => $row['nama'],
            'kelas' => $row['kelas'],
            'telepon' => $row['telepon'],
            'alamat' => $row['alamat'],
            'foto' => 'default.png',
            'pembimbing_sekolah_id' => null,
            'dudi_id' => null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => ['required', 'max:20', 'regex:/^[0-9]+$/'],
            'gender' => 'required|in:L,P',
            'nama' => 'required|string|max:100',
            'kelas' => 'required|string|max:10',
            'nama_jurusan' => 'required|string|exists:jurusan,nama_jurusan',
            'email' => 'required|email|unique:users,email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis.required' => 'NIS tidak boleh kosong.',
            'gender.in' => 'Gender harus L atau P.',
            'nama_jurusan.exists' => 'Nama jurusan tidak ditemukan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
        ];
    }

}
