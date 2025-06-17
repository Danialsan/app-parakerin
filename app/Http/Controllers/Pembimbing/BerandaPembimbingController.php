<?php

namespace App\Http\Controllers\Pembimbing;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\JurnalHarian;
use Illuminate\Http\Request;
use App\Models\MonitoringPkl;
use App\Models\PengaturanPkl;
use App\Models\PresensiSiswa;
use App\Http\Controllers\Controller;

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
}
