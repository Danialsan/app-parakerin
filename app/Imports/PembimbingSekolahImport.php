<?php

namespace App\Imports;

use App\Models\User;
use App\Models\PembimbingSekolah;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PembimbingSekolahImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // dd($row);

        // Buat user jika belum ada
        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name'     => $row['nama_pembimbing'],
                'role'     => 'pembimbing',
                'password' => Hash::make('pembimbing123'),
            ]
        );

        // Buat data pembimbing sekolah
        return new PembimbingSekolah([
            'nama_pembimbing' => $row['nama_pembimbing'],
            'nip'             => $row['nip'],
            'jabatan'         => $row['jabatan'],
            'foto'            => 'default.png',
            'user_id'         => $user->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_pembimbing' => 'required|string|max:100',
            'nip'             => 'required|numeric|digits_between:1,30',
            'jabatan'         => 'required|string|max:50',
            'email'           => 'required|email|unique:users,email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_pembimbing.required' => 'Nama pembimbing wajib diisi.',
            'nip.required'             => 'NIP wajib diisi.',
            'jabatan.required'         => 'Jabatan wajib diisi.',
            'email.required'           => 'Email wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'email.unique'             => 'Email sudah digunakan.',
        ];
    }
}
