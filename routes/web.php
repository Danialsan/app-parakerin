<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Siswa\BerandaController;
use App\Http\Controllers\Siswa\DownloadPdfController;
use App\Http\Controllers\Siswa\Presensi2Controller;
use App\Http\Controllers\Siswa\PresensiController;
use Illuminate\Support\Facades\Route;

// Route::redirect('/', '/login');

Auth::routes();

Route::get('pw', function () {
    echo bcrypt('12345');
});


// Siswa
// presensi
Route::prefix('siswa')->middleware('isSiswa')->name('siswa.')->group(function () {
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::resource('/presensi', PresensiController::class)->only(['index', 'store', 'update']);
    Route::resource('/presensi-2', Presensi2Controller::class)->only(['index', 'store', 'update']);
    Route::get('download-pdf', [DownloadPdfController::class, 'index'])->name('download-pdf');
});

// Admin
// admin beranda
Route::prefix('admin')->middleware('isAdmin')->name('admin.')->group(function () {
    Route::get('beranda', [AdminController::class, 'index'])->name('beranda');
});
