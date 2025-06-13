<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringPkl extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'monitoring_pkl';
    protected $fillable = [
        'id',
        'pembimbing_sekolah_id',
        'dudi_id',
        'keperluan',
        'foto',
    ];

    public $incrementing = false; // karena pakai UUID
    protected $keyType = 'string';

    public function pembimbing()
    {
        return $this->belongsTo(PembimbingSekolah::class, 'pembimbing_sekolah_id');
    }

    public function dudi()
    {
        return $this->belongsTo(Dudi::class, 'dudi_id');
    }

    public function detail()
    {
        return $this->hasMany(MonitoringPklDetail::class, 'monitoring_pkl_id');
    }
}
