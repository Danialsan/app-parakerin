<?php

namespace App\Imports;

use Throwable;
use App\Models\Dudi;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DudiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsFailures;

    public function model(array $row)
    {
        $email = $row['email'];
        $nama_perusahaan = $row['nama_perusahaan'];

        // Cari atau buat user
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $nama_perusahaan,
                'password' => Hash::make('dudi123'), // default password
                'role' => 'dudi', // sesuaikan
            ]
        );
        // dd($user->id);
        // dd(Str::uuid());
        // $id = (Str::uuid());

        // Buat data DUDI
        return new Dudi([
            'id' => Str::uuid(),
            'pimpinan_dudi' => $row['pimpinan_dudi'],
            'nama_perusahaan' => $row['nama_perusahaan'],
            'alamat_dudi' => $row['alamat_dudi'],
            'radius_kantor' => 0,
            'posisi_kantor' => 0,
            'user_id' => $user->id,
            'bidang_usaha' => $row['bidang_usaha'],
            'nama_pembimbing' => $row['nama_pembimbing'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.email' => 'required|email',
            '*.nama_pembimbing' => 'required|string',
            '*.nama_perusahaan' => 'required|string',
            '*.alamat_dudi' => 'required|string',
            '*.bidang_usaha' => 'required|string',
            '*.pimpinan_dudi' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.email.required' => 'Email pembimbing DUDI wajib diisi.',
            '*.email.email' => 'Format email tidak valid.',
            '*.nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            '*.nama_perusahaan.string' => 'Nama perusahaan harus berupa string.',
            '*.alamat_dudi.required' => 'Alamat DUDI wajib diisi.',
            '*.alamat_dudi.string' => 'Alamat DUDI harus berupa string.',
            '*.bidang_usaha.required' => 'Bidang usaha DUDI wajib diisi.',
            '*.bidang_usaha.string' => 'Bidang usaha DUDI harus berupa string.',
            '*.pimpinan_dudi.required' => 'Pimpinan DUDI wajib diisi.',
            '*.pimpinan_dudi.string' => 'Pimpinan DUDI harus berupa string.',
            // dan seterusnya...
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function onError(Throwable $e)
    {
        // Tangani kesalahan global
        throw $e;
    }
}
