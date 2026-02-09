<?php

namespace App\Http\Controllers\Penatua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\KeluargaController;
use App\Models\Penatua;
use App\Models\Keluarga;
use App\Models\Jemaat;
use App\Models\Wijk;

class PenatuaJemaatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $penatua = Penatua::where('user_id', $user->id)->first();
        $wijkId = $penatua->wijk_id ?? null;

        $keluargas = Keluarga::where('wijk_id', $wijkId)
            ->with('jemaats')
            ->orderBy('created_at', 'desc')
            ->get();

        $jemaats = Jemaat::whereIn('keluarga_id', $keluargas->pluck('id')->toArray())->get();

        $pendingCount = 0;

        return view('pages.penatua.jemaat.index', compact('keluargas', 'jemaats', 'pendingCount'));
    }

    public function create()
    {
        $user = Auth::user();
        $penatua = Penatua::where('user_id', $user->id)->with('wijk')->first();
        $penatuaWijk = $penatua->wijk ?? null;
        $wijks = Wijk::orderBy('nama_wijk', 'asc')->get();

        return view('pages.penatua.jemaat.create', compact('penatuaWijk', 'wijks'));
    }

    public function store(Request $request)
    {
        try {
            Log::debug('PenatuaJemaatController::store called', ['input' => $request->all()]);

            // reuse keluarga creation and validation from KeluargaController
            $keluarga = KeluargaController::createFromRequest($request);
            Log::debug('Returned from KeluargaController::createFromRequest', ['keluarga_id' => $keluarga ? $keluarga->id : null]);

            // create kepala keluarga (suami) using same keys as admin form
            $kepala = [
                'keluarga_id' => $keluarga->id,
                'nama' => $request->input('suami_nama'),
                'jenis_kelamin' => $request->input('suami_jenis_kelamin'),
                'tempat_lahir' => $request->input('suami_tempat_lahir'),
                'tanggal_lahir' => $request->input('suami_tanggal_lahir'),
                'no_telp' => $request->input('suami_no_telp'),
                'hubungan_keluarga' => 'Kepala Keluarga',
            ];

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
            Log::debug('Kepala jemaat created (penatua)', ['id' => $createdKepala->id ?? null]);

            // handle anggota arrays (anggota_nama[], anggota_jenis_kelamin[], ...)
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

                    if (isset($fotoFiles[$i]) && $fotoFiles[$i]->isValid()) {
                        $anggotaData['foto'] = $fotoFiles[$i]->store('jemaats/foto', 'public');
                    }
                    if (isset($fileSidi[$i]) && $fileSidi[$i]->isValid()) {
                        $anggotaData['file_sidi'] = $fileSidi[$i]->store('jemaats/sidi', 'public');
                    }
                    if (isset($fileBaptis[$i]) && $fileBaptis[$i]->isValid()) {
                        $anggotaData['file_baptis'] = $fileBaptis[$i]->store('jemaats/baptis', 'public');
                    }

                    if (!empty($anggotaData['nama'])) {
                        $created = Jemaat::create($anggotaData);
                        Log::debug('Anggota created (penatua)', ['id' => $created->id ?? null]);
                    }
                }
            }

            return redirect()->route('penatua.jemaat')->with('success', 'Data jemaat dan keluarga berhasil disimpan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Penatua jemaat store error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan server: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $jemaat = Jemaat::with('keluarga')->findOrFail($id);
        return view('pages.penatua.jemaat.show', compact('jemaat'));
    }

    /**
     * Show the form for editing the specified jemaat (penatua).
     */
    public function edit($id)
    {
        $jemaat = Jemaat::with('keluarga.jemaats')->findOrFail($id);
        $keluarga = $jemaat->keluarga;
        $wijks = Wijk::orderBy('nama_wijk', 'asc')->get();
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

        $user = Auth::user();
        $penatua = Penatua::where('user_id', $user->id)->with('wijk')->first();
        $penatuaWijk = $penatua->wijk ?? null;

        return view('pages.penatua.jemaat.edit', compact('jemaat', 'keluarga', 'kepala', 'anggota', 'wijks', 'penatuaWijk'));
    }

    /**
     * Update the specified jemaat in storage (penatua).
     */
    public function update(Request $request, $id)
    {
        $jemaat = Jemaat::with('keluarga')->findOrFail($id);
        $keluarga = $jemaat->keluarga;

        $rules = [
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
            'nomor_registrasi' => 'required|string|max:255',
            'tanggal_registrasi' => 'required|date',
            'alamat' => 'required|string',
            // penatua should not change wijk via dropdown; allow nullable and enforce penatua's wijk server-side
            'wijk_id' => 'nullable|integer|exists:wijks,id',
            'tanggal_pernikahan' => 'nullable|date',
            'gereja_pemberkatan' => 'nullable|string|max:255',
            'pendeta_pemberkatan' => 'nullable|string|max:255',
            'akte_pernikahan' => 'nullable|file|max:5120',

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
            if ($keluarga) {
                $keluarga->nomor_registrasi = $validated['nomor_registrasi'];
                $keluarga->tanggal_registrasi = $validated['tanggal_registrasi'];
                $keluarga->alamat = $validated['alamat'];
                // enforce penatua's wijk if available
                $user = Auth::user();
                $penatua = Penatua::where('user_id', $user->id)->first();
                $penatuaWijkId = $penatua->wijk_id ?? null;
                $keluarga->wijk_id = $penatuaWijkId ?? ($validated['wijk_id'] ?? $keluarga->wijk_id);
                $keluarga->tanggal_pernikahan = $validated['tanggal_pernikahan'] ?? $keluarga->tanggal_pernikahan;
                $keluarga->gereja_pemberkatan = $validated['gereja_pemberkatan'] ?? $keluarga->gereja_pemberkatan;
                $keluarga->pendeta_pemberkatan = $validated['pendeta_pemberkatan'] ?? $keluarga->pendeta_pemberkatan;
                if ($request->hasFile('akte_pernikahan')) {
                    $keluarga->akte_pernikahan = $request->file('akte_pernikahan')->store('keluarga/akte', 'public');
                }
                $keluarga->save();
            }

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

            if (in_array($jemaat->hubungan_keluarga, ['Suami', 'Istri'])) {
                $jemaat->hubungan_keluarga = 'Kepala Keluarga';
                $jemaat->save();

                $currentKepala = Jemaat::where('keluarga_id', $jemaat->keluarga_id)
                    ->where('hubungan_keluarga', 'Kepala Keluarga')
                    ->where('id', '!=', $jemaat->id)
                    ->first();
                if ($currentKepala) {
                    $currentKepala->hubungan_keluarga = ($currentKepala->jenis_kelamin === 'P') ? 'Istri' : 'Suami';
                    $currentKepala->save();
                }
            }

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

            return redirect()->route('penatua.jemaat')->with('success', 'Data jemaat dan keluarga berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Penatua jemaat update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui jemaat: ' . $e->getMessage())->withInput();
        }
    }
}
