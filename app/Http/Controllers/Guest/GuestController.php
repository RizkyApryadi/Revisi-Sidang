<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;

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
        return view('pages.guest.galeri');
    }

    public function renungan()
    {
        return view('pages.guest.renungan');
    }

    public function dashboard()
    {
        $beritas = Berita::orderBy('tanggal', 'desc')->take(3)->get();
        return view('pages.guest.dashboard', compact('beritas'));
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
}
