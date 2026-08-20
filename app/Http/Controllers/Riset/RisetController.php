<?php

namespace App\Http\Controllers\Riset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RisetController extends Controller
{
    public function __construct()
    {
        view()->share('activeMenu', 'riset');
    }

    public function dataMetodeAplikasi()
    {
        return view('riset.data-metode-aplikasi');
    }

    public function instruksiPenyaringan()
    {
        return view('riset.instruksi-penyaringan');
    }

    public function jenisSaringan()
    {
        return view('riset.jenis-saringan');
    }
}
