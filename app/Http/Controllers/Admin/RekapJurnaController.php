<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jurusan;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapJurnaController extends Controller
{
    public function index(Request $request)
    {
        $query = JurnalHarian::with([
            'siswa.jurusan',
            'siswa.pengaturanPkl.pembimbing',
            'capaianPembelajaran'
        ]);


        // Filter berdasarkan jurusan
        if ($request->filled('jurusan_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        // Filter berdasarkan status verifikasi
        if ($request->filled('status')) {
            if ($request->status === 'terverifikasi') {
                $query->where('verifikasi_pembimbing', true);
            } elseif ($request->status === 'belum') {
                $query->where('verifikasi_pembimbing', false);
            }
        }

        $jurnal = $query->latest('tanggal')->paginate(10)->withQueryString();
        $jurusan = Jurusan::all();

        return view('admin.siswa-admin.rekap-jurnal', compact('jurnal', 'jurusan'));
    }


    public function download(Request $request)
    {
        $query = JurnalHarian::with([
            'siswa.jurusan',
            'siswa.dudi',
            'siswa.pengaturanPkl.pembimbing'
        ]);

        if ($request->filled('jurusan_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }

        if ($request->status === 'terverifikasi') {
            $query->where('verifikasi_pembimbing', true);
        } elseif ($request->status === 'belum') {
            $query->where('verifikasi_pembimbing', false);
        }

        $jurnal = $query->orderBy('tanggal')->get();

        $pdf = Pdf::loadView('admin.siswa-admin.rekap-jurnal-pdf', compact('jurnal'))->setPaper('A4', 'landscape');
        return $pdf->download('rekap-jurnal-siswa' . now()->format('Y-m-d') . '.pdf');
    }

}
