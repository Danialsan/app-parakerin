<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PresensiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Presensi2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waktuSekarang = date('Y-m-d');
        $user = Auth::user();
        $siswa = $user->siswa;
        $posisi_awal = PresensiSiswa::orderBy('created_at', 'asc')->where('posisi_masuk', '!=', NULL)->where('absensi', 'hadir')->first();
        $presensiHariIni = PresensiSiswa::whereDate('created_at', $waktuSekarang)->where('siswa_id', $siswa->id)->first();

        return view('students.presensi-2', compact('presensiHariIni', 'posisi_awal'));
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
        $waktuSekarang = date('Y-m-d H:i:s');
        $user = Auth::user();
        $siswa = $user->siswa;
        $request->validate([
            'absensi' => 'required|in:hadir,izin,sakit,libur,tidak hadir'
        ]);

        if ($request->absensi == 'hadir') {
            PresensiSiswa::create([
                'absensi' => $request->absensi,
                'posisi_masuk' => $request->posisi_masuk,
                'waktu_masuk' => $waktuSekarang,
                'siswa_id' => $siswa->id
            ]);
        } else {
            $request->validate([
                'keterangan' => 'required'
            ]);

            PresensiSiswa::create([
                'absensi' => $request->absensi,
                'keterangan' => $request->keterangan,
                'siswa_id' => $siswa->id
            ]);
        }

        return redirect()->back();
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
        $user = Auth::user();
        $siswa = $user->siswa;
        $waktuSekarang = date('Y-m-d H:i:s');

        $presensi = PresensiSiswa::findOrFail($id);
        if ($presensi->siswa_id != $siswa->id) {
            return redirect()->back();
        }

        $presensi->update([
            'waktu_pulang' => $waktuSekarang,
            'posisi_pulang' => $request->posisi_pulang
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
