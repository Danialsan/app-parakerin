<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Dudi;
use App\Models\Siswa;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Models\MonitoringPkl;
use App\Models\PresensiSiswa;
use App\Models\PembimbingSekolah;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $jumlah_dudi = Dudi::count();
        $jumlah_pembimbing = PembimbingSekolah::count();
        $jumlah_siswa = Siswa::count();
        $jumlah_monitoring = MonitoringPkl::count();

        // Data hari ini
        $hariIni = Carbon::today();

        $jumlah_jurnal_hari_ini = JurnalHarian::whereDate('tanggal', $hariIni)->count();
        $jumlah_presensi_hari_ini = PresensiSiswa::whereDate('waktu_masuk', $hariIni)->count();

        return view('admin.beranda', compact(
            'jumlah_dudi',
            'jumlah_pembimbing',
            'jumlah_siswa',
            'jumlah_jurnal_hari_ini',
            'jumlah_presensi_hari_ini',
            'jumlah_monitoring'
        ));
        // return view('admin.beranda', compact('jumlah_dudi', 'jumlah_pembimbing', 'jumlah_siswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
