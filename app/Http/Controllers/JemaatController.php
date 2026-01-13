<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jemaat;
use App\Models\Keluarga;
use App\Models\Wijk;
use App\Models\StatusBaptis;
use App\Models\StatusSidi;
use App\Models\StatusPernikahan;
use App\Models\StatusPindah;
use App\Models\StatusKedukaan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JemaatController extends Controller
{
    /**
     * Show the form for creating a new jemaat.
     */
    public function create()
    {
        $wijks = Wijk::all();
        return view('pages.admin.MasterData.jemaat.create', compact('wijks'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Exclude pending submissions from main admin list
        $jemaats = Jemaat::with('keluarga')
            ->where(function($q){
                $q->where('status', 'approved')->orWhereNull('status');
            })
            ->orderBy('nama_lengkap')
            ->get();
        $kkCount = Keluarga::count();
        $totalJemaat = Jemaat::where(function($q){
            $q->where('status', 'approved')->orWhereNull('status');
        })->count();
        // pending count for admin UI
        $pendingCount = Jemaat::where('status', 'pending')->count();
        return view('pages.admin.MasterData.jemaat.index', compact('jemaats', 'kkCount', 'totalJemaat'));
    }

    /**
     * Store a newly created jemaat and keluarga.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_jemaat' => 'required|string|unique:jemaats,nomor_jemaat',
            'nomor_keluarga' => 'required|string',
            'nama_lengkap' => 'required|string',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string',
            'rw' => 'nullable|string',
            'wijk' => 'nullable|integer|exists:wijks,id',
            'kelurahan' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kabupaten' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kode_pos' => 'nullable|string',
            'email' => 'nullable|email',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'no_hp' => 'nullable|string',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'status_pernikahan' => 'nullable|in:belum,sudah',
            'status_jemaat' => 'nullable|in:hidup,wafat',
            'hubungan_keluarga' => 'nullable|string',
            'anak_ke' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Determine wijk (use selected id, otherwise ensure default 'Umum' exists)
        if ($request->filled('wijk')) {
            $wijk = Wijk::find($request->input('wijk'));
        } else {
            $wijk = Wijk::firstOrCreate(['nama_wijk' => 'Umum']);
        }

        // Create keluarga (if nomor_keluarga already exists, use existing)
        $keluarga = Keluarga::firstOrCreate(
            ['nomor_keluarga' => $request->input('nomor_keluarga')],
            [
                'alamat' => $request->input('alamat'),
                'rt' => $request->input('rt'),
                'rw' => $request->input('rw'),
                'wijk_id' => $wijk->id,
                'kelurahan' => $request->input('kelurahan'),
                'kecamatan' => $request->input('kecamatan'),
                'kabupaten' => $request->input('kabupaten'),
                'provinsi' => $request->input('provinsi'),
                'kode_pos' => $request->input('kode_pos'),
            ]
        );

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('jemaats', 'public');
        }

        // Log incoming relevant fields for debugging
        Log::info('Jemaat store payload', $request->only([
            'nomor_jemaat','nama_lengkap','status_jemaat','tgl_wafat','no_surat_kematian','keterangan_wafat'
        ]));
        // Log full payload (except file) to help debugging missing fields
        Log::info('Jemaat full payload', $request->except(['_token', 'foto']));

        // Create jemaat and related status records inside a transaction
        DB::beginTransaction();
        try {
            // Create jemaat
            $jemaat = Jemaat::create([
            'nomor_jemaat' => $request->input('nomor_jemaat'),
            'status' => 'approved',
            'nama_lengkap' => $request->input('nama_lengkap'),
            'keluarga_id' => $keluarga->id,
            'email' => $request->input('email'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'no_hp' => $request->input('no_hp'),
            'nama_ayah' => $request->input('nama_ayah'),
            'nama_ibu' => $request->input('nama_ibu'),
            'status_pernikahan' => $request->input('status_pernikahan'),
            'hubungan_keluarga' => $request->input('hubungan_keluarga'),
            'anak_ke' => $request->input('anak_ke'),
            'keterangan' => $request->input('keterangan'),
            'foto' => $fotoPath,
            ]);

            // --- Save related status records when provided ---
        // Baptis
        if ($request->filled('status_baptis')) {
            $b = new StatusBaptis();
            $b->jemaat_id = $jemaat->id;
            $b->status = $request->input('status_baptis');
            $b->jenis = $request->input('jenis_gereja');
            if ($request->input('jenis_gereja') === 'luar') {
                $b->nomor_surat = $request->input('luar_nomor_kartu');
                $b->tanggal = $request->input('luar_tgl_baptis');
                $b->pendeta = $request->input('luar_baptis_oleh');
                $b->nama_gereja = $request->input('luar_nama_gereja');
                $b->alamat = $request->input('luar_alamat_gereja');
                $b->kota = $request->input('luar_kota');
            } else {
                $b->nomor_surat = $request->input('lokal_nomor_kartu');
                $b->tanggal = $request->input('lokal_tgl_baptis');
                $b->pendeta = $request->input('lokal_baptis_oleh');
            }
            $b->save();
        }

        // Sidi
        if ($request->filled('status_sidi')) {
            $s = new StatusSidi();
            $s->jemaat_id = $jemaat->id;
            $s->status = $request->input('status_sidi');
            $s->jenis = $request->input('jenis_gereja_sidi');
            if ($request->input('jenis_gereja_sidi') === 'luar') {
                $s->nomor_surat = $request->input('sidi_luar_nomor_kartu');
                $s->tanggal = $request->input('sidi_luar_tgl');
                $s->pendeta = $request->input('sidi_luar_oleh');
                $s->nama_gereja = $request->input('sidi_luar_nama_gereja');
                $s->alamat = $request->input('sidi_luar_alamat_gereja');
                $s->kota = $request->input('sidi_luar_kota');
            } else {
                $s->nomor_surat = $request->input('sidi_lokal_nomor_kartu');
                $s->tanggal = $request->input('sidi_lokal_tgl');
                $s->pendeta = $request->input('sidi_lokal_oleh');
            }
            $s->save();
        }

        // Pernikahan
        if ($request->filled('status_nikah')) {
            $p = new StatusPernikahan();
            $p->jemaat_id = $jemaat->id;
            $p->status = $request->input('status_nikah');
            $p->jenis = $request->input('jenis_gereja_nikah');
            if ($request->input('jenis_gereja_nikah') === 'luar') {
                $p->nomor_surat = $request->input('nikah_luar_nomor_kartu');
                $p->tanggal = $request->input('nikah_luar_tgl');
                $p->pendeta = $request->input('nikah_luar_oleh');
                $p->nama_gereja = $request->input('nikah_luar_nama_gereja');
                $p->alamat = $request->input('nikah_luar_alamat_gereja');
                $p->kota = $request->input('nikah_luar_kota');
            } else {
                $p->nomor_surat = $request->input('nikah_lokal_nomor_kartu');
                $p->tanggal = $request->input('nikah_lokal_tgl');
                $p->pendeta = $request->input('nikah_lokal_oleh');
            }
            $p->save();
        }

        // Pindah
        if ($request->filled('status_pindah')) {
            $pd = new StatusPindah();
            $pd->jemaat_id = $jemaat->id;
            $pd->status = $request->input('status_pindah');
            if ($request->input('status_pindah') === 'pindah_masuk') {
                $pd->nomor_surat = $request->input('no_surat_pindah');
                $pd->tanggal = $request->input('tgl_pindah_masuk');
                $pd->nama_gereja = $request->input('gereja_asal');
                $pd->kota = $request->input('kota_gereja_asal');
            }
            $pd->save();
        }

            // Kedukaan / Wafat
            if ($request->has('status_jemaat')) {
                Log::info('status_jemaat present', ['value' => $request->input('status_jemaat')]);
            } else {
                Log::info('status_jemaat NOT present in request');
            }

            // Create StatusKedukaan record when status_jemaat is provided (hidup or wafat).
            if ($request->filled('status_jemaat')) {
                $k = new StatusKedukaan();
                $k->jemaat_id = $jemaat->id;
                $k->status = $request->input('status_jemaat');

                // Only set death-specific fields when status is 'wafat'
                if ($request->input('status_jemaat') === 'wafat') {
                    $k->tanggal = $request->input('tgl_wafat');
                    $k->nomor_surat = $request->input('no_surat_kematian');
                    $k->keterangan = $request->input('keterangan_wafat');
                }

                Log::info('About to save StatusKedukaan', [
                    'attrs' => [
                        'jemaat_id' => $k->jemaat_id,
                        'status' => $k->status,
                        'tanggal' => $k->tanggal ?? null,
                        'nomor_surat' => $k->nomor_surat ?? null,
                        'keterangan' => $k->keterangan ?? null,
                    ]
                ]);

                $saved = $k->save();
                Log::info('Saved StatusKedukaan', ['jemaat_id' => $jemaat->id, 'saved' => $saved, 'id' => $k->id ?? null]);
            } else {
                Log::info('Kedukaan not saved - status_jemaat not filled', [
                    'filled' => $request->filled('status_jemaat'),
                    'value' => $request->input('status_jemaat')
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed saving jemaat with statuses: '.$e->getMessage());
            throw $e;
        }

        return redirect()->route('admin.jemaat')->with('success', 'Data jemaat berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified jemaat.
     */
    public function edit($id)
    {
        $jemaat = Jemaat::with('keluarga')->findOrFail($id);
        $wijks = Wijk::all();
        // eager load status relations (defined on model)
        $jemaat->load('statusBaptis', 'statusSidi', 'statusPernikahan', 'statusPindah', 'statusKedukaan');
        // Debug: log the kedukaan relation to verify data exists and status value
        try {
            Log::info('edit() loaded statusKedukaan', ['statusKedukaan' => optional($jemaat->statusKedukaan)->toArray() ?? null]);
        } catch (\Throwable $e) {
            Log::warning('Failed to log statusKedukaan in edit()', ['error' => $e->getMessage()]);
        }
        return view('pages.admin.MasterData.jemaat.edit', compact('jemaat', 'wijks'));
    }

    /**
     * Display the specified jemaat (read-only) for admin review.
     */
    public function show($id)
    {
        $jemaat = Jemaat::with(['keluarga', 'statusBaptis', 'statusSidi', 'statusPernikahan', 'statusPindah', 'statusKedukaan'])
            ->findOrFail($id);

        return view('pages.admin.MasterData.jemaat.show', compact('jemaat'));
    }

    /**
     * Update the specified jemaat in storage.
     */
    public function update(Request $request, $id)
    {
        $jemaat = Jemaat::findOrFail($id);

        $data = $request->validate([
            'nomor_jemaat' => 'required|string|unique:jemaats,nomor_jemaat,'.$id,
            'nomor_keluarga' => 'required|string',
            'nama_lengkap' => 'required|string',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|string',
            'rw' => 'nullable|string',
            'wijk' => 'nullable|integer|exists:wijks,id',
            'kelurahan' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kabupaten' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kode_pos' => 'nullable|string',
            'email' => 'nullable|email',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'no_hp' => 'nullable|string',
            'nama_ayah' => 'nullable|string',
            'nama_ibu' => 'nullable|string',
            'status_pernikahan' => 'nullable|in:belum,sudah',
            'status_jemaat' => 'nullable|in:hidup,wafat',
            'hubungan_keluarga' => 'nullable|string',
            'anak_ke' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('wijk')) {
            $wijk = Wijk::find($request->input('wijk'));
        } else {
            $wijk = Wijk::firstOrCreate(['nama_wijk' => 'Umum']);
        }

        // update or create keluarga
        $keluarga = Keluarga::firstOrCreate(
            ['nomor_keluarga' => $request->input('nomor_keluarga')],
            [
                'alamat' => $request->input('alamat'),
                'rt' => $request->input('rt'),
                'rw' => $request->input('rw'),
                'wijk_id' => $wijk->id,
                'kelurahan' => $request->input('kelurahan'),
                'kecamatan' => $request->input('kecamatan'),
                'kabupaten' => $request->input('kabupaten'),
                'provinsi' => $request->input('provinsi'),
                'kode_pos' => $request->input('kode_pos'),
            ]
        );

        // handle foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('jemaats', 'public');
        } else {
            $fotoPath = $jemaat->foto;
        }

        DB::beginTransaction();
        try {
            // update jemaat
            $jemaat->update([
                'nomor_jemaat' => $request->input('nomor_jemaat'),
                'nama_lengkap' => $request->input('nama_lengkap'),
                'keluarga_id' => $keluarga->id,
                'email' => $request->input('email'),
                'tempat_lahir' => $request->input('tempat_lahir'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'no_hp' => $request->input('no_hp'),
                'nama_ayah' => $request->input('nama_ayah'),
                'nama_ibu' => $request->input('nama_ibu'),
                'status_pernikahan' => $request->input('status_pernikahan'),
                'hubungan_keluarga' => $request->input('hubungan_keluarga'),
                'anak_ke' => $request->input('anak_ke'),
                'keterangan' => $request->input('keterangan'),
                'foto' => $fotoPath,
            ]);

            // Update or create statuses similar to store
            if ($request->filled('status_baptis')) {
                StatusBaptis::updateOrCreate(
                    ['jemaat_id' => $jemaat->id],
                    array_merge([
                        'status' => $request->input('status_baptis'),
                        'jenis' => $request->input('jenis_gereja'),
                    ], $request->input('jenis_gereja') === 'luar' ? [
                        'nomor_surat' => $request->input('luar_nomor_kartu'),
                        'tanggal' => $request->input('luar_tgl_baptis'),
                        'pendeta' => $request->input('luar_baptis_oleh'),
                        'nama_gereja' => $request->input('luar_nama_gereja'),
                        'alamat' => $request->input('luar_alamat_gereja'),
                        'kota' => $request->input('luar_kota'),
                    ] : [
                        'nomor_surat' => $request->input('lokal_nomor_kartu'),
                        'tanggal' => $request->input('lokal_tgl_baptis'),
                        'pendeta' => $request->input('lokal_baptis_oleh'),
                    ])
                );
            }

            if ($request->filled('status_sidi')) {
                StatusSidi::updateOrCreate(
                    ['jemaat_id' => $jemaat->id],
                    array_merge([
                        'status' => $request->input('status_sidi'),
                        'jenis' => $request->input('jenis_gereja_sidi'),
                    ], $request->input('jenis_gereja_sidi') === 'luar' ? [
                        'nomor_surat' => $request->input('sidi_luar_nomor_kartu'),
                        'tanggal' => $request->input('sidi_luar_tgl'),
                        'pendeta' => $request->input('sidi_luar_oleh'),
                        'nama_gereja' => $request->input('sidi_luar_nama_gereja'),
                        'alamat' => $request->input('sidi_luar_alamat_gereja'),
                        'kota' => $request->input('sidi_luar_kota'),
                    ] : [
                        'nomor_surat' => $request->input('sidi_lokal_nomor_kartu'),
                        'tanggal' => $request->input('sidi_lokal_tgl'),
                        'pendeta' => $request->input('sidi_lokal_oleh'),
                    ])
                );
            }

            if ($request->filled('status_nikah')) {
                StatusPernikahan::updateOrCreate(
                    ['jemaat_id' => $jemaat->id],
                    array_merge([
                        'status' => $request->input('status_nikah'),
                        'jenis' => $request->input('jenis_gereja_nikah'),
                    ], $request->input('jenis_gereja_nikah') === 'luar' ? [
                        'nomor_surat' => $request->input('nikah_luar_nomor_kartu'),
                        'tanggal' => $request->input('nikah_luar_tgl'),
                        'pendeta' => $request->input('nikah_luar_oleh'),
                        'nama_gereja' => $request->input('nikah_luar_nama_gereja'),
                        'alamat' => $request->input('nikah_luar_alamat_gereja'),
                        'kota' => $request->input('nikah_luar_kota'),
                    ] : [
                        'nomor_surat' => $request->input('nikah_lokal_nomor_kartu'),
                        'tanggal' => $request->input('nikah_lokal_tgl'),
                        'pendeta' => $request->input('nikah_lokal_oleh'),
                    ])
                );
            }

            if ($request->filled('status_pindah')) {
                $pdAttrs = ['status' => $request->input('status_pindah')];
                if ($request->input('status_pindah') === 'pindah_masuk') {
                    $pdAttrs = array_merge($pdAttrs, [
                        'nomor_surat' => $request->input('no_surat_pindah'),
                        'tanggal' => $request->input('tgl_pindah_masuk'),
                        'nama_gereja' => $request->input('gereja_asal'),
                        'kota' => $request->input('kota_gereja_asal'),
                    ]);
                }
                StatusPindah::updateOrCreate(['jemaat_id' => $jemaat->id], $pdAttrs);
            }

            if ($request->filled('status_jemaat')) {
                $kAttrs = ['status' => $request->input('status_jemaat')];
                if ($request->input('status_jemaat') === 'wafat') {
                    $kAttrs = array_merge($kAttrs, [
                        'tanggal' => $request->input('tgl_wafat'),
                        'nomor_surat' => $request->input('no_surat_kematian'),
                        'keterangan' => $request->input('keterangan_wafat'),
                    ]);
                }
                StatusKedukaan::updateOrCreate(['jemaat_id' => $jemaat->id], $kAttrs);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed updating jemaat: '.$e->getMessage());
            throw $e;
        }

        return redirect()->route('admin.jemaat')->with('success', 'Data jemaat berhasil diperbarui.');
    }

    /**
     * Remove the specified jemaat from storage.
     */
    public function destroy($id)
    {
        $jemaat = Jemaat::findOrFail($id);
        DB::beginTransaction();
        try {
            // remove stored photo if exists
            if (!empty($jemaat->foto)) {
                Storage::disk('public')->delete($jemaat->foto);
            }

            $jemaat->delete();
            DB::commit();
            return redirect()->route('admin.jemaat')->with('success', 'Data jemaat berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed deleting jemaat: '.$e->getMessage());
            return redirect()->route('admin.jemaat')->with('error', 'Gagal menghapus jemaat.');
        }
    }

    /**
     * Show list of jemaat awaiting admin approval.
     */
    public function pending()
    {
        $jemaats = Jemaat::with('keluarga')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return view('pages.admin.MasterData.jemaat.pending', compact('jemaats'));
    }

    /**
     * Approve a pending jemaat.
     */
    public function approve($id)
    {
        $jemaat = Jemaat::findOrFail($id);
        $jemaat->update(['status' => 'approved']);
        return redirect()->route('admin.jemaat.pending')->with('success', 'Jemaat telah disetujui.');
    }

    /**
     * Reject a pending jemaat.
     */
    public function reject($id)
    {
        $jemaat = Jemaat::findOrFail($id);
        $jemaat->update(['status' => 'rejected']);
        return redirect()->route('admin.jemaat.pending')->with('success', 'Jemaat telah ditolak.');
    }
}
