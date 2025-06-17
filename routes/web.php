<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Siswa\BerandaController;
use App\Http\Controllers\Siswa\PresensiController;
use App\Http\Controllers\Admin\DudiAdminController;
use App\Http\Controllers\Admin\RekapJurnaController;
use App\Http\Controllers\Admin\SiswaAdminController;
use App\Http\Controllers\Dudi\JurnalSiswaController;
use App\Http\Controllers\Dudi\PresensiSiswaController;
use App\Http\Controllers\Siswa\JurnalHarianController;
use App\Http\Controllers\Admin\PengaturanPklController;
use App\Http\Controllers\Siswa\RekapPresensiController;
use App\Http\Controllers\Admin\RekapMonitoringController;
use App\Http\Controllers\Pembimbing\MonitoringController;
use App\Http\Controllers\Admin\CapaianPembelajaranController;
use App\Http\Controllers\Pembimbing\PembimbingJurnalController;
use App\Http\Controllers\Admin\PembimbingSekolahAdminController;
use App\Http\Controllers\Pembimbing\BerandaPembimbingController;
use App\Http\Controllers\Pembimbing\PresensiPembimbingController;
use App\Http\Controllers\Dudi\BerandaController as BerandaDudiController;

Route::redirect('/', '/login');

Auth::routes();

Route::get('pw', function () {
    echo bcrypt('12345');
});


// Siswa
Route::prefix('siswa')->middleware('isSiswa')->name('siswa.')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::resource('/presensi', PresensiController::class)->only(['index', 'store', 'update']);
    Route::resource('rekap-presensi', RekapPresensiController::class)->only(['index']);
    // Route::get('download-pdf', [DownloadPdfController::class, 'index'])->name('download-pdf');
    Route::get('presensi/download', [RekapPresensiController::class, 'download'])->name('presensi.download');
    Route::get('rekap-presensi/riwayat', [RekapPresensiController::class, 'riwayat'])->name('presensi.riwayat');

    // Jurnal
    Route::resource('jurnal', JurnalHarianController::class)->only(['index', 'store', 'destroy']);
    Route::get('jurnal/riwayat', [JurnalHarianController::class, 'riwayat'])->name('jurnal.riwayat');
    Route::get('jurnal/download', [JurnalHarianController::class, 'download'])->name('jurnal.download');
    // Route::get('cek-jurnal', [JurnalController::class, 'cekJurnalHariIni'])->name('jurnal.cek');

});

// Admin
Route::prefix('admin')->middleware('isAdmin')->name('admin.')->group(function () {
    Route::get('beranda', [AdminController::class, 'index'])->name('beranda');

    // Jurusan
    Route::resource('jurusan', JurusanController::class)->only(['index', 'store', 'update', 'destroy']);

    //capaian pembelajaran
    Route::resource('capaian-pembelajaran', CapaianPembelajaranController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('capaian-pembelajaran/import', [CapaianPembelajaranController::class, 'import'])->name('capaian-pembelajaran.import');

    // Dudi
    Route::resource('dudi-admin', DudiAdminController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('dudi-admin/import', [DudiAdminController::class, 'import'])->name('dudi-admin.import');

    // Pembimbing Sekolah
    Route::resource('pembimbing-sekolah-admin', PembimbingSekolahAdminController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('pembimbing-sekolah-admin/import', [PembimbingSekolahAdminController::class, 'import'])->name('pembimbing-sekolah-admin.import');
    Route::get('rekap-monitoring', [RekapMonitoringController::class, 'rekap'])->name('pembimbing-sekolah-admin.rekap-monitoring');
    Route::get('rekap-monitoring/download', [RekapMonitoringController::class, 'download'])->name('pembimbing-sekolah-admin.rekap-monitoring.download');

    // siswa
    Route::resource('siswa-admin', SiswaAdminController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('siswa-admin/import', [SiswaAdminController::class, 'import'])->name('siswa-admin.import');
    Route::get('siswa-admin/rekap-jurnal', [RekapJurnaController::class, 'index'])->name('siswa-admin.rekap-jurnal');
    Route::get('siswa-admin/rekap-jurnal/download', [RekapJurnaController::class, 'download'])->name('siswa-admin.rekap-jurnal.download');

    // Pengaturan PKL
    Route::resource('pengaturan-pkl', PengaturanPklController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('pengaturan-pkl/import', [PengaturanPklController::class, 'import'])->name('pengaturan-pkl.import');
    Route::get('siswa-by-jurusan', [PengaturanPklController::class, 'getSiswaByJurusan'])->name('siswa.by.jurusan');
    Route::get('pengaturan-pkl/download', [PengaturanPklController::class, 'download'])->name('pengaturan-pkl.download');

});

// Dudi
Route::prefix('dudi')->middleware('isDudi')->name('dudi.')->group(function () {
    Route::get('beranda', [BerandaDudiController::class, 'index'])->name('index');
    Route::prefix('siswa')->group(function () {
        Route::get('presensi', [PresensiSiswaController::class, 'index'])->name('presensi');
        Route::get('jurnal', [JurnalSiswaController::class, 'index'])->name('jurnal');
    });
});

Route::prefix('pembimbing')->middleware('isPembimbingSekolah')->name('pembimbing.')->group(function () {
    Route::get('beranda', [BerandaPembimbingController::class, 'index'])->name('index');
    Route::resource('kunjungan', MonitoringController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('monitoring/siswa', [MonitoringController::class, 'getSiswaByDudi'])->name('siswa.by.dudi');
    Route::get('monitoring/keperluan-used', [MonitoringController::class, 'getKeperluanUsed'])->name('keperluan.used');
    Route::get('kunjungan/riwayat', [MonitoringController::class, 'riwayat'])->name('keperluan.riwayat');
    Route::get('keperluan/unduh/{id}', [MonitoringController::class, 'unduh'])->name('keperluan.unduh');
    Route::get('verifikasi-jurnal', [PembimbingJurnalController::class, 'index'])->name('verifikasi-jurnal.index');
    Route::post('verifikasi-jurnal/{jurnal}', [PembimbingJurnalController::class, 'verifikasi'])->name('verifikasi-jurnal.verifikasi');
    Route::get('pembimbing/presensi', [PresensiPembimbingController::class, 'presensiSiswaBimbingan'])->name('presensi.index');
    Route::post('verifikasi-jurnal/{id}/batal', [PembimbingJurnalController::class, 'batal'])
        ->name('verifikasi-jurnal.batal');

});
