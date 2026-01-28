<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Wijk;
use App\Models\Jemaat;
use App\Models\Keluarga;
use App\Http\Controllers\KeluargaController;

class JemaatController extends Controller
{
	/**
	 * Display the admin jemaat index view.
	 */
	public function index()
	{

		$keluargas = Keluarga::with('jemaats')
			->withCount('jemaats')
			->orderBy('created_at', 'desc')
			->get();

		$totalJemaat = Jemaat::count();
		$pendingCount = 0; // adjust if you have a pending flag

		return view('pages.admin.MasterData.jemaat.index', compact('keluargas', 'totalJemaat', 'pendingCount'));
	}

	/**
	 * Assign a role to a jemaat (AJAX stub).
	 * Accepts `role` = 'penatua'|'pendeta' and returns JSON.
	 */
	public function assignRole(Request $request, $id)
	{
		$validated = $request->validate([
			'role' => 'required|string|in:penatua,pendeta'
		]);

		$jemaat = Jemaat::findOrFail($id);
		// For now this is a backend stub: log and return success JSON.
		// TODO: implement actual creation of Penatua/Pendeta records as needed.
		\Illuminate\Support\Facades\Log::info('assignRole called', ['jemaat_id' => $id, 'role' => $validated['role']]);

		return response()->json([
			'success' => true,
			'message' => 'Permintaan "' . $validated['role'] . '" diterima untuk jemaat: ' . ($jemaat->nama ?? $jemaat->id)
		]);
	}

	/**
	 * Show the form for creating a new jemaat (admin).
	 */
	public function create()
	{
		$wijks = Wijk::orderBy('nama_wijk', 'asc')->get();
		return view('pages.admin.MasterData.jemaat.create', compact('wijks'));
	}

	/**
	 * AJAX search for jemaat by name. Returns JSON array of matches.
	 */
	public function ajaxSearch(Request $request)
	{
		$q = $request->query('q', '');
		if (strlen(trim($q)) < 1) {
			return response()->json([]);
		}

		$results = Jemaat::where('nama', 'like', '%' . $q . '%')
			->with('keluarga.wijk')
			->orderBy('nama', 'asc')
			->limit(15)
			->get();

		$mapped = $results->map(function ($j) {
			return [
				'id' => $j->id,
				'nama' => $j->nama,
				'no_telp' => $j->no_telp,
				'keluarga_id' => $j->keluarga_id,
				'wijk_id' => $j->keluarga && $j->keluarga->wijk ? $j->keluarga->wijk->id : null,
				'wijk_name' => $j->keluarga && $j->keluarga->wijk ? $j->keluarga->wijk->nama_wijk : null,
			];
		});

		return response()->json($mapped);
	}

