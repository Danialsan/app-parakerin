<?php

namespace App\Http\Controllers\Siswa;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Models\PengaturanPkl;
use App\Models\PresensiSiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BerandaController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $hariIni = Carbon::today();

        $presensi = PresensiSiswa::where('siswa_id', $siswa->id)
            ->whereDate('waktu_masuk', $hariIni)
            ->first();

        $sudah_presensi_datang = $presensi !== null;
        $sudah_presensi_pulang = $presensi && $presensi->waktu_pulang !== null;

        $sudah_isi_jurnal = JurnalHarian::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $hariIni)
            ->exists();

        $nama_pembimbing = PengaturanPkl::where('siswa_id', $siswa->id)
            ->first()
            ->pembimbing->nama_pembimbing ?? '-';

        $nama_industri = PengaturanPkl::where('siswa_id', $siswa->id)
            ->first()
            ->dudi->nama_perusahaan ?? '-';

        $jurnal_belum_verifikasi = JurnalHarian::where('siswa_id', $siswa->id)
            ->where('verifikasi_pembimbing', false)
            ->count();


        return view('siswa.beranda', compact(
                'siswa',
                'sudah_presensi_datang',
                'sudah_presensi_pulang',
                'sudah_isi_jurnal',
                'nama_pembimbing',
                'nama_industri',
                'jurnal_belum_verifikasi'
        ));

    }

    public function pengaturan()
    {
        $user = auth()->user();
        return view('pengaturan', [
            'user_role' => 'siswa',
            'siswa' => $user->siswa->first(),
        ]);
    }

    public function updatePengaturan(Request $request)
    {
       try {
            $user = auth()->user();
            $request->validate([
                'password' => 'nullable|min:6',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'password.min' => 'Password minimal 6 karakter.',
                'foto.image' => 'File harus berupa gambar.',
                'foto.max' => 'Ukuran gambar maksimal 2MB.',
            ]);
            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            // Update foto jika diunggah
            if ($request->hasFile('foto')) {
                // Ambil pembimbingnya dari relasi
                $siswa = $user->siswa->first();

                if ($siswa) {
                    // Hapus foto lama jika ada
                    if ($siswa->foto && $siswa->foto !== 'default.jpg') {
                        Storage::disk('public')->delete('foto-siswa/' . $siswa->foto);
                    }

                    $fotoFile = $request->file('foto');
                    $fotoName = time() . '-' . Str::slug(pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fotoFile->getClientOriginalExtension();
                    $fotoFile->storeAs('foto-siswa', $fotoName, 'public');

                }
                $siswa->foto = $fotoName;
                $siswa->save();
            }

            $user->save();

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }

    }
}
