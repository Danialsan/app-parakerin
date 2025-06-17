<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JurnalHarian extends Model
{
    use HasUuids;

    protected $table = 'jurnal_harian';

    protected $fillable = [
        'tanggal',
        'siswa_id',
        'capaian_pembelajaran_id',
        'kegiatan',
        'foto',
        'verifikasi_pembimbing',
        'catatan_pembimbing',
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];


    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function capaianPembelajaran()
    {
        return $this->belongsTo(CapaianPembelajaran::class, 'capaian_pembelajaran_id');
    }
}
