<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot()
    {
        View::composer('*', function ($view) {
            $user = Auth::user();

            if ($user) {
                $folder = match ($user->role) {
                    'siswa' => 'foto-siswa',
                    'pembimbing' => 'foto-pembimbing',
                    // 'dudi' => 'foto-dudi',
                    default => 'foto-lain'
                };

                $foto = match ($user->role) {
                    'siswa' => $user->siswa?->foto,
                    'pembimbing' => $user->pembimbing?->foto,
                    'dudi' => $user->dudi?->foto,
                    default => null
                };

                $foto_profil = $foto ? asset("storage/{$folder}/{$foto}") : asset('assets/img/avatars/default.png');
            } else {
                $foto_profil = asset('assets/img/avatars/default.png');
            }

            $view->with('foto_profil', $foto_profil);
        });

        Paginator::useBootstrap();

    }

}
