<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelayanController extends Controller
{
	/**
	 * Display the admin pelayanan ibadah index view.
	 */
	public function index()
	{
		// select distinct ibadahs that have pelayan entries, join wartas for display
		$ibadahs = DB::table('pelayan_ibadahs')
			->join('ibadahs', 'pelayan_ibadahs.ibadah_id', '=', 'ibadahs.id')
			->leftJoin('wartas', 'ibadahs.warta_id', '=', 'wartas.id')
			->select(
				'ibadahs.id as id',
				'ibadahs.waktu as waktu',
				'ibadahs.warta_id as warta_id',
				'wartas.nama_minggu as warta_nama'
			)
			->groupBy('ibadahs.id', 'ibadahs.waktu', 'ibadahs.warta_id', 'wartas.nama_minggu')
			->orderBy('ibadahs.waktu')
			->get();

		// attach a `warta` object compatible with the view
		foreach ($ibadahs as $ibadah) {
			$ibadah->warta = $ibadah->warta_id ? (object)[
				'id' => $ibadah->warta_id,
				'nama_minggu' => $ibadah->warta_nama,
			] : null;
		}

		return view('pages.admin.MasterData.pelayan.index', compact('ibadahs'));
	}

	/**
	 * Show form to create pelayanan
	 */
	public function create()
	{
		$wartas = DB::table('wartas')->select('id', 'nama_minggu')->orderBy('tanggal', 'desc')->get();
		$ibadahs = DB::table('ibadahs')->select('id', 'warta_id', 'waktu')->get()->groupBy('warta_id');

		$wartasForJs = $wartas->map(function ($w) use ($ibadahs) {
			$list = $ibadahs->has($w->id) ? $ibadahs[$w->id]->map(function ($i) {
				return ['id' => $i->id, 'waktu' => $i->waktu];
			})->values() : collect();

			return [
				'id' => $w->id,
				'nama_minggu' => $w->nama_minggu,
				'ibadahs' => $list,
			];
		});

		return view('pages.admin.MasterData.pelayan.create', compact('wartas', 'wartasForJs'));
	}

	/**
	 * Store pelayanan (placeholder implementation)
	 */
	public function store(Request $request)
	{
		$request->validate([
			'warta_id' => 'required|integer',
			'ibadah_id' => 'required|integer',
			'jenis_pelayanan' => 'required|array|min:1',
			'jenis_pelayanan.*' => 'required|string|max:191',
			'petugas' => 'required|array|min:1',
			'petugas.*' => 'nullable|string|max:191',
		]);

		$ibadahId = $request->input('ibadah_id');
		$jenis = $request->input('jenis_pelayanan', []);
		$petugas = $request->input('petugas', []);

		$rows = [];
		foreach ($jenis as $i => $j) {
			$p = $petugas[$i] ?? null;
			if (trim((string)$j) === '') continue;
			$rows[] = [
				'ibadah_id' => $ibadahId,
				'jenis_pelayanan' => $j,
				'petugas' => $p,
				'created_at' => now(),
				'updated_at' => now(),
			];
		}

		if (!empty($rows)) {
			DB::transaction(function () use ($rows) {
				DB::table('pelayan_ibadahs')->insert($rows);
			});
		}

		return redirect()->route('admin.pelayan')->with('success', 'Pelayanan disimpan.');
	}

	/**
	 * Show detail of pelayanan for an ibadah
	 */
	public function show($id)
	{
		$ibadah = DB::table('ibadahs')->where('id', $id)->first();
		$pelayan = collect();

		if ($ibadah) {
			$warta = DB::table('wartas')->select('id', 'nama_minggu')->where('id', $ibadah->warta_id)->first();
			$ibadah->warta = $warta ?? null;

			// load pelayan entries for this ibadah
			$pelayan = DB::table('pelayan_ibadahs')
				->where('ibadah_id', $id)
				->orderBy('id')
				->get();
		}

		return view('pages.admin.MasterData.pelayan.show', compact('ibadah', 'pelayan'));
	}

	/**
	 * Show edit form for pelayanan of an ibadah
	 */
	public function edit($id)
	{
		$ibadah = DB::table('ibadahs')->where('id', $id)->first();
		if (!$ibadah) {
			return redirect()->route('admin.pelayan')->with('error', 'Ibadah tidak ditemukan.');
		}

		$wartas = DB::table('wartas')->select('id', 'nama_minggu', 'tanggal')->orderBy('tanggal', 'desc')->get();
		$ibadahs = DB::table('ibadahs')->select('id', 'warta_id', 'waktu')->get()->groupBy('warta_id');

		$wartasForJs = $wartas->map(function ($w) use ($ibadahs) {
			$list = $ibadahs->has($w->id) ? $ibadahs[$w->id]->map(function ($i) {
				return ['id' => $i->id, 'waktu' => $i->waktu];
			})->values() : collect();

			return [
				'id' => $w->id,
				'nama_minggu' => $w->nama_minggu,
				'ibadahs' => $list,
			];
		});

		$pelayan = DB::table('pelayan_ibadahs')->where('ibadah_id', $id)->orderBy('id')->get();

		return view('pages.admin.MasterData.pelayan.edit', compact('ibadah', 'wartas', 'wartasForJs', 'pelayan'));
	}

	/**
	 * Update pelayanan list for an ibadah
	 */
	public function update(Request $request, $id)
	{
		$request->validate([
			'jenis_pelayanan' => 'required|array|min:1',
			'jenis_pelayanan.*' => 'required|string|max:191',
			'petugas' => 'required|array|min:1',
			'petugas.*' => 'nullable|string|max:191',
		]);

		$jenis = $request->input('jenis_pelayanan', []);
		$petugas = $request->input('petugas', []);

		$rows = [];
		foreach ($jenis as $i => $j) {
			$p = $petugas[$i] ?? null;
			if (trim((string)$j) === '') continue;
			$rows[] = [
				'ibadah_id' => $id,
				'jenis_pelayanan' => $j,
				'petugas' => $p,
				'created_at' => now(),
				'updated_at' => now(),
			];
		}

		DB::transaction(function () use ($id, $rows) {
			DB::table('pelayan_ibadahs')->where('ibadah_id', $id)->delete();
			if (!empty($rows)) {
				DB::table('pelayan_ibadahs')->insert($rows);
			}
		});

		return redirect()->route('admin.pelayan')->with('success', 'Pelayanan diperbarui.');
	}

	/**
	 * Remove all pelayanan entries for an ibadah
	 */
	public function destroy($id)
	{
		DB::table('pelayan_ibadahs')->where('ibadah_id', $id)->delete();
		return redirect()->route('admin.pelayan')->with('success', 'Pelayanan dihapus.');
	}
}
