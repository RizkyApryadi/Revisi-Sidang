<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Katekisasi;
use App\Models\Pendeta;
use App\Models\PendaftaranSidi;

class KatekiController extends Controller
{
    /**
     * Show create form for admin to add a new Katekisasi.
     */
    public function create()
    {
        try {
            $pendetas = Pendeta::orderBy('nama_lengkap')->get();
        } catch (\Throwable $e) {
            logger()->error('KatekiController@create: '.$e->getMessage());
            return view('pages.admin.MainMenu.katekisasi.create', ['pendetas' => collect(), 'errorMessage' => $e->getMessage()]);
        }

        return view('pages.admin.MainMenu.katekisasi.create', compact('pendetas'));
    }

    /**
     * Display a listing of Katekisasi for admin.
     */
    public function index()
    {
        $katekisasis = Katekisasi::with(['pendeta', 'pendaftaranSidis'])->orderBy('tanggal_mulai', 'desc')->get();

        // Pending pendaftar that need admin approval
        $pendingCount = 0;
        $pendingRecent = collect();
        try {
            $pendingQuery = PendaftaranSidi::where('status_pengajuan', 'pending')->orderBy('created_at', 'desc');
            $pendingCount = $pendingQuery->count();
            $pendingRecent = $pendingQuery->limit(5)->get();
        } catch (\Throwable $e) {
            logger()->error('KatekiController@index fetching pending pendaftaran_sidis: '.$e->getMessage());
        }

        return view('pages.admin.MainMenu.katekisasi.index', compact('katekisasis', 'pendingCount', 'pendingRecent'));
    }

    /**
     * Store new Katekisasi using table `katekisasis`.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'periode_ajaran' => 'required|string|max:20',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tanggal_pendaftaran_tutup' => 'required|date|before_or_equal:tanggal_mulai',
            'pendeta_id' => 'required|exists:pendetas,id',
            'deskripsi' => 'nullable|string',
        ]);

        Katekisasi::create($validated);

        return redirect()->route('admin.pelayanan.katekisasi')
            ->with('success', 'Informasi katekisasi berhasil disimpan.');
    }
}
