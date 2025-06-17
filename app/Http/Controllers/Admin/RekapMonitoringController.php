<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\MonitoringPkl;
use App\Models\MonitoringPklDetail;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;


class RekapMonitoringController extends Controller
{

    public function rekap(Request $request)
    {
        $jurusan = Jurusan::all();

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
        ->paginate(10);

        return view('admin.pembimbing-sekolah-admin.rekap-monitoring', compact('monitoring', 'jurusan'));
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
