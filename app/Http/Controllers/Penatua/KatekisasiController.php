<?php

namespace App\Http\Controllers\Penatua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Katekisasi;
use App\Models\PendaftaranSidi;

class KatekisasiController extends Controller
{
	/**
	 * Show the create form for katekisasi (pendaftaran sidi).
	 * Wijk is auto-filled based on the authenticated penatua's assigned wijk.
	 */
	public function create()
	{
		$user = Auth::user();
		$penatua = $user ? $user->penatua : null;

		$wijkName = null;
		if ($penatua && $penatua->wijk) {
			$wijkName = $penatua->wijk->nama_wijk;
		}

		return view('pages.penatua.katekisasi.create', compact('wijkName'));
	}

	/**
	 * Store a new pendaftaran sidi submitted by a penatua.
	 */
	public function store(Request $request)
	{
		$user = Auth::user();
		$penatua = $user ? $user->penatua : null;

		$validated = $request->validate([
			'nama' => 'required|string|max:255',
			'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
			// 'tempat_lahir' is collected in the form but not stored in DB table
			'tempat_lahir' => 'nullable|string|max:255',
			'tanggal_lahir' => 'nullable|date',
			'no_hp' => 'nullable|string|max:50',
			'email' => 'nullable|email|max:255',
			'nama_ayah' => 'nullable|string|max:255',
			'nama_ibu' => 'nullable|string|max:255',
			'alamat' => 'nullable|string',
			'wijk' => 'nullable|string|max:255',
			'jenis_pendaftar' => 'required|in:internal,external',
			'katekisasi_id' => 'required|exists:katekisasis,id',
			'foto_4x6' => 'nullable|file|max:5120',
			'foto_3x4' => 'nullable|file|max:5120',
			'surat_baptis' => 'nullable|file|max:5120',
			'kartu_keluarga' => 'nullable|file|max:5120',
			'surat_pengantar_sintua' => 'nullable|file|max:5120',
		]);

		// Handle uploads
		$files = ['foto_4x6','foto_3x4','surat_baptis','kartu_keluarga','surat_pengantar_sintua'];
		foreach ($files as $file) {
			if ($request->hasFile($file)) {
				$validated[$file] = $request->file($file)->store('pendaftaran_sidis', 'public');
			}
		}

		// Set jemaat_id null by default only if the database has the column.
		if (Schema::hasColumn('pendaftaran_sidis', 'jemaat_id')) {
			$validated['jemaat_id'] = null;
		}

		// The form collects 'tempat_lahir' but the DB table doesn't have that column — remove it before saving.
		if (array_key_exists('tempat_lahir', $validated)) {
			unset($validated['tempat_lahir']);
		}

		// Ensure katekisasi_id is present (validated above) and include it for saving
		$validated['katekisasi_id'] = $request->input('katekisasi_id');

		// Save using Eloquent model if exists, otherwise DB insert
		if (class_exists('\App\\Models\\PendaftaranSidi')) {
			\App\Models\PendaftaranSidi::create($validated);
		} else {
			\Illuminate\Support\Facades\DB::table('pendaftaran_sidis')->insert(array_merge($validated, [
				'created_at' => now(),
				'updated_at' => now(),
			]));
		}

		return redirect()->route('penatua.pelayanan.katekisasi')
			->with('success', 'Pendaftaran Sidi berhasil disimpan.');
	}

	/**
	 * Show list of available Katekisasi to Penatua (read-only).
	 */
	public function index()
	{
		try {
			$katekisasis = Katekisasi::with('pendeta')->orderBy('tanggal_mulai', 'desc')->get();
		} catch (\Throwable $e) {
			logger()->error('Penatua\\KatekisasiController@index: '.$e->getMessage());
			$katekisasis = collect();
		}

		return view('pages.penatua.katekisasi.index', compact('katekisasis'));
	}

	/**
	 * Display the specified Katekisasi details.
	 */
	public function show($id)
	{
		$k = Katekisasi::with(['pendeta','pendaftaranSidis'])->find($id);
		if (! $k) {
			abort(404);
		}

		// Determine penatua's wijk so we only show pendaftar from the same wijk
		$user = Auth::user();
		$penatua = $user ? $user->penatua : null;

		$wijkName = null;
		if ($penatua && $penatua->wijk) {
			$wijkName = $penatua->wijk->nama_wijk;
		}

		$pendaftaranSidis = collect();
		if ($wijkName) {
			try {
				$pendaftaranSidis = PendaftaranSidi::where('katekisasi_id', $k->id)
					->where('wijk', $wijkName)
					->orderBy('created_at', 'desc')
					->get();
			} catch (\Throwable $e) {
				logger()->error('Penatua\\KatekisasiController@show pendaftaranSidis: '.$e->getMessage());
				$pendaftaranSidis = collect();
			}
		}

		return view('pages.penatua.katekisasi.show', compact('k', 'pendaftaranSidis', 'wijkName'));
	}

	/**
	 * Show edit form (penatua may not edit katekisasi metadata; provide view placeholder).
	 */
	public function edit($id)
	{
		$k = Katekisasi::find($id);
		if (! $k) abort(404);
		return view('pages.penatua.katekisasi.edit', compact('k'));
	}

	/**
	 * Remove the specified katekisasi (if allowed).
	 */
	public function destroy($id)
	{
		$k = Katekisasi::find($id);
		if (! $k) abort(404);
		$k->delete();
		return redirect()->route('penatua.pelayanan.katekisasi')->with('success', 'Katekisasi dihapus.');
	}
}

