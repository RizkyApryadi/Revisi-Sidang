<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class WartaController extends Controller
{
    /**
     * Show the create warta form.
     */
    public function create()
    {
        return view('pages.admin.MasterData.ibadah.createWarta');
    }

    /**
     * Display a listing of wartas in the admin ibadah page.
     */
    public function index()
    {
        $wartas = DB::table('wartas')->orderBy('tanggal', 'desc')->get();

        // fetch pendetas for the create-ibadah modal (fallback to users with role 'pendeta')
        $pendetas = collect();
        try {
            if (Schema::hasTable('pendetas')) {
                $pendetas = DB::table('pendetas')
                    ->leftJoin('jemaats', 'pendetas.jemaat_id', '=', 'jemaats.id')
                    ->select('pendetas.id', DB::raw("COALESCE(jemaats.nama, pendetas.nama) as name"))
                    ->orderBy('pendetas.created_at', 'desc')
                    ->get();
            } else {
                $pendetas = DB::table('users')->where('role', 'pendeta')->select('id', 'name')->get();
            }
        } catch (\Exception $e) {
            Log::error('Error fetching pendetas: ' . $e->getMessage());
            $pendetas = collect();
        }

        // fetch ibadahs to display in the index table (include warta and pendeta display name)
        $ibadahs = collect();
        try {
            $ibadahs = DB::table('ibadahs')
                ->leftJoin('wartas', 'ibadahs.warta_id', '=', 'wartas.id')
                ->leftJoin('pendetas', 'ibadahs.pendeta_id', '=', 'pendetas.id')
                ->leftJoin('jemaats', 'pendetas.jemaat_id', '=', 'jemaats.id')
                ->leftJoin('users', 'pendetas.user_id', '=', 'users.id')
                ->select('ibadahs.*', 'wartas.nama_minggu', 'wartas.tanggal', DB::raw("COALESCE(jemaats.nama, pendetas.nama, users.name) as pendeta_name"))
                ->orderBy('ibadahs.created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching ibadahs: ' . $e->getMessage());
            $ibadahs = collect();
        }

        // fallback simple fetch if joined query returned no rows
        if (empty($ibadahs) || (is_object($ibadahs) && $ibadahs->isEmpty())) {
            try {
                $simple = DB::table('ibadahs')->orderBy('created_at', 'desc')->get();
                $ibadahs = $simple->map(function ($row) {
                    $warta = DB::table('wartas')->where('id', $row->warta_id)->first();
                    $row->nama_minggu = $warta->nama_minggu ?? null;
                    $row->tanggal = $warta->tanggal ?? null;

                    $pendetaName = null;
                    if (Schema::hasTable('pendetas')) {
                        $pendeta = DB::table('pendetas')->where('id', $row->pendeta_id)->first();
                        if ($pendeta) {
                            $j = DB::table('jemaats')->where('id', $pendeta->jemaat_id)->first();
                            $pendetaName = $j->nama ?? ($pendeta->nama ?? null);
                        }
                    }

                    if (empty($pendetaName)) {
                        $u = DB::table('users')->where('id', $row->pendeta_id)->first();
                        $pendetaName = $u->name ?? $u->email ?? null;
                    }

                    $row->pendeta_name = $pendetaName;
                    return $row;
                });
            } catch (\Exception $e) {
                Log::error('Fallback ibadah fetch error: ' . $e->getMessage());
                $ibadahs = collect();
            }
        }

        return view('pages.admin.MasterData.ibadah.index', compact('wartas', 'pendetas', 'ibadahs'));
    }

    /**
     * Store a new warta.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'nama_minggu' => 'required|string|max:255',
            'pengumuman' => 'nullable|string',
        ]);

        try {
            DB::table('wartas')->insert([
                'user_id' => Auth::id(),
                'tanggal' => $data['tanggal'],
                'nama_minggu' => $data['nama_minggu'],
                'pengumuman' => $data['pengumuman'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('admin.ibadah')->with('success', 'Warta berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Warta store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan warta.')->withInput();
        }
    }
}
