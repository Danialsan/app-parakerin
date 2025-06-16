<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PresensiSiswaController extends Controller
{
    public function index()
    {
        return view('dudi.presensi-siswa');
    }
}
