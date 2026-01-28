<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Keluarga;

class KeluargaController extends Controller
{
    /**
     * Create a Keluarga model from a request and return it.
     */
    public static function createFromRequest(Request $request)
    {
        Log::debug('KeluargaController::createFromRequest called', ['input' => $request->all()]);
        $data = $request->validate([
            'nomor_registrasi' => 'required|string|max:255|unique:keluargas,nomor_registrasi',
            'tanggal_registrasi' => 'required|date',
            'alamat' => 'required|string',
            'wijk_id' => 'required|integer|exists:wijks,id',
            'tanggal_pernikahan' => 'nullable|date',
            'gereja_pemberkatan' => 'nullable|string|max:255',
            'pendeta_pemberkatan' => 'nullable|string|max:255',
        ]);

        // do not set user_id here — `keluargas` table does not include that column

        // handle file upload
        if ($request->hasFile('akte_pernikahan')) {
            $path = $request->file('akte_pernikahan')->store('keluarga/akte', 'public');
            $data['akte_pernikahan'] = $path;
        }

        $keluarga = Keluarga::create($data);
        Log::debug('Keluarga created', ['id' => $keluarga->id, 'data' => $keluarga->toArray()]);
        return $keluarga;
    }

    /**
     * Store endpoint for creating keluarga via form (admin).
     */
    public function store(Request $request)
    {
        $keluarga = self::createFromRequest($request);
        return redirect()->back()->with('success', 'Keluarga dibuat');
    }

    /**
     * Remove the specified keluarga and its jemaats.
     */
    public function destroy($id)
    {
        try {
            $kel = Keluarga::with('jemaats')->findOrFail($id);

            // delete related jemaats first (if not cascade in DB)
            if ($kel->jemaats && $kel->jemaats->count()) {
                foreach ($kel->jemaats as $j) {
                    // if files exist, consider deleting them here (optional)
                    $j->delete();
                }
            }

            $kel->delete();
            return redirect()->back()->with('success', 'Keluarga dan anggota berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Keluarga destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus keluarga: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to jemaat create form (family data lives in jemaat create step)
     */
    public function create()
    {
        return redirect()->route('admin.jemaat.create');
    }
}
