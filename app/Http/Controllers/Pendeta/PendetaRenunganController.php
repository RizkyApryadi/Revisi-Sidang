<?php

namespace App\Http\Controllers\Pendeta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Pendeta;
use App\Models\Renungan;

class PendetaRenunganController extends Controller
{
	/**
	 * Display a listing of the renungans.
	 */
	public function index(Request $request)
	{
		// use Eloquent so relations are available in the view
		$renungans = Renungan::with('pendeta')
			->orderBy('tanggal', 'desc')
			->paginate(10);

		return view('pages.pendeta.renungan.index', compact('renungans'));
	}

	/**
	 * Show the form for creating a new renungan.
	 */
	public function create()
	{
		$defaults = [
			'tanggal' => Carbon::now()->toDateString(),
			'status' => 'draft',
		];

		$pendeta = Auth::user();

		return view('pages.pendeta.renungan.create', compact('defaults', 'pendeta'));
	}

	/**
	 * Store a newly created renungan in storage.
	 */
	public function store(Request $request)
	{
		$rules = [
			'judul' => 'required|string|max:255',
			'tanggal' => 'required|date',
			'konten' => 'nullable|string',
			'status' => 'required|in:draft,publish',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		try {
			$data = [
				'judul' => $request->input('judul'),
				'tanggal' => $request->input('tanggal'),
				'konten' => $request->input('konten'),
				'status' => $request->input('status'),
			];

			if (Auth::check()) {
				$pendeta = Pendeta::where('user_id', Auth::id())->first();
				if ($pendeta) {
					$data['pendeta_id'] = $pendeta->id;
				}
			}

			Renungan::create($data);

			return redirect()->route('pendeta.renungan.index')->with('success', 'Renungan berhasil disimpan.');
		} catch (\Throwable $e) {
			return redirect()->back()->withInput()->with('error', 'Gagal menyimpan renungan: ' . $e->getMessage());
		}
	}

	/**
	 * Display the specified renungan.
	 */
	public function show($id)
	{
		$renungan = Renungan::with('pendeta')->findOrFail($id);
		return view('pages.pendeta.renungan.show', compact('renungan'));
	}

	/**
	 * Show the form for editing the specified renungan.
	 */
	public function edit($id)
	{
		$renungan = Renungan::findOrFail($id);
		return view('pages.pendeta.renungan.edit', compact('renungan'));
	}

	/**
	 * Update the specified renungan in storage.
	 */
	public function update(Request $request, $id)
	{
		$rules = [
			'judul' => 'required|string|max:255',
			'tanggal' => 'required|date',
			'konten' => 'nullable|string',
			'status' => 'required|in:draft,publish',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}

		try {
			$renungan = Renungan::findOrFail($id);
			$renungan->update([
				'judul' => $request->input('judul'),
				'tanggal' => $request->input('tanggal'),
				'konten' => $request->input('konten'),
				'status' => $request->input('status'),
			]);

			return redirect()->route('pendeta.renungan.index')->with('success', 'Renungan berhasil diperbarui.');
		} catch (\Throwable $e) {
			return redirect()->back()->withInput()->with('error', 'Gagal memperbarui renungan: ' . $e->getMessage());
		}
	}

	/**
	 * Remove the specified renungan from storage.
	 */
	public function destroy($id)
	{
		try {
			$renungan = Renungan::findOrFail($id);
			$renungan->delete();
			return redirect()->route('pendeta.renungan.index')->with('success', 'Renungan berhasil dihapus.');
		} catch (\Throwable $e) {
			return redirect()->back()->with('error', 'Gagal menghapus renungan: ' . $e->getMessage());
		}
	}

	/**
	 * Toggle status between draft and publish (AJAX)
	 */
	public function toggleStatus(Request $request, $id)
	{
		try {
			$renungan = Renungan::findOrFail($id);
			$new = $renungan->status === 'publish' ? 'draft' : 'publish';
			$renungan->status = $new;
			$renungan->save();

			return response()->json(['success' => true, 'status' => $new]);
		} catch (\Throwable $e) {
			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
		}
	}
}
