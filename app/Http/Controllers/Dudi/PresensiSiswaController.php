<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use App\Models\PresensiSiswa;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiSiswaController extends Controller
{
    public function index(Request $request)
    {

        $user = Auth::user();
        $dudi = $user->dudi->siswa->pluck('id');


        if ($request->has('tanggal_awal') && $request->has('tanggal_akhir')) {
            $tanggal_awal = Carbon::parse($request->query('tanggal_awal'))->startOfDay();
            $tanggal_akhir = Carbon::parse($request->query('tanggal_akhir'))->endOfDay();
            $presensi_siswa = PresensiSiswa::whereIn('siswa_id', $dudi)
                ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $presensi_siswa = PresensiSiswa::whereIn('siswa_id', $dudi)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return view('dudi.presensi-siswa', compact('presensi_siswa'));
    }
}
