<?php

namespace App\Http\Controllers\Pembimbing;

use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Models\PengaturanPkl;
use App\Http\Controllers\Controller;

class PembimbingJurnalController extends Controller
{
    public function index(Request $request)
    {
        $pembimbingId = auth()->user()->pembimbingSekolah->first()?->id;

        // Ambil semua jurnal siswa yang dibimbing
        $query = JurnalHarian::with(['siswa'])
            ->whereIn('siswa_id', function ($q) use ($pembimbingId) {
                $q->select('siswa_id')
                  ->from('pengaturan_pkl')
                  ->where('pembimbing_sekolah_id', $pembimbingId);
            });

        // Filter berdasarkan status verifikasi
        if ($request->filled('status')) {
            if ($request->status === 'terverifikasi') {
                $query->where('verifikasi_pembimbing', true);
            } elseif ($request->status === 'belum') {
                $query->where('verifikasi_pembimbing', false);
            }
        }

        $jurnals = $query->latest('tanggal')->paginate(10)->withQueryString();

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
