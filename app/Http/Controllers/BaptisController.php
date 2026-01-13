<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusBaptis;

class BaptisController extends Controller
{
    public function create()
    {
        return view('pages.admin.MasterData.jemaat.create', ['activeTab' => 'baptis']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jemaat_id' => 'nullable|integer|exists:jemaats,id',
            'status_baptis' => 'required|in:belum,sudah',
            'jenis_gereja' => 'nullable|in:lokal,luar',
            'lokal_nomor_kartu' => 'nullable|string',
            'lokal_tgl_baptis' => 'nullable|date',
            'lokal_baptis_oleh' => 'nullable|string',
            'luar_nomor_kartu' => 'nullable|string',
            'luar_tgl_baptis' => 'nullable|date',
            'luar_baptis_oleh' => 'nullable|string',
            'luar_nama_gereja' => 'nullable|string',
            'luar_alamat_gereja' => 'nullable|string',
            'luar_kota' => 'nullable|string',
        ]);

        $record = new StatusBaptis();
        if (!empty($data['jemaat_id'])) $record->jemaat_id = $data['jemaat_id'];
        $record->status = $data['status_baptis'];
        $record->jenis = $data['jenis_gereja'] ?? null;
        $record->nomor_surat = $data['lokal_nomor_kartu'] ?? $data['luar_nomor_kartu'] ?? null;
        $record->tanggal = $data['lokal_tgl_baptis'] ?? $data['luar_tgl_baptis'] ?? null;
        $record->pendeta = $data['lokal_baptis_oleh'] ?? $data['luar_baptis_oleh'] ?? null;
        $record->nama_gereja = $data['luar_nama_gereja'] ?? null;
        $record->alamat = $data['luar_alamat_gereja'] ?? null;
        $record->kota = $data['luar_kota'] ?? null;
        $record->save();

        return redirect()->back()->with('success', 'Data baptis disimpan.');
    }
}
