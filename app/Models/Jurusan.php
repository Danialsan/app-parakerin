<?php

namespace App\Models;

use App\Models\CapaianPembelajaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jurusan extends Model
{
    use HasFactory;
    protected $table = 'jurusan';
    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
    ];

    public function capaianPembelajaran()
    {
        return $this->hasMany(CapaianPembelajaran::class);
    }
}
