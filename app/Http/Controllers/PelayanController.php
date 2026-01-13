<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ibadah;
use App\Models\PelayanIbadah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PelayanController extends Controller
{
    public function index()
    {
        $ibadahs = Ibadah::with('warta')->orderBy('waktu')->get();
        return view('pages.admin.MasterData.pelayan.index', compact('ibadahs'));
    }

    public function show($id)
    {
        $ibadah = Ibadah::with('warta')->findOrFail($id);
        $pelayan = PelayanIbadah::where('ibadah_id', $id)->get();
        return view('pages.admin.MasterData.pelayan.show', compact('ibadah', 'pelayan'));
    }
    public function create()
    {
        // load wartas with their ibadahs so the view can display nama_minggu
        $wartas = \App\Models\Warta::with('ibadahs')->orderBy('tanggal')->get();

        // prepare simple array for JS to avoid closures in blade
        $wartasForJs = $wartas->map(function ($w) {
            return [
                'id' => $w->id,
                'ibadahs' => $w->ibadahs->map(function ($i) {
                    return ['id' => $i->id, 'waktu' => $i->waktu];
                })->values()->toArray(),
            ];
        })->toArray();

        return view('pages.admin.MasterData.pelayan.create', compact('wartas', 'wartasForJs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ibadah_id' => 'required|exists:ibadahs,id',
            'waktu' => 'nullable',
            'jenis_pelayanan' => 'required|array',
            'jenis_pelayanan.*' => 'required|string|max:255',
            'petugas' => 'required|array',
            'petugas.*' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $ibadahId = $data['ibadah_id'];

            foreach ($data['jenis_pelayanan'] as $i => $jenis) {
                $petugas = $data['petugas'][$i] ?? null;
                PelayanIbadah::create([
                    'ibadah_id' => $ibadahId,
                    'jenis_pelayanan' => $jenis,
                    'petugas' => $petugas,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.pelayan')->with('success', 'Data pelayanan ibadah berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed saving PelayanIbadah: '.$e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data pelayanan.');
        }
    }
}
