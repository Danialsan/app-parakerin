<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalSiswaController extends Controller
{

    public function index()
    {

        $user = Auth::user();
        $siswa_by_dudi = $user->dudi->siswa->pluck('id');
        $rekap_jurnal = JurnalHarian::whereIn('siswa_id', $siswa_by_dudi)->orderBy('created_at', 'desc')->get();
        return view('dudi.jurnal-siswa', compact('rekap_jurnal'));
    }


}
