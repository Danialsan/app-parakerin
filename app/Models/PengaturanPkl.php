<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class PengaturanPkl extends Model
{
    use HasUuids;

    protected $table = 'pengaturan_pkl';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'siswa_id',
        'dudi_id',
        'pembimbing_sekolah_id',
        'jurusan_id',
        'mulai',
        'selesai',
    ];

    // Relasi ke Siswa (satu pengaturan hanya untuk satu siswa)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function dudi()
    {
        return $this->belongsTo(Dudi::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(PembimbingSekolah::class, 'pembimbing_sekolah_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    // Auto-generate UUID saat membuat data
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
