<?php

namespace App\Http\Controllers\Pembimbing;

use Carbon\Carbon;
use App\Models\Siswa;
use Illuminate\Support\Str;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Models\MonitoringPkl;
use App\Models\PengaturanPkl;
use App\Models\PresensiSiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BerandaPembimbingController extends Controller
{
    public function index()
    {
        // Asumsi user hanya punya 1 pembimbing terkait
        $pembimbingId = auth()->user()->pembimbingSekolah->pluck('id');

        // Ambil ID semua siswa dari pengaturan PKL yang dibimbing pembimbing ini
        $siswaIds = PengaturanPkl::where('pembimbing_sekolah_id', $pembimbingId)
            ->with('siswa')
            ->get()
            ->pluck('siswa.id')
            ->filter(); // Hindari null jika ada data tidak lengkap

        $jumlah_siswa = $siswaIds->count();

        $hariIni = Carbon::today();

        $jumlah_presensi_hari_ini = PresensiSiswa::whereIn('siswa_id', $siswaIds)
            ->whereDate('waktu_masuk', $hariIni)
            ->count();

        $jumlah_jurnal_hari_ini = JurnalHarian::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', $hariIni)
            ->count();

        $jumlah_jurnal_perlu_verifikasi = JurnalHarian::whereIn('siswa_id', $siswaIds)
            ->where('verifikasi_pembimbing', false)
            ->count();

        $jumlah_kunjungan = MonitoringPkl::whereIn('pembimbing_sekolah_id', $pembimbingId)
            ->count();


        return view('pembimbing.beranda', compact(
            'jumlah_siswa',
            'jumlah_presensi_hari_ini',
            'jumlah_jurnal_hari_ini',
            'jumlah_jurnal_perlu_verifikasi',
            'jumlah_kunjungan'

        ));
    }

    public function pengaturan()
    {
        $user = auth()->user();
        return view('pengaturan', [
            'user_role' => 'pembimbing',
            'pembimbing' => $user->pembimbingSekolah->first(),
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
                $pembimbing = $user->pembimbingSekolah->first();

                if ($pembimbing) {
                    $fotoName = $pembimbing->foto;

                    if ($request->hasFile('foto')) {
                        if ($pembimbing->foto && $pembimbing->foto !== 'default.jpg') {
                            Storage::disk('public')->delete('foto-pembimbing/' . $pembimbing->foto);
                        }


                        $fotoFile = $request->file('foto');
                        $fotoName = time() . '-' . Str::slug(pathinfo($fotoFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $fotoFile->getClientOriginalExtension();
                        $fotoFile->storeAs('foto-pembimbing', $fotoName, 'public');

                    }

                    // Simpan foto baru
                    $pembimbing->foto = $fotoName;
                    $pembimbing->save();
                }
            }

            $user->save();

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());

        }
    }


}
