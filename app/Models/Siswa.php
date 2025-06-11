<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Siswa extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'siswa';
    protected $fillable = [
        'name',
        'nis',
        'gender',
        'user_id'
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

}
