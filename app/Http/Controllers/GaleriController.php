<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

	/**
	 * Show the form for editing the specified galeri.
	 */
	public function edit($id)
	{
		$galeri = Galeri::with('fotos')->findOrFail($id);

		return view('pages.admin.MasterData.galeri.edit', compact('galeri'));
	}

	/**
	 * Update the specified galeri in storage.
	 */
	public function update(Request $request, $id)
	{
		$galeri = Galeri::findOrFail($id);

		$data = $request->validate([
			'judul' => 'required|string|max:255',
			'deskripsi' => 'nullable|string',
			'tanggal' => 'required|date',
			'foto' => 'nullable',
			'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
		]);

		$galeri->update([
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

		return redirect()->route('admin.galeri')->with('success', 'Galeri berhasil diperbarui.');
	}

	/**
	 * Remove the specified galeri from storage.
	 */
	public function destroy($id)
	{
		$galeri = Galeri::with('fotos')->findOrFail($id);

		// delete fotos from storage
		foreach ($galeri->fotos as $foto) {
			$storedPath = $foto->foto;
			if (Str::startsWith($storedPath, 'public/')) {
				$storedPath = substr($storedPath, 7);
			}
			Storage::disk('public')->delete($storedPath);
			$foto->delete();
		}

		$galeri->delete();

		return redirect()->route('admin.galeri')->with('success', 'Galeri berhasil dihapus.');
	}

	/**
	 * Delete a single galeri foto by id.
	 */
	public function destroyFoto($id)
	{
		$foto = GaleriFoto::findOrFail($id);
		$storedPath = $foto->foto;
		if (Str::startsWith($storedPath, 'public/')) {
			$storedPath = substr($storedPath, 7);
		}
		Storage::disk('public')->delete($storedPath);
		$foto->delete();

		return back()->with('success', 'Foto berhasil dihapus.');
	}

	/**
	 * Display the specified galeri for admin (show view).
	 */
	public function show($id)
	{
		$galeri = Galeri::with('fotos', 'user')->findOrFail($id);

		return view('pages.admin.MasterData.galeri.show', compact('galeri'));
	}
}

