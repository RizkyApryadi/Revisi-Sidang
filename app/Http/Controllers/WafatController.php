<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusKedukaan;

class WafatController extends Controller
{
    public function create()
    {
        return view('pages.admin.MasterData.jemaat.create', ['activeTab' => 'kedukaan']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jemaat_id' => 'nullable|integer|exists:jemaats,id',
            'status_jemaat' => 'required|in:hidup,wafat',
            'tgl_wafat' => 'nullable|date',
            'no_surat_kematian' => 'nullable|string',
            'keterangan_wafat' => 'nullable|string',
        ]);

        $record = new StatusKedukaan();
        if (!empty($data['jemaat_id'])) $record->jemaat_id = $data['jemaat_id'];
        $record->status = $data['status_jemaat'];
        $record->tanggal = $data['tgl_wafat'] ?? null;
        $record->nomor_surat = $data['no_surat_kematian'] ?? null;
        $record->keterangan = $data['keterangan_wafat'] ?? null;
        $record->save();

        return redirect()->back()->with('success', 'Data wafat disimpan.');
    }
}
