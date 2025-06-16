<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Models\PresensiSiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RekapPresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $cari = $request->query('cari');


        // request
        // $halaman = $request->input('halaman', 2);
        // Ambil user
        $user = Auth::user();

        // Ambil siswa
        $siswa = $user->siswa;

        if (!empty($cari)) {
            $rekap_presensi = PresensiSiswa::where('siswa_id', $siswa->id)->where('absensi', 'LIKE', '%' . $cari . '%')->paginate(2);
        } else {
            $rekap_presensi = PresensiSiswa::where('siswa_id', $siswa->id)->paginate(2);
        }


        return view('siswa.rekap-presensi', compact('rekap_presensi', 'cari'));
    }

    public function download(Request $request)
    {
        $id = auth()->user()->siswa->id;

        $siswa = Siswa::with([
            'pengaturanPkl.pembimbing'
        ])->findOrFail($id);

        $presensi = $siswa->presensiSiswa()
            ->when($request->filled('tanggal_awal') && $request->filled('tanggal_akhir'), function ($query) use ($request) {
                $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
                $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();
                $query->whereBetween('waktu_masuk', [$tanggal_awal, $tanggal_akhir]);
            })
            ->orderBy('waktu_masuk', 'asc')
            ->get();

        if ($presensi->isEmpty()) {
            return back()->with('error', 'Tidak ada data presensi yang tersedia dalam rentang tanggal tersebut.');
        }

        $pdf = Pdf::loadView('siswa.pdf.presensi-pdf', [
            'siswa' => $siswa,
            'presensi' => $presensi,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir
        ])->setPaper('A4', 'portrait');

        return $pdf->download('riwayat-presensi-' . $siswa->nama . '-' . $siswa->kelas . '-' . now()->format('Y-m-d') . '.pdf');
    }


    public function riwayat(Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();

        $rekap_presensi = PresensiSiswa::where('siswa_id', $siswa->id)
            ->when($request->filled('tanggal_awal') && $request->filled('tanggal_akhir'), function ($query) use ($request) {
                $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
                $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();
                $query->whereBetween('waktu_masuk', [$tanggal_awal, $tanggal_akhir]);
            })
            ->orderBy('waktu_masuk', 'asc')
            ->paginate(10);

        return view('siswa.rekap-presensi', compact('rekap_presensi'));
    }

}
