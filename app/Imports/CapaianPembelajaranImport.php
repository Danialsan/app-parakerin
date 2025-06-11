<?php
namespace App\Imports;

use App\Models\Jurusan;
use App\Models\CapaianPembelajaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CapaianPembelajaranImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $jurusan = Jurusan::where('nama_jurusan', $row['jurusan'])->first();

        if (!$jurusan) {
            throw new \Exception("Jurusan '{$row['jurusan']}' tidak ditemukan.");
        }

        return new CapaianPembelajaran([
            'jurusan_id' => $jurusan->id,
            'deskripsi_cp' => $row['deskripsi_cp'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.jurusan' => 'required',
            '*.deskripsi_cp' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.jurusan.required' => 'Nama jurusan wajib diisi.',
            '*.deskripsi_cp.required' => 'Deskripsi CP wajib diisi.',
        ];
    }
}


