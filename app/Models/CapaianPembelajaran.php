<?php

namespace App\Models;

use App\Models\Jurusan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CapaianPembelajaran extends Model
{
    use HasFactory;
    protected $table = 'capaian_pembelajaran';
    protected $fillable = [
        'jurusan_id',
        'deskripsi_cp',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
    public function jurnalHarian()
    {
        return $this->hasMany(JurnalHarian::class);
    }

}
