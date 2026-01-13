<?php

namespace App\Http\Controllers\Penatua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendeta;
use App\Models\Wijk;
use App\Models\PendaftaranPernikahan;
use App\Models\DataMempelai;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class NikahController extends Controller
{
	/**
	 * Display list of pendaftaran pernikahan.
	 */
	public function index()
	{
		$pendaftarans = PendaftaranPernikahan::with('mempelais')->get();
		
		// Calculate total count
		$totalCount = $pendaftarans->count();
		
		return view('pages.penatua.pernikahan.index', compact('pendaftarans', 'totalCount'));
	}

	/**
	 * Show create form for nikah (penatua scope).
	 */
	public function create()
	{
		$user = Auth::user();
		$penatua = $user ? $user->penatua : null;

		$wijkName = null;
		if ($penatua && $penatua->wijk) {
			$wijkName = $penatua->wijk->nama_wijk;
		}

		try {
			$pendetas = Pendeta::orderBy('nama_lengkap')->get();
		} catch (\Throwable $e) {
			$pendetas = collect();
		}

		return view('pages.penatua.nikah.create', compact('wijkName', 'pendetas'));
	}

	/**
	 * Store penatua pendaftaran pernikahan: create pendaftaran and two mempelai records.
	 */
	public function store(Request $request)
	{
		$rules = [
			// Pria
			'pria_nama' => 'required|string',
			'pria_hp' => 'nullable|string',
			'pria_email' => 'nullable|email',
			'pria_tempat_lahir' => 'nullable|string',
			'pria_tanggal_lahir' => 'nullable|date',
			'pria_tanggal_baptis' => 'nullable|date',
			'pria_tanggal_sidi' => 'nullable|date',
			'pria_nama_ayah' => 'nullable|string',
			'pria_nama_ibu' => 'nullable|string',
			'pria_asal_gereja' => 'nullable|string',
			'pria_wijk' => 'nullable|string',
			'pria_alamat' => 'nullable|string',
			// Wanita
			'wanita_nama' => 'required|string',
			'wanita_hp' => 'nullable|string',
			'wanita_email' => 'nullable|email',
			'wanita_tempat_lahir' => 'nullable|string',
			'wanita_tanggal_lahir' => 'nullable|date',
			'wanita_tanggal_baptis' => 'nullable|date',
			'wanita_tanggal_sidi' => 'nullable|date',
			'wanita_nama_ayah' => 'nullable|string',
			'wanita_nama_ibu' => 'nullable|string',
			'wanita_asal_gereja' => 'nullable|string',
			'wanita_wijk' => 'nullable|string',
			'wanita_alamat' => 'nullable|string',
			// Acara
			'partumpolon_tanggal' => 'nullable|date',
			'partumpolon_jam' => 'nullable',
			'partumpolon_keterangan' => 'nullable|string',
			'pamasumasuon_tanggal' => 'nullable|date',
			'pamasumasuon_jam' => 'nullable',
			'pamasumasuon_keterangan' => 'nullable|string',
			// Files
			'surat_gereja_asal' => 'nullable|file|max:5120',
			'surat_baptis_pria' => 'nullable|file|max:5120',
			'surat_baptis_wanita' => 'nullable|file|max:5120',
			'surat_sidi_pria' => 'nullable|file|max:5120',
			'surat_sidi_wanita' => 'nullable|file|max:5120',
			'foto_bersama' => 'nullable|file|max:5120',
			'surat_pengantar_sintua' => 'nullable|file|max:5120',
		];

		$data = $request->validate($rules);

		// handle uploads
		$files = ['surat_gereja_asal','surat_baptis_pria','surat_baptis_wanita','surat_sidi_pria','surat_sidi_wanita','foto_bersama','surat_pengantar_sintua'];
		$stored = [];
		foreach ($files as $f) {
			if ($request->hasFile($f)) {
				$stored[$f] = $request->file($f)->store('pernikahan', 'public');
			}
		}

		DB::beginTransaction();
		try {
			$pendaftaran = PendaftaranPernikahan::create([
				'tanggal_perjanjian' => $data['partumpolon_tanggal'] ?? null,
				'jam_perjanjian' => $data['partumpolon_jam'] ?? null,
				'keterangan_perjanjian' => $data['partumpolon_keterangan'] ?? null,
				'tanggal_pemberkatan' => $data['pamasumasuon_tanggal'] ?? null,
				'jam_pemberkatan' => $data['pamasumasuon_jam'] ?? null,
				'keterangan_pemberkatan' => $data['pamasumasuon_keterangan'] ?? null,
				'surat_keterangan_gereja_asal' => $stored['surat_gereja_asal'] ?? null,
				'surat_baptis_pria' => $stored['surat_baptis_pria'] ?? null,
				'surat_baptis_wanita' => $stored['surat_baptis_wanita'] ?? null,
				'surat_sidi_pria' => $stored['surat_sidi_pria'] ?? null,
				'surat_sidi_wanita' => $stored['surat_sidi_wanita'] ?? null,
				'foto' => $stored['foto_bersama'] ?? null,
				'surat_pengantar' => $stored['surat_pengantar_sintua'] ?? null,
			]);

			// create mempelai records (pria, wanita)
			$pria = DataMempelai::create([
				'pendaftaran_pernikahans_id' => $pendaftaran->id,
				'nama' => $data['pria_nama'],
				'no_hp' => $data['pria_hp'] ?? null,
				'email' => $data['pria_email'] ?? null,
				'tempat_lahir' => $data['pria_tempat_lahir'] ?? null,
				'tanggal_lahir' => $data['pria_tanggal_lahir'] ?? null,
				'tanggal_baptis' => $data['pria_tanggal_baptis'] ?? null,
				'tanggal_sidi' => $data['pria_tanggal_sidi'] ?? null,
				'nama_ayah' => $data['pria_nama_ayah'] ?? null,
				'nama_ibu' => $data['pria_nama_ibu'] ?? null,
				'asal_gereja' => $data['pria_asal_gereja'] ?? null,
				'wijk' => $data['pria_wijk'] ?? null,
				'alamat' => $data['pria_alamat'] ?? null,
			]);

			$wanita = DataMempelai::create([
				'pendaftaran_pernikahans_id' => $pendaftaran->id,
				'nama' => $data['wanita_nama'],
				'no_hp' => $data['wanita_hp'] ?? null,
				'email' => $data['wanita_email'] ?? null,
				'tempat_lahir' => $data['wanita_tempat_lahir'] ?? null,
				'tanggal_lahir' => $data['wanita_tanggal_lahir'] ?? null,
				'tanggal_baptis' => $data['wanita_tanggal_baptis'] ?? null,
				'tanggal_sidi' => $data['wanita_tanggal_sidi'] ?? null,
				'nama_ayah' => $data['wanita_nama_ayah'] ?? null,
				'nama_ibu' => $data['wanita_nama_ibu'] ?? null,
				'asal_gereja' => $data['wanita_asal_gereja'] ?? null,
				'wijk' => $data['wanita_wijk'] ?? null,
				'alamat' => $data['wanita_alamat'] ?? null,
			]);

			DB::commit();
		} catch (\Throwable $e) {
			DB::rollBack();
			throw $e;
		}

		return redirect()->route('penatua.pelayanan.pernikahan')->with('success', 'Pendaftaran nikah berhasil dikirim.');
	}
}
