<?php

namespace App\Http\Controllers\Pembimbing;

use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Models\PengaturanPkl;
use App\Http\Controllers\Controller;

class PembimbingJurnalController extends Controller
{
    public function index()
    {
        $pembimbing = auth()->user()->pembimbingSekolah->first();
        $pembimbingId = $pembimbing?->id;

        // dd($pembimbingId);
        // Ambil siswa yang dibimbing
        $siswaIds = PengaturanPkl::where('pembimbing_sekolah_id', $pembimbingId)
            ->pluck('siswa_id');

            // dd($siswaIds);

        $jurnals = JurnalHarian::with(['siswa'])
            ->whereIn('siswa_id', $siswaIds)
            ->latest('tanggal')
            ->paginate(10);

        return view('pembimbing.verifikasi-jurnal', compact('jurnals'));
    }

    public function verifikasi(Request $request, JurnalHarian $jurnal)
    {
        $pembimbingId = auth()->user()->pembimbingSekolah->first()?->id;

        $dibimbing = PengaturanPkl::where('pembimbing_sekolah_id', $pembimbingId)
            ->where('siswa_id', $jurnal->siswa_id)
            ->exists();

        if (!$dibimbing) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $jurnal->verifikasi_pembimbing = true;
        $jurnal->catatan_pembimbing = $request->catatan;
        $jurnal->save();

        return response()->json([
            'success' => true,
            'id' => $jurnal->id
        ]);

    }

    public function batal($id)
    {
        $jurnal = JurnalHarian::findOrFail($id);
        $pembimbingId = auth()->user()->pembimbingSekolah->first()?->id;

        $dibimbing = PengaturanPkl::where('pembimbing_sekolah_id', $pembimbingId)
            ->where('siswa_id', $jurnal->siswa_id)
            ->exists();

        if (!$dibimbing) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $jurnal->verifikasi_pembimbing = false;
        $jurnal->catatan_pembimbing = null;
        $jurnal->save();

        return response()->json(['success' => true]);
    }


}
