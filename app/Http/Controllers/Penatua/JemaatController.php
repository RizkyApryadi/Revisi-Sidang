<?php

namespace App\Http\Controllers\Penatua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Jemaat;
use App\Models\Keluarga;
use App\Models\Wijk;
use App\Models\StatusBaptis;
use App\Models\StatusSidi;
use App\Models\StatusPernikahan;
use App\Models\StatusPindah;
use App\Models\StatusKedukaan;

class JemaatController extends Controller
{
    /**
     * Show penatua create form.
     */
    public function create()
    {
        return view('pages.penatua.jemaat.create');
    }

    /**
     * Store a new jemaat (penatua scope: use penatua's wijk)
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
            // wijk is determined by authenticated penatua
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

        // Determine wijk from authenticated penatua
        $user = Auth::user();
        $penatua = $user ? $user->penatua : null;
        if ($penatua && $penatua->wijk_id) {
            $wijk = Wijk::find($penatua->wijk_id);
        } else {
            $wijk = Wijk::firstOrCreate(['nama_wijk' => 'Umum']);
        }

        // Create or find keluarga
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
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('jemaats', 'public');
        }

        DB::beginTransaction();
        try {
            $jemaat = Jemaat::create([
                'nomor_jemaat' => $request->input('nomor_jemaat'),
                'status' => 'pending',
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

            // Save other status records same as admin flow (optional fields)
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

            if ($request->filled('status_jemaat')) {
                $k = new StatusKedukaan();
                $k->jemaat_id = $jemaat->id;
                $k->status = $request->input('status_jemaat');
                if ($request->input('status_jemaat') === 'wafat') {
                    $k->tanggal = $request->input('tgl_wafat');
                    $k->nomor_surat = $request->input('no_surat_kematian');
                    $k->keterangan = $request->input('keterangan_wafat');
                }
                $k->save();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed saving jemaat (penatua): '.$e->getMessage());
            throw $e;
        }

        return redirect()->route('penatua.jemaat')->with('success', 'Data jemaat berhasil dikirim untuk persetujuan admin.');
    }
}
