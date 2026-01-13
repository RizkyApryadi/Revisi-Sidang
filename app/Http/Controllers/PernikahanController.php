<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusPernikahan;

class PernikahanController extends Controller
{
    public function create()
    {
        return view('pages.admin.MasterData.jemaat.create', ['activeTab' => 'pernikahan']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jemaat_id' => 'nullable|integer|exists:jemaats,id',
            'status_nikah' => 'required|in:belum,sudah',
            'jenis_gereja_nikah' => 'nullable|in:lokal,luar',
            'nikah_lokal_nomor_kartu' => 'nullable|string',
            'nikah_lokal_tgl' => 'nullable|date',
            'nikah_lokal_oleh' => 'nullable|string',
            'nikah_luar_nomor_kartu' => 'nullable|string',
            'nikah_luar_tgl' => 'nullable|date',
            'nikah_luar_oleh' => 'nullable|string',
            'nikah_luar_nama_gereja' => 'nullable|string',
            'nikah_luar_alamat_gereja' => 'nullable|string',
            'nikah_luar_kota' => 'nullable|string',
        ]);

        $record = new StatusPernikahan();
        if (!empty($data['jemaat_id'])) $record->jemaat_id = $data['jemaat_id'];
        $record->status = $data['status_nikah'];
        $record->jenis = $data['jenis_gereja_nikah'] ?? null;
        $record->nomor_surat = $data['nikah_lokal_nomor_kartu'] ?? $data['nikah_luar_nomor_kartu'] ?? null;
        $record->tanggal = $data['nikah_lokal_tgl'] ?? $data['nikah_luar_tgl'] ?? null;
        $record->pendeta = $data['nikah_lokal_oleh'] ?? $data['nikah_luar_oleh'] ?? null;
        $record->nama_gereja = $data['nikah_luar_nama_gereja'] ?? null;
        $record->alamat = $data['nikah_luar_alamat_gereja'] ?? null;
        $record->kota = $data['nikah_luar_kota'] ?? null;
        $record->save();

        return redirect()->back()->with('success', 'Data pernikahan disimpan.');
    }
}
