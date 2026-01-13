<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendaftaranSidi;

class PendaftaranSidiController extends Controller
{
    public function approve(Request $request, $id)
    {
        $p = PendaftaranSidi::findOrFail($id);
        $p->status_pengajuan = 'disetujui';
        $p->catatan_admin = $request->input('catatan_admin');
        $p->save();

        return redirect()->back()->with('success', 'Pendaftar disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $p = PendaftaranSidi::findOrFail($id);
        $p->status_pengajuan = 'ditolak';
        $p->catatan_admin = $request->input('catatan_admin');
        $p->save();

        return redirect()->back()->with('success', 'Pendaftar ditolak.');
    }
}
