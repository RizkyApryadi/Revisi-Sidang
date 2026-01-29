<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;


class IbadahController extends Controller
{
	/**
	 * Display the admin ibadah index view.
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

		// If joined query returned no rows (or failed silently), fall back to a safe simple fetch
		if (empty($ibadahs) || (is_object($ibadahs) && $ibadahs->isEmpty())) {
			try {
				$simple = DB::table('ibadahs')->orderBy('created_at', 'desc')->get();
				$ibadahs = $simple->map(function ($row) {
					// attach warta info
					$warta = DB::table('wartas')->where('id', $row->warta_id)->first();
					$row->nama_minggu = $warta->nama_minggu ?? null;
					$row->tanggal = $warta->tanggal ?? null;
					// resolve pendeta display name
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
	 * Store a newly created ibadah.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'warta_id' => 'required|integer|exists:wartas,id',
			'pendeta_id' => 'required|integer', // we'll resolve to pendetas.id below
			'waktu' => 'required',
			'tema' => 'required|string|max:255',
			'ayat' => 'nullable|string|max:255',
			'file' => 'nullable|file|mimes:pdf|max:10240',
			'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
		]);

		// Resolve pendeta id: input may be pendetas.id or users.id (when fallback used)
		$inputPendetaId = (int) $validated['pendeta_id'];
		$resolvedPendetaId = null;

		try {

			// if pendeta exists with this id, use it
			$pendeta = DB::table('pendetas')->where('id', $inputPendetaId)->first();
			if ($pendeta) {
				$resolvedPendetaId = $inputPendetaId;
			} else {
				// maybe the dropdown supplied a user id; try to find pendeta with user_id = input
				$byUser = DB::table('pendetas')->where('user_id', $inputPendetaId)->first();
				if ($byUser) {
					$resolvedPendetaId = $byUser->id;
				} else {
					// as last resort, if pendetas table doesn't exist or no relation found, but a user exists
					$user = DB::table('users')->where('id', $inputPendetaId)->first();
					if ($user) {
						// try to find pendeta by jemaat relation via jemaats table (unlikely)
						$maybe = DB::table('pendetas')->where('jemaat_id', $user->id)->first();
						if ($maybe) $resolvedPendetaId = $maybe->id;
					}
				}
			}
		} catch (\Exception $e) {
			Log::error('Pendeta resolve error: ' . $e->getMessage());
		}

		if (empty($resolvedPendetaId)) {
			return redirect()->back()->withErrors(['pendeta_id' => 'Pendeta tidak ditemukan.'])->withInput();
		}

		try {
			$tataPath = null;
			$photoPath = null;

			if ($request->hasFile('file')) {
				$tataPath = $request->file('file')->store('ibadahs/tata_ibadah', 'public');
			}

			if ($request->hasFile('photo')) {
				$photoPath = $request->file('photo')->store('ibadahs/photos', 'public');
			}

			DB::table('ibadahs')->insert([
				'warta_id' => $validated['warta_id'],
				'pendeta_id' => $resolvedPendetaId,
				'waktu' => $validated['waktu'],
				'tema' => $validated['tema'],
				'ayat' => $validated['ayat'] ?? null,
				'tata_ibadah' => $tataPath,
				'foto' => $photoPath,
				'created_at' => now(),
				'updated_at' => now(),
			]);

			return redirect()->route('admin.ibadah')->with('success', 'Jadwal ibadah berhasil ditambahkan.');
		} catch (\Exception $e) {
			Log::error('Ibadah store error: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal menyimpan jadwal ibadah.')->withInput();
		}
	}
}
