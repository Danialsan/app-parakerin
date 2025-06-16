<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dudi;
use App\Models\Siswa;
use App\Models\Jurusan;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PengaturanPkl;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PembimbingSekolah;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengaturanPklController extends Controller
{
    public function index(Request $request)
    {
        $query = PengaturanPkl::with(['siswa', 'jurusan', 'dudi', 'pembimbing']);

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($request->filled('dudi_id')) {
            $query->where('dudi_id', $request->dudi_id);
        }

        $pengaturanPkl = $query->paginate(10)->withQueryString();

        $jurusan = Jurusan::all();
        $dudi = Dudi::all();
        $pembimbing = PembimbingSekolah::all();
        $siswa = Siswa::all();

        return view('admin.pengaturan-pkl.index', compact(
            'pengaturanPkl', 'jurusan', 'dudi', 'pembimbing', 'siswa'
        ));
    }

    public function getSiswaByJurusan(Request $request)
    {
        $siswa = Siswa::with('jurusan')
            ->where('jurusan_id', $request->jurusan_id)
            ->whereNull('dudi_id') // belum punya tempat PKL
            ->select('id', 'nama', 'jurusan_id') // jurusan_id tetap perlu agar relasi jalan
            ->get();

        $formatted = $siswa->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'jurusan' => $item->jurusan->nama_jurusan ?? '-'
            ];
        });

        return response()->json($formatted);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'dudi_id' => 'required|exists:dudi,id',
                'pembimbing_sekolah_id' => 'required|exists:pembimbing_sekolah,id',
                'jurusan_id' => 'required|exists:jurusan,id',
                'siswa_id' => 'required|array|min:1',
                'siswa_id.*' => 'exists:siswa,id',
                'mulai' => 'required|date',
                'selesai' => 'required|date|after_or_equal:mulai',
            ]);

            foreach ($request->siswa_id as $siswaId) {
                // Simpan data ke tabel pengaturan_pkl
                PengaturanPkl::create([
                    'siswa_id' => $siswaId,
                    'dudi_id' => $request->dudi_id,
                    'pembimbing_sekolah_id' => $request->pembimbing_sekolah_id,
                    'jurusan_id' => $request->jurusan_id,
                    'mulai' => $request->mulai,
                    'selesai' => $request->selesai,
                ]);

                // Update data dudi dan pembimbing di tabel siswa
                Siswa::where('id', $siswaId)->update([
                    'dudi_id' => $request->dudi_id,
                    'pembimbing_sekolah_id' => $request->pembimbing_sekolah_id,
                ]);

            }

            return redirect()->route('admin.pengaturan-pkl.index')
                ->with('success', 'Pengaturan PKL berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dudi_id' => 'required|uuid',
            'pembimbing_sekolah_id' => 'required|uuid',
            'jurusan_id' => 'required|exists:jurusan,id',
            'siswa_id' => 'required|uuid',
            'mulai' => 'required|date',
            'selesai' => 'required|date|after_or_equal:mulai',
        ]);

        $pengaturan = PengaturanPkl::findOrFail($id);
        $pengaturan->update([
            'siswa_id' => $request->siswa_id,
            'jurusan_id' => $request->jurusan_id,
            'dudi_id' => $request->dudi_id,
            'pembimbing_sekolah_id' => $request->pembimbing_sekolah_id,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
        ]);

        // Update ke tabel siswa juga
        Siswa::where('id', $request->siswa_id)->update([
            'dudi_id' => $request->dudi_id,
            'pembimbing_sekolah_id' => $request->pembimbing_sekolah_id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function destroy(Request $request)
    {

        try {
            $pengaturanPkl = PengaturanPkl::findOrFail($request->id);
            $siswa = Siswa::findOrFail($pengaturanPkl->siswa_id);

            //hapus pengaturan pkl
            $pengaturanPkl->delete();

            //update siswa
            $siswa->update([
                'dudi_id' => null,
                'pembimbing_sekolah_id' => null,
            ]);

            return back()->with('success', 'Pengaturan PKL berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function download(Request $request)
    {
        $query = PengaturanPkl::with(['siswa', 'jurusan', 'dudi', 'pembimbing']);

        if ($request->jurusan_id) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        if ($request->dudi_id) {
            $query->where('dudi_id', $request->dudi_id);
        }

        $data = $query->get();
        // dd($data);

        // Pastikan $data dikirim dalam bentuk array atau koleksi
        if (!is_iterable($data)) {
            $data = collect(); // fallback ke koleksi kosong
        }

        $pdf = Pdf::loadView('admin.pengaturan-pkl.pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->download('pengaturan-pkl' . date('Y-m-d') . '.pdf');
    }


}
