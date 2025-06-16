<?php

namespace App\Http\Controllers\Dudi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        return view('dudi.beranda');
    }
}
