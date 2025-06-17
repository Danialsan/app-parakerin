<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use App\Models\JurnalHarian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalSiswaController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $siswa_by_dudi = $user->dudi->siswa->pluck('id');

        if ($request->has('tanggal_awal') && $request->has('tanggal_akhir')) {
            $tanggal_awal = Carbon::parse($request->query('tanggal_awal'))->startOfDay();
            $tanggal_akhir = Carbon::parse($request->query('tanggal_akhir'))->endOfDay();
            $rekap_jurnal = JurnalHarian::whereIn('siswa_id', $siswa_by_dudi)
                ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $rekap_jurnal = JurnalHarian::whereIn('siswa_id', $siswa_by_dudi)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('dudi.jurnal-siswa', compact('rekap_jurnal'));
    }


}
