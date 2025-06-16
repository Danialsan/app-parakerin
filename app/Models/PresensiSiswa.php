<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiSiswa extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'presensi_siswa';
    protected $fillable = ['absensi', 'keterangan', 'posisi_masuk', 'posisi_pulang', 'waktu_masuk', 'waktu_pulang', 'siswa_id'];
    public $timestamps = true;

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
