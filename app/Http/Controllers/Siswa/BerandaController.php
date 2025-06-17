<?php

namespace App\Http\Controllers\Siswa;

use Carbon\Carbon;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Models\PengaturanPkl;
use App\Models\PresensiSiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $hariIni = Carbon::today();

        $presensi = PresensiSiswa::where('siswa_id', $siswa->id)
            ->whereDate('waktu_masuk', $hariIni)
            ->first();

        $sudah_presensi_datang = $presensi !== null;
        $sudah_presensi_pulang = $presensi && $presensi->waktu_pulang !== null;

        $sudah_isi_jurnal = JurnalHarian::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $hariIni)
            ->exists();

        $nama_pembimbing = PengaturanPkl::where('siswa_id', $siswa->id)
            ->first()
            ->pembimbing->nama_pembimbing;

        $nama_industri = PengaturanPkl::where('siswa_id', $siswa->id)
            ->first()
            ->dudi->nama_perusahaan;

        $jurnal_belum_verifikasi = JurnalHarian::where('siswa_id', $siswa->id)
            ->where('verifikasi_pembimbing', false)
            ->count();

        return view('siswa.beranda', compact(
                'siswa',
                'sudah_presensi_datang',
                'sudah_presensi_pulang',
                'sudah_isi_jurnal',
                'nama_pembimbing',
                'nama_industri',
                'jurnal_belum_verifikasi'
        ));

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
