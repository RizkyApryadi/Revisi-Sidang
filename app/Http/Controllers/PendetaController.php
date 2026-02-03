<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Pendeta;
use App\Models\Penatua;

class PendetaController extends Controller
{
    /**
     * Display the admin pendeta index view.
     */
    public function index()
    {
        $pendetas = collect();
        $pendingPendetas = collect();
        $pendingCount = 0;

        try {
            if (Schema::hasTable('pendetas')) {
                // use Eloquent with eager-loading so Blade can access related jemaat/user attributes
                $pendetas = Pendeta::with(['jemaat', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                $userIds = Pendeta::pluck('user_id')->toArray();
                $pendingPendetas = User::where('role', 'pendeta')
                    ->whereNotIn('id', $userIds)
                    ->get();
                $pendingCount = $pendingPendetas->count();
            } else {
                // fallback: list users with role 'pendeta' when no pendetas table exists
                $pendetas = User::where('role', 'pendeta')->get();
                $pendingPendetas = collect();
                $pendingCount = 0;
            }
        } catch (\Exception $e) {
            $pendetas = collect();
            $pendingPendetas = collect();
            $pendingCount = 0;
        }

        return view('pages.admin.MasterData.pendeta.index', compact('pendetas', 'pendingPendetas', 'pendingCount'));
    }

    /**
     * Show the form for creating a new pendeta.
     */
    public function create()
    {
        return view('pages.admin.MasterData.pendeta.create');
    }

    /**
     * Store a newly created pendeta in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'jemaat_id' => 'required|integer|exists:jemaats,id|unique:pendetas,jemaat_id',
            'tanggal_tahbis' => 'required|date|before_or_equal:today',
            'status' => 'required|in:aktif,nonaktif,selesai',
            'no_sk_tahbis' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        try {
            // Cross-table: jemaat already a penatua cannot be pendeta
            if (Penatua::where('jemaat_id', $validated['jemaat_id'])->exists()) {
                return redirect()->back()->with('error', 'Jemaat sudah terdaftar sebagai Penatua, tidak bisa menjadi Pendeta.')->withInput();
            }
            $insert = [
                'user_id' => null,
                'jemaat_id' => $validated['jemaat_id'],
                'tanggal_tahbis' => $validated['tanggal_tahbis'],
                'status' => $validated['status'],
                'no_sk_tahbis' => $validated['no_sk_tahbis'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('pendetas')->insert($insert);

            return redirect()->route('admin.pendeta')->with('success', 'Pendeta berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Pendeta store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan pendeta.')->withInput();
        }
    }

    /**
     * Show the form for editing the specified pendeta.
     */
    public function edit($id)
    {
        try {
            if (Schema::hasTable('pendetas')) {
                $pendeta = Pendeta::with(['jemaat', 'user'])->where('id', $id)->first();
            } else {
                $pendeta = User::find($id);
            }
        } catch (\Exception $e) {
            Log::error('Pendeta edit error: ' . $e->getMessage());
            $pendeta = null;
        }

        if (!$pendeta) {
            return redirect()->route('admin.pendeta')->with('error', 'Data pendeta tidak ditemukan.');
        }

        return view('pages.admin.MasterData.pendeta.edit', compact('pendeta'));
    }

    /**
     * Update the specified pendeta in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'jemaat_id' => 'required|integer|exists:jemaats,id|unique:pendetas,jemaat_id,' . $id . ',id',
            'tanggal_tahbis' => 'required|date|before_or_equal:today',
            'status' => 'required|in:aktif,nonaktif,selesai',
            'no_sk_tahbis' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        try {
            $update = [
                'jemaat_id' => $validated['jemaat_id'],
                'tanggal_tahbis' => $validated['tanggal_tahbis'],
                'status' => $validated['status'],
                'no_sk_tahbis' => $validated['no_sk_tahbis'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'updated_at' => now(),
            ];
            // Cross-table: ensure the target jemaat is not registered as penatua
            if (Penatua::where('jemaat_id', $validated['jemaat_id'])->exists()) {
                return redirect()->back()->with('error', 'Jemaat sudah terdaftar sebagai Penatua, tidak bisa menjadi Pendeta.')->withInput();
            }
            DB::table('pendetas')->where('id', $id)->update($update);

            return redirect()->route('admin.pendeta')->with('success', 'Pendeta berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Pendeta update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui pendeta.')->withInput();
        }
    }

    /**
     * Remove the specified pendeta from storage.
     */
    public function destroy($id)
    {
        try {
            if (Schema::hasTable('pendetas')) {
                DB::table('pendetas')->where('id', $id)->delete();
            } else {
                // nothing to delete, but return success if using users fallback
            }
            return redirect()->route('admin.pendeta')->with('success', 'Pendeta berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Pendeta destroy error: ' . $e->getMessage());
            return redirect()->route('admin.pendeta')->with('error', 'Gagal menghapus pendeta.');
        }
    }
}
