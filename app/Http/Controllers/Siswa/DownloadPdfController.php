<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadPdfController extends Controller
{
    public function index()
    {
        return view('siswa.download-pdf');
    }

}
