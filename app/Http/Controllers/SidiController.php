<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusSidi;

class SidiController extends Controller
{
    public function create()
    {
        return view('pages.admin.MasterData.jemaat.create', ['activeTab' => 'sidi']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jemaat_id' => 'nullable|integer|exists:jemaats,id',
            'status_sidi' => 'required|in:belum,sudah',
            'jenis_gereja_sidi' => 'nullable|in:lokal,luar',
            'sidi_lokal_nomor_kartu' => 'nullable|string',
            'sidi_lokal_tgl' => 'nullable|date',
            'sidi_lokal_oleh' => 'nullable|string',
            'sidi_luar_nomor_kartu' => 'nullable|string',
            'sidi_luar_tgl' => 'nullable|date',
            'sidi_luar_oleh' => 'nullable|string',
            'sidi_luar_nama_gereja' => 'nullable|string',
            'sidi_luar_alamat_gereja' => 'nullable|string',
            'sidi_luar_kota' => 'nullable|string',
        ]);

        $record = new StatusSidi();
        if (!empty($data['jemaat_id'])) $record->jemaat_id = $data['jemaat_id'];
        $record->status = $data['status_sidi'];
        $record->jenis = $data['jenis_gereja_sidi'] ?? null;
        $record->nomor_surat = $data['sidi_lokal_nomor_kartu'] ?? $data['sidi_luar_nomor_kartu'] ?? null;
        $record->tanggal = $data['sidi_lokal_tgl'] ?? $data['sidi_luar_tgl'] ?? null;
        $record->pendeta = $data['sidi_lokal_oleh'] ?? $data['sidi_luar_oleh'] ?? null;
        $record->nama_gereja = $data['sidi_luar_nama_gereja'] ?? null;
        $record->alamat = $data['sidi_luar_alamat_gereja'] ?? null;
        $record->kota = $data['sidi_luar_kota'] ?? null;
        $record->save();

        return redirect()->back()->with('success', 'Data sidi disimpan.');
    }
}
