<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wijk;
use Illuminate\Support\Facades\Auth;

class WijkController extends Controller
{
	/**
	 * Display a listing of the wijks for admin.
	 */
	public function index()
	{
		$wijks = Wijk::orderBy('nama_wijk', 'asc')->get();
		return view('pages.admin.MasterData.wijk.index', compact('wijks'));
	}

	/**
	 * Store a newly created wijk in storage.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'nama_wijk' => 'required|string|max:100|unique:wijks,nama_wijk',
			'keterangan' => 'nullable|string',
		]);

		Wijk::create([
			'nama_wijk' => $validated['nama_wijk'],
			'keterangan' => $validated['keterangan'] ?? null,
		]);

		return redirect()->route('admin.wijk')->with('success', 'WIJK berhasil ditambahkan.');
	}

	/**
	 * Update the specified wijk in storage.
	 */
	public function update(Request $request, Wijk $wijk)
	{
		$validated = $request->validate([
			'nama_wijk' => 'required|string|max:100|unique:wijks,nama_wijk,'.$wijk->id,
			'keterangan' => 'nullable|string',
		]);

		$wijk->update([
			'nama_wijk' => $validated['nama_wijk'],
			'keterangan' => $validated['keterangan'] ?? null,
		]);

		return redirect()->route('admin.wijk')->with('success', 'WIJK berhasil diperbarui.');
	}

	/**
	 * Remove the specified wijk from storage.
	 */
	public function destroy(Wijk $wijk)
	{
		$wijk->delete();
		return redirect()->route('admin.wijk')->with('success', 'WIJK berhasil dihapus.');
	}
}
