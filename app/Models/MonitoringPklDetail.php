<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonitoringPklDetail extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'monitoring_pkl_detail';
    protected $fillable = [
        'monitoring_pkl_id',
        'siswa_id',
        'kehadiran',
        'sikap',
        'progres',
        'kesesuaian',
        'catatan',
    ];

    public function monitoring()
    {
        return $this->belongsTo(MonitoringPkl::class, 'monitoring_pkl_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}

