<?php

namespace App\Models;

use App\Models\PengaturanPkl;
use PhpParser\Node\Expr\FuncCall;
use App\Models\PengaturanPklSiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'siswa';
    protected $fillable = [
        // 'name',
        'id',
        'nis',
        'gender',
        'user_id',
        'pembimbing_sekolah_id',
        'jurusan_id',
        'alamat',
        'telepon',
        'foto',
        'nama',
        'kelas',
        'dudi_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function presensiSiswa()
    {
        return $this->hasMany(PresensiSiswa::class);
    }

    public function dudi()
    {
        return $this->belongsTo(Dudi::class);
    }
    public function pembimbing_sekolah()
    {
        return $this->belongsTo(PembimbingSekolah::class);
    }
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function pengaturanPkl()
    {
        return $this->hasOne(PengaturanPkl::class);
    }

    public function monitoringDetails()
    {
        return $this->hasMany(MonitoringPklDetail::class, 'siswa_id');
    }
    public function jurnalHarian()
    {
        return $this->hasMany(JurnalHarian::class);
    }

}
