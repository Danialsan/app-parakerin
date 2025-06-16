<?php

namespace App\Http\Controllers\Siswa;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Models\PresensiSiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CapaianPembelajaran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JurnalHarianController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->with('jurusan')->firstOrFail();
        // Ambil capaian pembelajaran berdasarkan jurusan siswa
        $capaian = CapaianPembelajaran::where('jurusan_id', $siswa->jurusan_id)->get();

        // Cek apakah sudah mengisi jurnal hari ini
        $sudahIsiHariIni = JurnalHarian::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();

        if ($sudahIsiHariIni) {
            return redirect()->back()->with('error', 'Anda sudah mengisi jurnal hari ini. Silahkan Hapus Jika Ingin Memperbarui.');
        }

        return view('siswa.jurnal', compact('capaian', 'siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'capaian_pembelajaran_id' => 'required|exists:capaian_pembelajaran,id',
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();

        // Cek presensi hari itu
        $presensi = PresensiSiswa::where('siswa_id', $siswa->id)
            ->whereDate('created_at', $request->tanggal)
            ->first();

        if (!$presensi) {
            return redirect()->back()->with('error', 'Anda belum melakukan presensi pada tanggal tersebut.');
        }

        // Cek jika sudah ada jurnal hari itu
        $jurnalExists = JurnalHarian::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($jurnalExists) {
            return redirect()->back()->with('error', 'Anda sudah mengisi jurnal untuk tanggal tersebut.');
        }

        // Simpan foto (jika ada)
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-jurnal', 'public');
        }

        // Simpan jurnal
        JurnalHarian::create([
            'siswa_id' => $siswa->id,
            'tanggal' => $request->tanggal,
            'capaian_pembelajaran_id' => $request->capaian_pembelajaran_id,
            'kegiatan' => $request->kegiatan,
            'foto' => $fotoPath,
            'verifikasi_pembimbing' => false,
        ]);

        return redirect()->route('siswa.jurnal.riwayat')->with('success', 'Jurnal berhasil disimpan dan menunggu verifikasi.');
    }

    public function riwayat(Request $request)
    {
        $user = auth()->user(); // Ambil siswa dari user yang login
        $siswa = Siswa::where('user_id', $user->id)->with('jurusan')->firstOrFail();

        $query = $siswa->jurnalHarian();

        // Filter berdasarkan tanggal jika diisi
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $jurnals = $query->with('capaianPembelajaran')->latest()->paginate(10);

        return view('siswa.riwayat-jurnal', compact('jurnals'));
    }
    public function download(Request $request)
    {
        $id = auth()->user()->siswa->id;

        $siswa = Siswa::with([
            'jurusan',
            'pengaturanPkl.pembimbing' // penting untuk akses pembimbing
        ])->findOrFail($id);

        // Ambil semua jurnal dalam rentang tanggal
        $allJurnal = $siswa->jurnalHarian()
            ->when($request->filled('tanggal_awal') && $request->filled('tanggal_akhir'), function ($query) use ($request) {
                $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
            })
            ->orderBy('tanggal')
            ->get();

        // Cek jika ada jurnal yang belum diverifikasi
        if ($allJurnal->contains('verifikasi_pembimbing', false)) {
            return back()->with('error', 'Tidak bisa mengunduh. Masih ada jurnal yang belum diverifikasi pembimbing.');
        }

        // Ambil ulang hanya yang sudah diverifikasi (untuk jaga-jaga)
        $jurnals = $allJurnal->where('verifikasi_pembimbing', true);

        // Jika kosong (semua diverifikasi tapi tidak ada data), beri pesan
        if ($jurnals->isEmpty()) {
            return back()->with('error', 'Tidak ada jurnal yang tersedia dalam rentang tanggal tersebut.');
        }

        $pdf = Pdf::loadView('siswa.pdf.jurnal-pdf', [
            'siswa' => $siswa,
            'jurnals' => $jurnals,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir
        ]);

        return $pdf->download('riwayat-jurnal-' . $siswa->nama . '-' . $siswa->kelas . '-' . now()->format('Y-m-d') . '.pdf');
    }


    public function cekJurnalHariIni()
    {
        $siswaId = auth()->user()->siswa->id;
        $hariIni = now()->toDateString();

        $sudahIsiJurnal = JurnalHarian::where('siswa_id', $siswaId)
            ->where('tanggal', $hariIni)
            ->exists();

        return response()->json([
            'sudah_isi_jurnal' => $sudahIsiJurnal,
        ]);
    }

    public function destroy($id)
    {
        $jurnal = JurnalHarian::findOrFail($id);

        // Pastikan hanya jurnal milik siswa yang sedang login yang bisa dihapus
        if ($jurnal->siswa_id != auth()->user()->siswa->id) {
            return response()->json(['message' => 'Tidak diizinkan!'], 403);
        }

        // Cek apakah jurnal sudah diverifikasi
        if ($jurnal->verifikasi_pembimbing) {
            return response()->json([
                'message' => 'Jurnal sudah diverifikasi dan tidak bisa dihapus.'
            ], 403);
        }

        // Hapus file foto jika ada
        if ($jurnal->foto && Storage::disk('public')->exists($jurnal->foto)) {
            Storage::disk('public')->delete($jurnal->foto);
        }

        $jurnal->delete();

        return response()->json(['message' => 'Jurnal berhasil dihapus!']);
    }


}
