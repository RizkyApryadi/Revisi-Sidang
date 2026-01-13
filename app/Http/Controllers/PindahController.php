<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusPindah;

class PindahController extends Controller
{
    public function create()
    {
        return view('pages.admin.MasterData.jemaat.create', ['activeTab' => 'pindah']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jemaat_id' => 'nullable|integer|exists:jemaats,id',
            'status_pindah' => 'required|in:tetap,pindah_masuk',
            'no_surat_pindah' => 'nullable|string',
            'tgl_pindah_masuk' => 'nullable|date',
            'gereja_asal' => 'nullable|string',
            'kota_gereja_asal' => 'nullable|string',
        ]);

        $record = new StatusPindah();
        if (!empty($data['jemaat_id'])) $record->jemaat_id = $data['jemaat_id'];
        $record->status = $data['status_pindah'];
        $record->nomor_surat = $data['no_surat_pindah'] ?? null;
        $record->tanggal = $data['tgl_pindah_masuk'] ?? null;
        $record->nama_gereja = $data['gereja_asal'] ?? null;
        $record->kota = $data['kota_gereja_asal'] ?? null;
        $record->save();

        return redirect()->back()->with('success', 'Data pindah disimpan.');
    }
}
