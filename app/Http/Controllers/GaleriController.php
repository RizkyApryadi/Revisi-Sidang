<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Support\Facades\Auth;

class GaleriController extends Controller
{
	/**
	 * Display the admin galeri index view.
	 */
	public function index()
	{
		$galeris = Galeri::with('fotos')->orderBy('tanggal', 'desc')->get();

		return view('pages.admin.MasterData.galeri.index', compact('galeris'));
	}

	/**
	 * Show create galeri form.
	 */
	public function create()
	{
		return view('pages.admin.MasterData.galeri.create');
	}

	/**
	 * Handle storing a new galeri. This stores uploaded files to storage and redirects.
	 */
	public function store(Request $request)
	{
		$data = $request->validate([
			'judul' => 'required|string|max:255',
			'deskripsi' => 'nullable|string',
			'tanggal' => 'required|date',
			'foto' => 'nullable',
			'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
		]);

		$galeri = Galeri::create([
			'user_id' => Auth::id(),
			'judul' => $data['judul'],
			'deskripsi' => $data['deskripsi'] ?? null,
			'tanggal' => $data['tanggal'],
		]);

		if ($request->hasFile('foto')) {
			foreach ($request->file('foto') as $file) {
				$path = $file->store('galeris/' . date('Ymd_His'), 'public');
				$galeri->fotos()->create([
					'foto' => $path,
				]);
			}
		}

		return redirect()->route('admin.galeri')->with('success', 'Galeri berhasil dibuat.');
	}
}

