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


        if ($user->role === 'siswa') {
            return '/siswa/beranda';
        }

        if ($user->role === 'admin') {
            return '/admin/beranda';
        }
        if ($user->role === 'dudi') {
            return '/dudi/beranda';
        }
        if ($user->role === 'pembimbing') {
            return '/pembimbing_sekolah/beranda';
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
