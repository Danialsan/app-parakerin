<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerandaPembimbingController extends Controller
{
    public function index()
    {
        return view('pembimbing.beranda');
    }
}
