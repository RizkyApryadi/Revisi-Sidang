<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BeritaController extends Controller
{
	/**
	 * Show form to create a new Berita.
	 */
	public function create()
	{
		return view('pages.admin.MasterData.berita.create');
	}

	/**
	 * Display a listing of Berita.
	 */
	public function index()
	{
		$beritas = Berita::orderBy('tanggal', 'desc')->get();
		return view('pages.admin.MasterData.berita.index', compact('beritas'));
	}

	/**
	 * Store a new Berita.
	 */
	public function store(Request $request)
	{
		$data = $request->validate([
			'tanggal' => 'required|date',
			'judul' => 'required|string|max:255',
			'ringkasan' => 'required|string',
			'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
		]);

		DB::beginTransaction();
		try {
			$filePath = null;
			if ($request->hasFile('file')) {
				$filePath = $request->file('file')->store('berita_files', 'public');
			}

			$berita = Berita::create([
				'user_id' => Auth::id(),
				'tanggal' => $data['tanggal'],
				'judul' => $data['judul'],
				'ringkasan' => $data['ringkasan'],
				'file' => $filePath,
			]);

			DB::commit();
			return redirect()->route('admin.berita')->with('success', 'Berita berhasil ditambahkan.');
		} catch (\Throwable $e) {
			DB::rollBack();
			Log::error('Failed saving Berita: ' . $e->getMessage());
			return redirect()->back()->withInput()->with('error', 'Gagal menyimpan Berita.');
		}
	}
}
