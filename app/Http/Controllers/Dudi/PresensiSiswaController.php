<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiSiswaController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $dudi = $user->dudi->siswa->pluck('id');
        $presensi_siswa = PresensiSiswa::whereIn('siswa_id', $dudi)->orderBy('created_at', 'desc')->get();
        // dd($presensi_siswa);
        // dd($presensi);
        // $presensi_siswa = PresensiSiswa::where('siswa_id');
        // $siswa = Siswa::where('dudi_id', $dudi->id)->with('presensiSiswa')->get();
        return view('dudi.presensi-siswa', compact('presensi_siswa'));
    }
}
