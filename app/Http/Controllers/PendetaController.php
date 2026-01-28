<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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
                $pendetas = DB::table('pendetas')->orderBy('created_at', 'desc')->get();
                $userIds = DB::table('pendetas')->pluck('user_id')->toArray();
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
}
