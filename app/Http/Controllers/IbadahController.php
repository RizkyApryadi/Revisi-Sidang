<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ibadah;
use App\Models\Warta;
use App\Models\Pendeta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IbadahController extends Controller
{
    /**
     * Display ibadah index with wartas and pendetas for dropdowns.
     */
    public function index()
    {
        $wartas = Warta::orderBy('tanggal', 'desc')->get();
        $pendetas = Pendeta::orderBy('nama_lengkap')->get();
        $ibadahs = Ibadah::with(['warta', 'pendeta'])->orderBy('waktu')->get();
        return view('pages.admin.MasterData.ibadah.index', compact('wartas', 'pendetas', 'ibadahs'));
    }

    /**
     * Store a new Ibadah.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'warta_id' => 'required|exists:wartas,id',
            'pendeta_id' => 'required|exists:pendetas,id',
            'waktu' => 'required',
            'tema' => 'required|string',
            'ayat' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('ibadahs', 'public');
            }

            Ibadah::create([
                'warta_id' => $data['warta_id'],
                'pendeta_id' => $data['pendeta_id'],
                'waktu' => $data['waktu'],
                'tema' => $data['tema'],
                'ayat' => $data['ayat'],
                'file' => $filePath,
            ]);

            DB::commit();
            return redirect()->route('admin.ibadah')->with('success', 'Jadwal ibadah berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed saving Ibadah: '.$e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan jadwal ibadah.');
        }
    }
}
