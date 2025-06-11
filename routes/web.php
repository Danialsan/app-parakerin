<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Siswa\BerandaController;
use App\Http\Controllers\Siswa\PresensiController;
use App\Http\Controllers\Siswa\Presensi2Controller;
use App\Http\Controllers\Siswa\DownloadPdfController;
use App\Http\Controllers\Admin\CapaianPembelajaranController;


// Route::redirect('/', '/login');

Auth::routes();

Route::get('pw', function () {
    echo bcrypt('12345');
});


// Siswa
Route::prefix('siswa')->middleware('isSiswa')->name('siswa.')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::resource('/presensi', Presensi2Controller::class)->only(['index', 'store', 'update']);
    Route::resource('rekap-presensi', RekapPresensiController::class)->only(['index']);
    Route::get('download-pdf', [DownloadPdfController::class, 'index'])->name('download-pdf');
});

// Admin
Route::prefix('admin')->middleware('isAdmin')->name('admin.')->group(function () {
    Route::get('beranda', [AdminController::class, 'index'])->name('beranda');

    // Jurusan
    Route::resource('/jurusan', JurusanController::class)->only(['index', 'store', 'update', 'destroy']);

    //capaian pembelajaran
    Route::resource('/capaian-pembelajaran', CapaianPembelajaranController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/capaian-pembelajaran/import', [CapaianPembelajaranController::class, 'import'])
    ->name('admin.capaian-pembelajaran.import');

});

// Dudi
Route::prefix('dudi')->middleware('isDudi')->name('dudi.')->group(function () {
    Route::get('beranda', [DudiController::class, 'index'])->name('index');
});
