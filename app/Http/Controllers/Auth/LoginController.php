<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/beranda';
    protected function redirectTo()
    {
        $user = Auth::user();
        // dd($user->role);

        // switch (strtolower($user->role)) {
        //     case 'siswa':
        //         return '/siswa/beranda';
        //     case 'admin':
        //         return '/admin/beranda';
        //     default:
        //         // fallback: bisa logout atau redirect ke error page
        //         Auth::logout();
        //         return '/login';
        // }
        // dd(auth()->user(), request()->url());
        // if (!$user) {
        //     return '/login';
        // }

        // if ($user->role == 'siswa') {
        //     return '/siswa/beranda';
        // } elseif ($user->role == 'admin') {
        //     return '/admin/beranda';
        // }

        // // return '/login';
        // abort(403, "Role tidak dikenali.");

        // if (!$user) {
        //     return '/login';
        // }

        if ($user->role === 'siswa') {
            return '/siswa/beranda';
        }

        if ($user->role === 'admin') {
            return '/admin/beranda';
        }

        // abort(403, 'Role tidak dikenali');
        return '/login';

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
