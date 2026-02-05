<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class BeritaController extends Controller
{
	/**
	 * Display the admin berita index view.
	 */
	public function index()
	{
		$beritas = DB::table('beritas')->orderBy('tanggal', 'desc')->get();

		return view('pages.admin.MasterData.berita.index', compact('beritas'));
	}

	/**
	 * Show the form for creating a new berita.
	 */
	public function create()
	{
		return view('pages.admin.MasterData.berita.create');
	}

	/**
	 * Store a newly created berita in storage.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'tanggal' => 'required|date',
			'judul' => 'required|string|max:255',
			'ringkasan' => 'required|string',
			'file' => 'nullable|file|max:10240',
			'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
		]);

		try {
			$filePath = null;
			$fotoPath = null;
			if ($request->hasFile('file')) {
				$filePath = $request->file('file')->store('beritas/files', 'public');
			}
			if ($request->hasFile('foto')) {
				$fotoPath = $request->file('foto')->store('beritas/foto', 'public');
			}

			DB::table('beritas')->insert([
				'user_id' => Auth::id(),
				'tanggal' => $validated['tanggal'],
				'judul' => $validated['judul'],
				'ringkasan' => $validated['ringkasan'],
				'file' => $filePath,
				'foto' => $fotoPath,
				'created_at' => now(),
				'updated_at' => now(),
			]);

			return redirect()->route('admin.berita')->with('success', 'Berita berhasil dibuat.');
		} catch (\Exception $e) {
			Log::error('Berita store error: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal menyimpan berita.')->withInput();
		}
	}

	/**
	 * Handle image uploads from the editor (TinyMCE).
	 */
	public function uploadImage(Request $request)
	{
		// Support single file named 'file' or multiple files 'file[]' / 'files'
		$files = [];
		if ($request->hasFile('file')) {
			$files = [$request->file('file')];
		} elseif ($request->hasFile('files')) {
			$files = $request->file('files');
		} elseif ($request->hasFile('file') && is_array($request->file('file'))) {
			$files = $request->file('file');
		}

		if (empty($files)) {
			return response()->json(['error' => 'No files uploaded'], 400);
		}

		$locations = [];
		foreach ($files as $f) {
			try {
				// validate each file
				if (!$f->isValid()) continue;
				$mime = $f->getClientMimeType();
				if (!preg_match('/image\//', $mime)) continue;
				$path = $f->store('beritas/content', 'public');
				$locations[] = asset('storage/' . $path);
			} catch (\Exception $e) {
				Log::error('Upload image error: ' . $e->getMessage());
			}
		}

		if (empty($locations)) {
			return response()->json(['error' => 'Gagal mengunggah gambar.'], 500);
		}

		// If only one image, return 'location' for TinyMCE default. If multiple, return 'locations' array.
		if (count($locations) === 1) {
			return response()->json(['location' => $locations[0]], 200);
		}

		return response()->json(['locations' => $locations], 200);
	}

	/**
	 * Display the specified berita.
	 */
	public function show($id)
	{
		$berita = DB::table('beritas')->where('id', $id)->first();
		if (!$berita) {
			return redirect()->route('admin.berita')->with('error', 'Berita tidak ditemukan.');
		}
		return view('pages.admin.MasterData.berita.show', compact('berita'));
	}

	/**
	 * Show the form for editing the specified berita.
	 */
	public function edit($id)
	{
		$berita = DB::table('beritas')->where('id', $id)->first();
		if (!$berita) {
			return redirect()->route('admin.berita')->with('error', 'Berita tidak ditemukan.');
		}
		return view('pages.admin.MasterData.berita.edit', compact('berita'));
	}

	/**
	 * Update the specified berita in storage.
	 */
	public function update(Request $request, $id)
	{
		$validated = $request->validate([
			'tanggal' => 'required|date',
			'judul' => 'required|string|max:255',
			'ringkasan' => 'required|string',
			'file' => 'nullable|file|max:10240',
			'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
		]);

		$berita = DB::table('beritas')->where('id', $id)->first();
		if (!$berita) {
			return redirect()->route('admin.berita')->with('error', 'Berita tidak ditemukan.');
		}

		try {
			$filePath = $berita->file ?? null;
			$fotoPath = $berita->foto ?? null;
			if ($request->hasFile('file')) {
				// delete old file
				if (!empty($filePath)) Storage::disk('public')->delete($filePath);
				$filePath = $request->file('file')->store('beritas/files', 'public');
			}
			if ($request->hasFile('foto')) {
				if (!empty($fotoPath)) Storage::disk('public')->delete($fotoPath);
				$fotoPath = $request->file('foto')->store('beritas/foto', 'public');
			}

			DB::table('beritas')->where('id', $id)->update([
				'tanggal' => $validated['tanggal'],
				'judul' => $validated['judul'],
				'ringkasan' => $validated['ringkasan'],
				'file' => $filePath,
				'foto' => $fotoPath,
				'updated_at' => now(),
			]);

			return redirect()->route('admin.berita')->with('success', 'Berita berhasil diperbarui.');
		} catch (\Exception $e) {
			Log::error('Berita update error: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal memperbarui berita.')->withInput();
		}
	}

	/**
	 * Remove the specified berita from storage.
	 */
	public function destroy($id)
	{
		$berita = DB::table('beritas')->where('id', $id)->first();
		if (!$berita) {
			return redirect()->route('admin.berita')->with('error', 'Berita tidak ditemukan.');
		}

		try {
			if (!empty($berita->file)) Storage::disk('public')->delete($berita->file);
			if (!empty($berita->foto)) Storage::disk('public')->delete($berita->foto);
			DB::table('beritas')->where('id', $id)->delete();
			return redirect()->route('admin.berita')->with('success', 'Berita berhasil dihapus.');
		} catch (\Exception $e) {
			Log::error('Berita destroy error: ' . $e->getMessage());
			return redirect()->route('admin.berita')->with('error', 'Gagal menghapus berita.');
		}
	}
}
