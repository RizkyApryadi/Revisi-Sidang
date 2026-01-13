<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class WartaController extends Controller
{
    /**
     * Show form to create a new Warta.
     */
    public function create()
    {
        return view('pages.admin.MasterData.ibadah.createWarta');
    }

    /**
     * Display a listing of wartas.
     */
    public function index()
    {
        $wartas = Warta::orderBy('tanggal', 'desc')->get();
        return view('pages.admin.MasterData.ibadah.index', compact('wartas'));
    }

    /**
     * Store a new Warta.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date|unique:wartas,tanggal',
            'nama_minggu' => 'required|string',
            'pengumuman' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $warta = Warta::create([
                'user_id' => Auth::id(),
                'tanggal' => $data['tanggal'],
                'nama_minggu' => $data['nama_minggu'],
                'pengumuman' => $data['pengumuman'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('admin.ibadah')->with('success', 'Warta berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed saving Warta: '.$e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Warta.');
        }
    }
}