	/**
	 * Placeholder store method for admin jemaat create form.
	 */
	public function store(Request $request)
	{
		try {
			Log::debug('JemaatController::store called', ['input' => $request->all()]);
			$keluarga = KeluargaController::createFromRequest($request);
			Log::debug('Returned from KeluargaController::createFromRequest', ['keluarga_id' => $keluarga ? $keluarga->id : null]);
			$kepala = [
				'keluarga_id' => $keluarga->id,
				'nama' => $request->input('suami_nama'),
				'jenis_kelamin' => $request->input('suami_jenis_kelamin'),
				'tempat_lahir' => $request->input('suami_tempat_lahir'),
				'tanggal_lahir' => $request->input('suami_tanggal_lahir'),
				'no_telp' => $request->input('suami_no_telp'),
				'hubungan_keluarga' => 'Kepala Keluarga',
			];

			// handle kepala files
			if ($request->hasFile('suami_foto')) {
				$kepala['foto'] = $request->file('suami_foto')->store('jemaats/foto', 'public');
			}
			if ($request->hasFile('suami_file_sidi')) {
				$kepala['file_sidi'] = $request->file('suami_file_sidi')->store('jemaats/sidi', 'public');
			}
			if ($request->hasFile('suami_file_baptis')) {
				$kepala['file_baptis'] = $request->file('suami_file_baptis')->store('jemaats/baptis', 'public');
			}

			$kepala['tanggal_sidi'] = $request->input('suami_tanggal_sidi');
			$kepala['tanggal_baptis'] = $request->input('suami_tanggal_baptis');

			$createdKepala = Jemaat::create($kepala);
			Log::debug('Kepala jemaat created', ['id' => $createdKepala->id, 'data' => $createdKepala->toArray()]);

			// handle anggota keluarga jika ada (format asumsi: anggota_nama[], anggota_jenis_kelamin[], ...)
			$names = $request->input('anggota_nama', []);
			if (is_array($names) && count($names) > 0) {
				$count = count($names);

				$jenisArr = $request->input('anggota_jenis_kelamin', []);
				$tempatArr = $request->input('anggota_tempat_lahir', []);
				$tanggalArr = $request->input('anggota_tanggal_lahir', []);
				$noArr = $request->input('anggota_no_telp', []);
				$hubArr = $request->input('anggota_hubungan', []);
				$sidiTanggalArr = $request->input('anggota_tanggal_sidi', []);
				$baptisTanggalArr = $request->input('anggota_tanggal_baptis', []);

				$fotoFiles = $request->file('anggota_foto') ?: [];
				$fileSidi = $request->file('anggota_file_sidi') ?: [];
				$fileBaptis = $request->file('anggota_file_baptis') ?: [];

				for ($i = 0; $i < $count; $i++) {
					$anggotaData = [
						'keluarga_id' => $keluarga->id,
						'nama' => $names[$i] ?? null,
						'jenis_kelamin' => $jenisArr[$i] ?? null,
						'tempat_lahir' => $tempatArr[$i] ?? null,
						'tanggal_lahir' => $tanggalArr[$i] ?? null,
						'no_telp' => $noArr[$i] ?? null,
						'hubungan_keluarga' => $hubArr[$i] ?? 'Anak',
						'tanggal_sidi' => $sidiTanggalArr[$i] ?? null,
						'tanggal_baptis' => $baptisTanggalArr[$i] ?? null,
					];

					// handle uploaded files per anggota if present
					if (isset($fotoFiles[$i]) && $fotoFiles[$i]->isValid()) {
						$anggotaData['foto'] = $fotoFiles[$i]->store('jemaats/foto', 'public');
					}
					if (isset($fileSidi[$i]) && $fileSidi[$i]->isValid()) {
						$anggotaData['file_sidi'] = $fileSidi[$i]->store('jemaats/sidi', 'public');
					}
					if (isset($fileBaptis[$i]) && $fileBaptis[$i]->isValid()) {
						$anggotaData['file_baptis'] = $fileBaptis[$i]->store('jemaats/baptis', 'public');
					}

					$created = Jemaat::create($anggotaData);
					Log::debug('Anggota created', ['id' => $created->id ?? null, 'data' => $created ? $created->toArray() : null]);
				}
			}

			return redirect()->route('admin.jemaat')->with('success', 'Data jemaat dan keluarga berhasil disimpan.');
		} catch (\Illuminate\Validation\ValidationException $e) {
			return redirect()->back()->withErrors($e->validator)->withInput();
		} catch (\Exception $e) {
			Log::error('Jemaat store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
			return redirect()->back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage())->withInput();
		}
	}

