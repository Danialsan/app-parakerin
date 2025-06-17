<?php
namespace App\Providers;


use Carbon\Carbon;
use App\Models\Informasi;
use App\Models\PresensiSiswa;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

        public function boot()
        {
            Carbon::setLocale('id');
            View::composer('*', function ($view) {
                $user = Auth::user();
                $defaultFoto = asset('assets/img/avatars/default.png');

                // Default variabel
                $foto_profil = $defaultFoto;
                $sudah_presensi_hari_ini = false;

                if ($user) {
                    // Mapping folder dan relasi berdasarkan role
                    $folderMap = [
                        'siswa' => 'foto-siswa',
                        'pembimbing' => 'foto-pembimbing', // ini adalah pembimbing sekolah
                        'dudi' => 'foto-dudi',
                    ];

                    $foto = match ($user->role) {
                        'siswa' => $user->siswa->first()?->foto,
                        'pembimbing' => $user->pembimbingSekolah->first()?->foto,
                        'dudi' => $user->dudi?->foto,
                        default => null,
                    };

                    $folder = $folderMap[$user->role] ?? 'foto-lain';
                    $foto_profil = $foto ? asset("storage/{$folder}/{$foto}") : $defaultFoto;

                    // Cek presensi hanya jika siswa
                    if ($user->role === 'siswa' && $user->siswa) {
                        $sudah_presensi_hari_ini = PresensiSiswa::where('siswa_id', $user->siswa->id)
                            ->whereDate('waktu_masuk', Carbon::today())
                            ->exists();
                    }
                }

                // Share ke semua view
                $view->with([
                    'foto_profil' => $foto_profil,
                    'sudah_presensi_hari_ini' => $sudah_presensi_hari_ini
                ]);

                $user = Auth::user();
                $role = $user->role ?? null;
                $informasi =Informasi::where(function ($q) use ($role) {
                    $q->whereNull('target_role')
                      ->orWhere('target_role', $role);
                })->latest()->get();

                $view->with('informasi_list', $informasi);
            });

            Paginator::useBootstrap();
        }

    }
