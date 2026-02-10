<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use App\Models\Keluarga;
use App\Models\Wijk;

class PendetaJemaatController extends Controller
{
    /**
     * Display a listing of jemaat for pendeta area.
     */
    public function index(Request $request)
    {
        // Load keluarga with their jemaats and wijk to render the admin-style keluarga/jemaat view
        $keluargas = Keluarga::with(['jemaats', 'wijk'])
            ->withCount('jemaats')
            ->orderBy('nomor_registrasi')
            ->get();

        // pendingCount kept for compatibility with admin view (set to 0 if not applicable)
        $pendingCount = 0;

        return view('pages.pendeta.jemaat.index', compact('keluargas', 'pendingCount'));
    }

    /**
     * Show the create form for jemaat in pendeta area.
     */
    public function create()
    {
        $wijks = Wijk::orderBy('nama_wijk', 'asc')->get();
        return view('pages.pendeta.jemaat.create', compact('wijks'));
    }
}
