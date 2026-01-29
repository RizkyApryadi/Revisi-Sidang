<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
		]);

		try {
			$filePath = null;
			if ($request->hasFile('file')) {
				$filePath = $request->file('file')->store('beritas/files', 'public');
			}

			DB::table('beritas')->insert([
				'user_id' => Auth::id(),
				'tanggal' => $validated['tanggal'],
				'judul' => $validated['judul'],
				'ringkasan' => $validated['ringkasan'],
				'file' => $filePath,
				'created_at' => now(),
				'updated_at' => now(),
			]);

			return redirect()->route('admin.berita')->with('success', 'Berita berhasil dibuat.');
		} catch (\Exception $e) {
			Log::error('Berita store error: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal menyimpan berita.')->withInput();
		}
	}
}

