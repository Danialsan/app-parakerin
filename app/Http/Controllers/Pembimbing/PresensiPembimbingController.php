<?php

namespace App\Http\Controllers\Pembimbing;

use Illuminate\Http\Request;
use App\Models\PengaturanPkl;
use App\Models\PresensiSiswa;
use App\Http\Controllers\Controller;

class PresensiPembimbingController extends Controller
{
    public function presensiSiswaBimbingan()
    {
        $pembimbing = auth()->user()->pembimbingSekolah->first(); // ambil pembimbing yang login

        $siswaIds = PengaturanPkl::where('pembimbing_sekolah_id', $pembimbing->id)
            ->pluck('siswa_id');

        $presensis = PresensiSiswa::with('siswa')
            ->whereIn('siswa_id', $siswaIds)
            ->latest('waktu_masuk')
            ->paginate(10);

        return view('pembimbing.presensi-siswa-bimbingan', compact('presensis'));
    }


}
