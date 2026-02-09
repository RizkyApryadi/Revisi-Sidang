<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;

class PendetaController extends Controller
{
    /**
     * Pendeta dashboard
     */
    public function index()
    {
        return view('pages.pendeta.dashboard');
    }
}
