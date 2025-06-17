<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Dudi;
use App\Models\Siswa;
use App\Models\Informasi;
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
    }

    public function informasi()
    {
        $informasi = Informasi::paginate(10);
        return view('admin.informasi', compact('informasi'));
    }


    public function destroy($id)
    {
        $informasi = Informasi::findOrFail($id);
        $informasi->delete();

        return response()->json(['message' => 'Informasi berhasil dihapus.']);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'target_role' => 'nullable|string',
                'isi' => 'required|string',
            ]);

            Informasi::create([
                'target_role' => $request->target_role ?? null,
                'isi' => $request->isi,
            ]);

            return redirect()->route('admin.informasi')->with('success', 'Informasi berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.informasi')->with('error', 'Informasi gagal disimpan.');
        }
    }


}
