<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
