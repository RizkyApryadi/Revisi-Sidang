<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wijk;
use App\Models\Penatua;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PenatuaController extends Controller
{
    /**
     * Display the admin penatua index view.
     */
    public function index()
    {
        // Eager-load related jemaat and wijk to ensure all data is available to the view.
        $penatuas = Penatua::with(['jemaat', 'wijk'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Normalize a couple of convenience attributes the view expects (nama_lengkap, no_hp)
        $penatuas = $penatuas->map(function ($p) {
            if (!empty($p->tanggal_tahbis)) {
                try {
                    $p->tanggal_tahbis = Carbon::parse($p->tanggal_tahbis);
                } catch (\Exception $e) {
                    // leave as-is
                }
            } else {
                $p->tanggal_tahbis = null;
            }

            // add compatibility properties used by the Blade view
            $p->nama_lengkap = $p->jemaat->nama ?? null;
            $p->no_hp = $p->jemaat->no_telp ?? null;

            return $p;
        });

        $wijks = Wijk::orderBy('nama_wijk', 'asc')->get();

        // Debug: log how many penatua were loaded so we can verify DB contents
        try {
            Log::info('PenatuaController@index loaded penatuas count: ' . $penatuas->count());
        } catch (\Exception $e) {
            // ignore logging issues
        }

        return view('pages.admin.MasterData.penatua.index', compact('penatuas', 'wijks'));
    }

    public function create()
    {
        $wijks = Wijk::orderBy('nama_wijk', 'asc')->get();
        return view('pages.admin.MasterData.penatua.create', compact('wijks'));
    }

    /**
     * Show penatua data (JSON) for editing.
     */
    public function edit($id)
    {
        $penatua = Penatua::with('wijk', 'jemaat')->findOrFail($id);
        $wijks = Wijk::orderBy('nama_wijk', 'asc')->get();

        return response()->json([
            'penatua' => $penatua,
            'wijks' => $wijks,
        ]);
    }

    /**
     * Store a new penatua record into penatuas table.
     */
    public function store(Request $request)
    {
        $rules = [
            'jemaat_id' => 'required|integer|exists:jemaats,id|unique:penatuas,jemaat_id',
            'wijk_id' => 'required|integer|exists:wijks,id',
            'tanggal_tahbis' => 'required|date|before_or_equal:today',
            'status' => 'required|in:aktif,nonaktif,selesai',
            'keterangan' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id|unique:penatuas,user_id',
        ];

        $validated = $request->validate($rules);

        try {
            // Determine which user should be associated: request user_id (optional). Do NOT default to Auth::id().
            $userId = $validated['user_id'] ?? null;

            // Prevent duplicate penatua by jemaat_id; only check user duplicate when a user_id was provided
            $existsByJemaat = Penatua::where('jemaat_id', $validated['jemaat_id'])->exists();
            $existsByUser = $userId ? Penatua::where('user_id', $userId)->exists() : false;

            if ($existsByJemaat || $existsByUser) {
                if ($existsByJemaat) {
                    return redirect()->back()->with('error', 'Jemaat sudah terdaftar jadi penatua')->withInput();
                }
                if ($existsByUser) {
                    return redirect()->back()->with('error', 'Akun pengguna yang dipilih sudah terdaftar sebagai penatua. Silakan pilih akun user lain.')->withInput();
                }
            }

            $penatua = Penatua::create([
                'user_id' => $userId,
                'jemaat_id' => $validated['jemaat_id'],
                'wijk_id' => $validated['wijk_id'],
                'tanggal_tahbis' => $validated['tanggal_tahbis'],
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            return redirect()->route('admin.penatua')->with('success', 'Penatua berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Penatua store error: ' . $e->getMessage());
            // Do not expose raw SQL errors to the user; show a generic failure message
            return redirect()->back()->with('error', 'Gagal menyimpan penatua. Silakan periksa data dan coba lagi.')->withInput();
        }
    }

    /**
     * Update the specified penatua (only penatua-specific fields).
     */
    public function update(Request $request, $id)
    {
        $penatua = Penatua::findOrFail($id);

        $rules = [
            'wijk_id' => 'required|integer|exists:wijks,id',
            'tanggal_tahbis' => 'nullable|date|before_or_equal:today',
            'status' => 'required|in:aktif,nonaktif,selesai',
            'keterangan' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
        ];

        $validated = $request->validate($rules);

        try {
            $penatua->update([
                'wijk_id' => $validated['wijk_id'],
                'tanggal_tahbis' => $validated['tanggal_tahbis'] ?? $penatua->tanggal_tahbis,
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? $penatua->keterangan,
                'user_id' => $validated['user_id'] ?? $penatua->user_id,
            ]);

            return redirect()->route('admin.penatua')->with('success', 'Data Penatua berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Penatua update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui penatua: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified penatua from storage.
     */
    public function destroy($id)
    {
        try {
            $penatua = Penatua::findOrFail($id);
            $penatua->delete();
            return redirect()->route('admin.penatua')->with('success', 'Penatua berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Penatua destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus penatua: ' . $e->getMessage());
        }
    }
}
