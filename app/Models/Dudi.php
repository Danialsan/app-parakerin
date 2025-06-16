<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Dudi extends Model
{
    use HasUuids;
    protected $table = 'dudi';
    protected $fillable = [
        'id',
        'pimpinan_dudi',
        'nama_perusahaan',
        'alamat_dudi',
        'radius_kantor',
        'posisi_kantor',
        'user_id',
        'bidang_usaha',
        'nama_pembimbing',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

}
