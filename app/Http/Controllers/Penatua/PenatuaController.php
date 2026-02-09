<?php

namespace App\Http\Controllers\Penatua;

use App\Http\Controllers\Controller;

class PenatuaController extends Controller
{
    /**
     * Penatua dashboard
     */
    public function index()
    {
        return view('pages.penatua.dashboard');
    }
}
