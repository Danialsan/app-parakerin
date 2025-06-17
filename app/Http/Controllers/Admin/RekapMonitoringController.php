<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\MonitoringPkl;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MonitoringPklDetail;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;


class RekapMonitoringController extends Controller
{

    public function rekap(Request $request)
    {
        $jurusan = Jurusan::all();

        // Ambil semua monitoring (misalnya limit 100 data agar ringan)
        $monitoring = MonitoringPklDetail::with([
            'monitoring.pembimbing',
            'siswa.jurusan',
            'siswa.dudi',
        ])
        ->when($request->jurusan_id, function ($query) use ($request) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        })
        ->latest()
        ->get(); // pakai get() dulu agar bisa diolah sebagai collection

        // Filter agar hanya 1 data per DUDI (berdasarkan dudi_id siswa)
        $filtered = $monitoring->unique(function ($item) {
            return $item->siswa->dudi->id ?? null;
        });

        // Hitung jumlah siswa
        $filtered = $monitoring->unique(function ($item) {
            return $item->siswa->dudi->id ?? null;
        })->map(function ($item) {
            $dudiId = $item->siswa->dudi_id;
            $jumlahSiswa = \App\Models\Siswa::where('dudi_id', $dudiId)->count();
            $item->jumlah_siswa = $jumlahSiswa;
            return $item;
        });


        // pagination setelah filter:
        $page = request()->get('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );


        return view('admin.pembimbing-sekolah-admin.rekap-monitoring', [
            'monitoring' => $paginated,
            'jurusan' => $jurusan
        ]);
    }


    public function download(Request $request)
    {
        $monitoring = MonitoringPklDetail::with([
            'monitoring.pembimbing',
            'siswa.jurusan',
            'siswa.dudi',
        ])
        ->when($request->jurusan_id, function ($query) use ($request) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        })
        ->latest()->get();

        $pdf = Pdf::loadView('admin.pembimbing-sekolah-admin.rekap-monitoring-pdf', compact('monitoring'))->setPaper('A4', 'landscape');;

        return $pdf->download('rekap-kunjungan-pembimbing' . now()->format('Y-m-d') . '.pdf');
    }


}
