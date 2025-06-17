<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Informasi extends Model
{
    use HasUuids;
    protected $table = 'informasi';
    protected $fillable = ['target_role', 'isi'];

}
