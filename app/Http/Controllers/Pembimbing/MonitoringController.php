<?php

namespace App\Http\Controllers\Pembimbing;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MonitoringPkl;
use App\Models\PengaturanPkl;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MonitoringController extends Controller
{
    public function index()
    {
        $pembimbing = auth()->user()->pembimbingSekolah->first();

        if (!$pembimbing) {
            abort(403, 'Pembimbing sekolah tidak ditemukan');
        }


        // Ambil DUDI yang dibimbing oleh pembimbing ini
        $dudi = PengaturanPkl::with('dudi')
            ->where('pembimbing_sekolah_id', $pembimbing->id)
            ->get()
            ->pluck('dudi')
            ->unique('id');
        return view('pembimbing.monitoring', compact('dudi'));
    }
    public function getSiswaByDudi(Request $request)
    {
        $pembimbingIds = auth()->user()->pembimbingSekolah->pluck('id'); // ambil semua id-nya

        $siswa = PengaturanPkl::with('siswa')
            ->where('dudi_id', $request->dudi_id)
            ->whereIn('pembimbing_sekolah_id', $pembimbingIds)
            ->get()
            ->pluck('siswa');

        return response()->json($siswa);
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $pembimbing = auth()->user()->pembimbingSekolah->first(); // Jika relasi hasMany

        if (!$pembimbing) {
            return back()->with('error', 'Data pembimbing tidak ditemukan.');
        }

        // ek apakah sudah ada monitoring dengan keperluan yang sama
        $sudahAda = MonitoringPkl::where('pembimbing_sekolah_id', $pembimbing->id)
            ->where('dudi_id', $request->dudi_id)
            ->where('keperluan', $request->keperluan)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Anda sudah melaksanakan keperluan tersebut untuk DUDI ini.');
        }

        // Validasi request
        $request->validate([
            'keperluan' => 'required|in:pengantaran,monitoring 1,monitoring 2,monitoring 3,penjemputan',
            'dudi_id' => 'required|exists:dudi,id',
            'foto' => 'nullable|image|max:2048',
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'exists:siswa,id',
            'kehadiran.*' => 'required|numeric|min:0|max:100',
            'sikap.*' => 'required|numeric|min:0|max:100',
            'progres.*' => 'required|numeric|min:0|max:100',
            'kesesuaian.*' => 'required|numeric|min:0|max:100',
            'catatan.*' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto-monitoring', 'public');
        } else {
            $path = null;
        }

        // Simpan data monitoring utama
        $monitoring = MonitoringPkl::create([
            'id' => (string) Str::uuid(),
            'pembimbing_sekolah_id' => $pembimbing->id,
            'dudi_id' => $request->dudi_id,
            'keperluan' => $request->keperluan,
            'foto' => $path,
        ]);

        // Simpan detail monitoring siswa
        foreach ($request->siswa_id as $i => $siswa_id) {
            $monitoring->detail()->create([
                'siswa_id' => $siswa_id,
                'kehadiran' => $request->kehadiran[$i],
                'sikap' => $request->sikap[$i],
                'progres' => $request->progres[$i],
                'kesesuaian' => $request->kesesuaian[$i],
                'catatan' => $request->catatan[$i] ?? null,
            ]);
        }

        return redirect()->route('pembimbing.kunjungan.index')->with('success', 'Data berhasil disimpan.');
    }
    public function getKeperluanUsed(Request $request)
    {
        $pembimbing = auth()->user()->pembimbingSekolah()->first();


        $keperluan = MonitoringPkl::where('pembimbing_sekolah_id', $pembimbing->id)
            ->where('dudi_id', $request->dudi_id)
            ->pluck('keperluan');

        return response()->json($keperluan);
    }
    public function riwayat()
    {
        $pembimbing = auth()->user()->pembimbingSekolah()->first();

        if (!$pembimbing) {
            return back()->with('error', 'Data pembimbing tidak ditemukan.');
        }

        $monitoring = MonitoringPkl::with(['dudi', 'detail.siswa'])
            ->where('pembimbing_sekolah_id', $pembimbing->id)
            ->latest()
            ->get();

        return view('pembimbing.riwayat', compact('monitoring'));
    }

    public function destroy($id)
    {
        $monitoring = MonitoringPkl::findOrFail($id);

        if ($monitoring->pembimbing_sekolah_id !== auth()->user()->pembimbingSekolah()->first()->id) {
            abort(403, 'Tidak diizinkan');
        }

        // Hapus file dari storage/app/foto-monitoring/
        if ($monitoring->foto && Storage::exists($monitoring->foto)) {
            Storage::delete($monitoring->foto);
        }

        $monitoring->delete();

        return redirect()->back()->with('success', 'Riwayat monitoring dan dokumentasi berhasil dihapus.');
    }



    public function unduh($id)
    {
        $monitoring = MonitoringPkl::with(['dudi', 'detail.siswa'])->findOrFail($id);
        $pdf = Pdf::loadView('pembimbing.pdf', compact('monitoring'))->setPaper('A4', 'portrait');

        return $pdf->download('laporan-kunjungan-' . $monitoring->pembimbing->nama_pembimbing . '-' . $monitoring->dudi->nama_perusahaan . '-'. $monitoring->keperluan .'.pdf');
    }

}
