<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GaleriController extends Controller
{
    /**
     * Display a listing of galerIs.
     */
    public function index()
    {
        $galeris = Galeri::with('fotos')->orderBy('tanggal', 'desc')->get();
        return view('pages.admin.MasterData.galeri.index', compact('galeris'));
    }

    public function create()
    {
        return view('pages.admin.MasterData.galeri.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'foto.*' => 'nullable|image|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $galeri = Galeri::create([
                'user_id' => Auth::id(),
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'tanggal' => $data['tanggal'],
            ]);

            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $path = $file->store('galeri_photos', 'public');
                    GaleriFoto::create([
                        'galeri_id' => $galeri->id,
                        'foto' => $path,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.galeri')->with('success', 'Galeri berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed saving Galeri: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Galeri.');
        }
    }
}