	/**
	 * Remove the specified jemaat.
	 */
	public function destroy($id)
	{
		try {
			$jemaat = Jemaat::findOrFail($id);

			// Find current kepala (explicit 'Kepala Keluarga') if exists
			$currentKepala = Jemaat::where('keluarga_id', $jemaat->keluarga_id)
				->where('hubungan_keluarga', 'Kepala Keluarga')
				->first();

			// Determine if the member being deleted is considered the kepala
			// Case A: there is an explicit 'Kepala Keluarga' and it's this member
			// Case B: no explicit kepala but this member is labeled as 'Suami' (treated as kepala)
			$isKepala = false;
			if ($currentKepala) {
				$isKepala = ($currentKepala->id === $jemaat->id);
			} else {
				$isKepala = ($jemaat->hubungan_keluarga === 'Suami');
			}

			if ($isKepala) {
				// If the deleted kepala is male (Suami or male Kepala), promote Istri to Kepala Keluarga (if exists)
				if ($jemaat->jenis_kelamin === 'L') {
					$istri = Jemaat::where('keluarga_id', $jemaat->keluarga_id)
						->where('hubungan_keluarga', 'Istri')
						->first();
					if ($istri) {
						$istri->hubungan_keluarga = 'Kepala Keluarga';
						$istri->save();
					}
				}
				// If deleted kepala is female, do NOT promote anyone (leave kepala empty)
			}

			$jemaat->delete();
			return redirect()->back()->with('success', 'Jemaat berhasil dihapus.');
		} catch (\Exception $e) {
			Log::error('Jemaat destroy error: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal menghapus jemaat: ' . $e->getMessage());
		}
	}

	/**
	 * Show the form for editing the specified jemaat.
	 */
	public function edit($id)
	{
		$jemaat = Jemaat::with('keluarga.jemaats')->findOrFail($id);
		$keluarga = $jemaat->keluarga;
		$wijks = Wijk::orderBy('nama_wijk', 'asc')->get();
		// determine kepala and anggota
		$kepala = null;
		$anggota = collect();
		if ($keluarga) {
			$kepala = $keluarga->jemaats->firstWhere('hubungan_keluarga', 'Kepala Keluarga');
			if (!$kepala) {
				$kepala = $keluarga->jemaats->first();
			}
			$anggota = $keluarga->jemaats->filter(function ($j) use ($kepala) {
				return $kepala ? $j->id !== $kepala->id : true;
			});
		}
		return view('pages.admin.MasterData.jemaat.edit', compact('jemaat', 'keluarga', 'kepala', 'anggota', 'wijks'));
	}

