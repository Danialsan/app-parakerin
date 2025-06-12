<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PembimbingSekolah extends Model
{
    use HasUuids;
    protected $table = 'pembimbing_sekolah';
    protected $fillable = [
        'id',
        'nama_pembimbing',
        'nip',
        'jabatan',
        'foto',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
