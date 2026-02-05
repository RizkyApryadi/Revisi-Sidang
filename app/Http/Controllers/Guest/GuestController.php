<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class GuestController extends Controller
{
    public function jadwal()
    {
        return view('pages.guest.jadwal');
    }

    public function kegiatan()
    {
        return view('pages.guest.kegiatan');
    }

    public function layanan()
    {
        return view('pages.guest.layanan');
    }

    public function galeri()
    {
        $galeris = Galeri::with('fotos')->orderBy('tanggal', 'desc')->get()->map(function($item) {
            $item->foto_path = optional($item->fotos->first())->foto;
            return $item;
        });

        return view('pages.guest.galeri', compact('galeris'));
    }

    public function renungan()
    {
        return view('pages.guest.renungan');
    }

    public function dashboard()
    {
        $beritas = Berita::orderBy('tanggal', 'desc')->take(3)->get();
        $galeris = Galeri::with('fotos')->orderBy('tanggal', 'desc')->take(6)->get()->map(function($item) {
            $item->foto_path = optional($item->fotos->first())->foto;
            return $item;
        });

        // Also fetch recent individual photos so dashboard can display many images
        $fotos = GaleriFoto::with('galeri')->orderBy('id', 'desc')->take(12)->get();

        // Fetch ibadahs to show on dashboard (include warta info and pendeta display name)
        $ibadahs = collect();
        try {
            $ibadahs = DB::table('ibadahs')
                ->leftJoin('wartas', 'ibadahs.warta_id', '=', 'wartas.id')
                ->leftJoin('pendetas', 'ibadahs.pendeta_id', '=', 'pendetas.id')
                ->leftJoin('jemaats', 'pendetas.jemaat_id', '=', 'jemaats.id')
                ->leftJoin('users', 'pendetas.user_id', '=', 'users.id')
                ->select('ibadahs.*', 'wartas.nama_minggu', 'wartas.tanggal', DB::raw("COALESCE(jemaats.nama, pendetas.nama, users.name) as pendeta_name"))
                ->orderBy('ibadahs.created_at', 'desc')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {
            Log::error('Guest dashboard ibadah fetch error: ' . $e->getMessage());
            $ibadahs = collect();
        }

        // If joined query returned no rows, fall back to simple fetch and enrich rows.
        if (empty($ibadahs) || (is_object($ibadahs) && $ibadahs->isEmpty())) {
            try {
                $simple = DB::table('ibadahs')->orderBy('created_at', 'desc')->limit(6)->get();
                $ibadahs = $simple->map(function ($row) {
                    // attach warta info
                    $warta = DB::table('wartas')->where('id', $row->warta_id)->first();
                    $row->nama_minggu = $warta->nama_minggu ?? null;
                    $row->tanggal = $warta->tanggal ?? null;

                    // resolve pendeta display name
                    $pendetaName = null;
                    if (Schema::hasTable('pendetas')) {
                        $pendeta = DB::table('pendetas')->where('id', $row->pendeta_id)->first();
                        if ($pendeta) {
                            $j = DB::table('jemaats')->where('id', $pendeta->jemaat_id)->first();
                            $pendetaName = $j->nama ?? ($pendeta->nama ?? null);
                        }
                    }

                    if (empty($pendetaName)) {
                        $u = DB::table('users')->where('id', $row->pendeta_id)->first();
                        $pendetaName = $u->name ?? $u->email ?? null;
                    }

                    $row->pendeta_name = $pendetaName;
                    return $row;
                });
            } catch (\Exception $e) {
                Log::error('Guest dashboard ibadah fallback error: ' . $e->getMessage());
                $ibadahs = collect();
            }
        }

        return view('pages.guest.dashboard', compact('beritas', 'galeris', 'fotos', 'ibadahs'));
    }

    public function baptisan()
    {
        return view('pages.guest.layanan.baptisan');
    }

    public function pernikahan()
    {
        return view('pages.guest.layanan.pernikahan');
    }

    public function pindah()
    {
        return view('pages.guest.layanan.pindah');
    }

    public function sidi()
    {
        return view('pages.guest.layanan.sidi');
    }

    public function beritaShow($id)
    {
        $berita = Berita::with('user')->findOrFail($id);
        $beritas = Berita::orderBy('tanggal', 'desc')->get();
        return view('pages.guest.berita_show', compact('berita', 'beritas'));
    }

    public function galeriShow($id)
    {
        $galeri = Galeri::with(['fotos', 'user'])->findOrFail($id);
        $galeri->foto_path = optional($galeri->fotos->first())->foto;
        return view('pages.guest.galeri_show', compact('galeri'));
    }

    public function jadwalShow($id)
    {
        $ibadah = DB::table('ibadahs')->where('id', $id)->first();
        if (!$ibadah) abort(404);

        // attach warta
        $ibadah->warta = DB::table('wartas')->where('id', $ibadah->warta_id)->first();

        // attach pendeta (if available)
        if (Schema::hasTable('pendetas')) {
            $pendeta = DB::table('pendetas')->where('id', $ibadah->pendeta_id)->first();
            if ($pendeta) {
                $pendeta->jemaat = DB::table('jemaats')->where('id', $pendeta->jemaat_id)->first();
            }
            $ibadah->pendeta = $pendeta;
        } else {
            $ibadah->pendeta = DB::table('users')->where('id', $ibadah->pendeta_id)->first();
        }

        // map tata_ibadah to file for view compatibility
        $ibadah->file = $ibadah->tata_ibadah ?? null;

        // leave pdf_text empty (could be extracted later)
        $ibadah->pdf_text = null;

        return view('pages.guest.jadwal_show', compact('ibadah'));
    }

    public function jadwalFile($id)
    {
        $ibadah = DB::table('ibadahs')->where('id', $id)->first();
        if (!$ibadah || empty($ibadah->tata_ibadah)) abort(404);

        // Serve the stored file via public storage URL
        return redirect(asset('storage/' . $ibadah->tata_ibadah));
    }
}