	/**
	 * Update the specified jemaat in storage.
	 */
	public function update(Request $request, $id)
	{
		$jemaat = Jemaat::with('keluarga')->findOrFail($id);
		$keluarga = $jemaat->keluarga;

		$rules = [
			// kepala fields (form uses suami_* names)
			'suami_nama' => 'required|string|max:255',
			'suami_jenis_kelamin' => 'nullable|in:L,P',
			'suami_tempat_lahir' => 'nullable|string|max:255',
			'suami_tanggal_lahir' => 'nullable|date',
			'suami_no_telp' => 'nullable|string|max:50',
			'suami_hubungan' => 'nullable|string|max:100',
			'suami_tanggal_sidi' => 'nullable|date',
			'suami_tanggal_baptis' => 'nullable|date',
			'suami_foto' => 'nullable|file|image|max:5120',
			'suami_file_sidi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
			'suami_file_baptis' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
			// keluarga fields
			'nomor_registrasi' => 'required|string|max:255',
			'tanggal_registrasi' => 'required|date',
			'alamat' => 'required|string',
			'wijk_id' => 'required|integer|exists:wijks,id',
			'tanggal_pernikahan' => 'nullable|date',
			'gereja_pemberkatan' => 'nullable|string|max:255',
			'pendeta_pemberkatan' => 'nullable|string|max:255',
			'akte_pernikahan' => 'nullable|file|max:5120',

			// anggota arrays
			'anggota_id' => 'nullable|array',
			'anggota_nama' => 'nullable|array',
			'anggota_nama.*' => 'nullable|string|max:255',
			'anggota_jenis_kelamin' => 'nullable|array',
			'anggota_jenis_kelamin.*' => 'nullable|in:L,P',
			'anggota_hubungan' => 'nullable|array',
			'anggota_hubungan.*' => 'nullable|string|max:100',
			'anggota_tempat_lahir' => 'nullable|array',
			'anggota_tempat_lahir.*' => 'nullable|string|max:255',
			'anggota_tanggal_lahir' => 'nullable|array',
			'anggota_tanggal_lahir.*' => 'nullable|date',
			'anggota_no_telp' => 'nullable|array',
			'anggota_no_telp.*' => 'nullable|string|max:50',
			'anggota_tanggal_sidi' => 'nullable|array',
			'anggota_tanggal_sidi.*' => 'nullable|date',
			'anggota_tanggal_baptis' => 'nullable|array',
			'anggota_tanggal_baptis.*' => 'nullable|date',
			'anggota_foto' => 'nullable|array',
			'anggota_foto.*' => 'nullable|file|image|max:5120',
			'anggota_file_sidi' => 'nullable|array',
			'anggota_file_sidi.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
			'anggota_file_baptis' => 'nullable|array',
			'anggota_file_baptis.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
		];

		$validated = $request->validate($rules);

		try {
			// update keluarga if exists
			if ($keluarga) {
				$keluarga->nomor_registrasi = $validated['nomor_registrasi'];
				$keluarga->tanggal_registrasi = $validated['tanggal_registrasi'];
				$keluarga->alamat = $validated['alamat'];
				$keluarga->wijk_id = $validated['wijk_id'];
				$keluarga->tanggal_pernikahan = $validated['tanggal_pernikahan'] ?? $keluarga->tanggal_pernikahan;
				$keluarga->gereja_pemberkatan = $validated['gereja_pemberkatan'] ?? $keluarga->gereja_pemberkatan;
				$keluarga->pendeta_pemberkatan = $validated['pendeta_pemberkatan'] ?? $keluarga->pendeta_pemberkatan;
				if ($request->hasFile('akte_pernikahan')) {
					$keluarga->akte_pernikahan = $request->file('akte_pernikahan')->store('keluarga/akte', 'public');
				}
				$keluarga->save();
			}

			// update the jemaat being edited using suami_* inputs (form structure)
			$jemaat->nama = $validated['suami_nama'];
			$jemaat->jenis_kelamin = $validated['suami_jenis_kelamin'] ?? $jemaat->jenis_kelamin;
			$jemaat->tempat_lahir = $validated['suami_tempat_lahir'] ?? $jemaat->tempat_lahir;
			$jemaat->tanggal_lahir = $validated['suami_tanggal_lahir'] ?? $jemaat->tanggal_lahir;
			$jemaat->no_telp = $validated['suami_no_telp'] ?? $jemaat->no_telp;
			$jemaat->hubungan_keluarga = $validated['suami_hubungan'] ?? $jemaat->hubungan_keluarga;
			$jemaat->tanggal_sidi = $validated['suami_tanggal_sidi'] ?? $jemaat->tanggal_sidi;
			$jemaat->tanggal_baptis = $validated['suami_tanggal_baptis'] ?? $jemaat->tanggal_baptis;

			if ($request->hasFile('suami_foto')) {
				$jemaat->foto = $request->file('suami_foto')->store('jemaats/foto', 'public');
			}
			if ($request->hasFile('suami_file_sidi')) {
				$jemaat->file_sidi = $request->file('suami_file_sidi')->store('jemaats/sidi', 'public');
			}
			if ($request->hasFile('suami_file_baptis')) {
				$jemaat->file_baptis = $request->file('suami_file_baptis')->store('jemaats/baptis', 'public');
			}

			$jemaat->save();

			// If the edited member's hubungan is Suami or Istri, make them Kepala Keluarga
			if (in_array($jemaat->hubungan_keluarga, ['Suami', 'Istri'])) {
				$jemaat->hubungan_keluarga = 'Kepala Keluarga';
				$jemaat->save();

				// demote any other kepala in the same keluarga
				$currentKepala = Jemaat::where('keluarga_id', $jemaat->keluarga_id)
					->where('hubungan_keluarga', 'Kepala Keluarga')
					->where('id', '!=', $jemaat->id)
					->first();
				if ($currentKepala) {
					$currentKepala->hubungan_keluarga = ($currentKepala->jenis_kelamin === 'P') ? 'Istri' : 'Suami';
					$currentKepala->save();
				}
			}

			// --- Sync anggota: delete removed, update existing, create new ---
			if ($keluarga) {
				$existingAnggota = Jemaat::where('keluarga_id', $keluarga->id)->where('id', '!=', $jemaat->id)->get();
				$existingIds = $existingAnggota->pluck('id')->toArray();

				$submittedIds = array_filter(
					array_map('intval', (array) $request->input('anggota_id', [])),
					function ($v) {
						return $v > 0;
					}
				);

				$toDelete = array_diff($existingIds, $submittedIds);
				foreach ($toDelete as $delId) {
					$del = Jemaat::find($delId);
					if ($del) $del->delete();
				}

				// prepare arrays
				$names = (array) $request->input('anggota_nama', []);
				$jenisArr = (array) $request->input('anggota_jenis_kelamin', []);
				$tempatArr = (array) $request->input('anggota_tempat_lahir', []);
				$tanggalArr = (array) $request->input('anggota_tanggal_lahir', []);
				$noArr = (array) $request->input('anggota_no_telp', []);
				$hubArr = (array) $request->input('anggota_hubungan', []);
				$sidiTanggalArr = (array) $request->input('anggota_tanggal_sidi', []);
				$baptisTanggalArr = (array) $request->input('anggota_tanggal_baptis', []);

				$fotoFiles = $request->file('anggota_foto') ?: [];
				$fileSidi = $request->file('anggota_file_sidi') ?: [];
				$fileBaptis = $request->file('anggota_file_baptis') ?: [];

				$ids = (array) $request->input('anggota_id', []);
				$count = max(count($names), count($ids));
				for ($i = 0; $i < $count; $i++) {
					$aid = isset($ids[$i]) && $ids[$i] ? intval($ids[$i]) : null;
					$data = [
						'keluarga_id' => $keluarga->id,
						'nama' => $names[$i] ?? null,
						'jenis_kelamin' => $jenisArr[$i] ?? null,
						'tempat_lahir' => $tempatArr[$i] ?? null,
						'tanggal_lahir' => $tanggalArr[$i] ?? null,
						'no_telp' => $noArr[$i] ?? null,
						'hubungan_keluarga' => $hubArr[$i] ?? 'Anak',
						'tanggal_sidi' => $sidiTanggalArr[$i] ?? null,
						'tanggal_baptis' => $baptisTanggalArr[$i] ?? null,
					];

					if ($aid) {
						$member = Jemaat::find($aid);
						if ($member) {
							$member->fill($data);
							if (isset($fotoFiles[$i]) && $fotoFiles[$i] && $fotoFiles[$i]->isValid()) {
								$member->foto = $fotoFiles[$i]->store('jemaats/foto', 'public');
							}
							if (isset($fileSidi[$i]) && $fileSidi[$i] && $fileSidi[$i]->isValid()) {
								$member->file_sidi = $fileSidi[$i]->store('jemaats/sidi', 'public');
							}
							if (isset($fileBaptis[$i]) && $fileBaptis[$i] && $fileBaptis[$i]->isValid()) {
								$member->file_baptis = $fileBaptis[$i]->store('jemaats/baptis', 'public');
							}
							$member->save();
						}
					} else {
						// create new anggota
						if (!empty($data['nama'])) {
							if (isset($fotoFiles[$i]) && $fotoFiles[$i] && $fotoFiles[$i]->isValid()) {
								$data['foto'] = $fotoFiles[$i]->store('jemaats/foto', 'public');
							}
							if (isset($fileSidi[$i]) && $fileSidi[$i] && $fileSidi[$i]->isValid()) {
								$data['file_sidi'] = $fileSidi[$i]->store('jemaats/sidi', 'public');
							}
							if (isset($fileBaptis[$i]) && $fileBaptis[$i] && $fileBaptis[$i]->isValid()) {
								$data['file_baptis'] = $fileBaptis[$i]->store('jemaats/baptis', 'public');
							}
							Jemaat::create($data);
						}
					}
				}
			}

			return redirect()->route('admin.jemaat')->with('success', 'Data jemaat dan keluarga berhasil diperbarui.');
		} catch (\Exception $e) {
			Log::error('Jemaat update error: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal memperbarui jemaat: ' . $e->getMessage())->withInput();
		}
	}
}
