<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Guest\GuestController;
use App\Http\Controllers\WijkController;
use App\Http\Controllers\JemaatController;
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\PenatuaController;
use App\Http\Controllers\IbadahController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PelayanController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\PendetaController;
use App\Http\Controllers\WartaController;
use App\Http\Controllers\Penatua\PenatuaController as PenatuaAreaController;
use App\Http\Controllers\Penatua\PenatuaJemaatController;
use App\Http\Controllers\Pendeta\PendetaController as PendetaAreaController;
use App\Http\Controllers\Pendeta\PendetaRenunganController;
use Illuminate\Support\Facades\Route;


/*--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|*/
// Root guest dashboard
Route::get('/', [GuestController::class, 'dashboard'])->name('pages.guest.dashboard');

// Guest pages used by blade navigation
Route::get('/jadwal', [GuestController::class, 'jadwal'])->name('guest.jadwal');
Route::get('/jadwal/{id}', [GuestController::class, 'jadwalShow'])->name('guest.jadwal.show');
Route::get('/jadwal/{id}/file', [GuestController::class, 'jadwalFile'])->name('guest.jadwal.file');
Route::get('/kegiatan', [GuestController::class, 'kegiatan'])->name('guest.kegiatan');
Route::get('/layanan', [GuestController::class, 'layanan'])->name('guest.layanan');
Route::get('/layanan/baptisan', [GuestController::class, 'baptisan'])->name('guest.layanan.baptisan');
Route::get('/layanan/pernikahan', [GuestController::class, 'pernikahan'])->name('guest.layanan.pernikahan');
Route::get('/layanan/pindah', [GuestController::class, 'pindah'])->name('guest.layanan.pindah');
Route::get('/layanan/sidi', [GuestController::class, 'sidi'])->name('guest.layanan.sidi');
Route::get('/galeri', [GuestController::class, 'galeri'])->name('guest.galeri');
Route::get('/galeri/{id}', [GuestController::class, 'galeriShow'])->name('guest.galeri.show');
Route::get('/renungan', [GuestController::class, 'renungan'])->name('guest.renungan');
Route::get('/renungan/{id}', [GuestController::class, 'renunganShow'])->name('guest.renungan.show');
Route::get('/berita/{id}', [GuestController::class, 'beritaShow'])->name('guest.berita.show');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class);
});
require __DIR__ . '/auth.php';

/* --------------------------------------------------------------------------
   Admin Routes
   --------------------------------------------------------------------------*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.admin.dashboard');
    })->name('dashboard');

    // Wijk 
    Route::get('/wijk', [WijkController::class, 'index'])->name('wijk');
    Route::post('/wijk', [WijkController::class, 'store'])->name('wijk.store');
    Route::put('/wijk/{wijk}', [WijkController::class, 'update'])->name('wijk.update');
    Route::delete('/wijk/{wijk}', [WijkController::class, 'destroy'])->name('wijk.destroy');

    // jemaat
    Route::get('/jemaat', [JemaatController::class, 'index'])->name('jemaat');
    Route::get('/jemaat/create', [JemaatController::class, 'create'])->name('jemaat.create');
    Route::get('/jemaat/search', [JemaatController::class, 'ajaxSearch'])->name('jemaat.search');
    Route::get('/jemaat/{id}/check-role', [JemaatController::class, 'checkRole'])->name('jemaat.checkRole');
    Route::post('/jemaat', [JemaatController::class, 'store'])->name('jemaat.store');
    Route::get('/jemaat/{id}/edit', [JemaatController::class, 'edit'])->name('jemaat.edit');
    Route::put('/jemaat/{id}', [JemaatController::class, 'update'])->name('jemaat.update');
    Route::delete('/jemaat/{id}', [JemaatController::class, 'destroy'])->name('jemaat.destroy');
    Route::post('/jemaat/{id}/assign-role', [JemaatController::class, 'assignRole'])->name('jemaat.assignRole');

    // keluarga
    Route::get('/keluarga/create', [KeluargaController::class, 'create'])->name('keluarga.create');
    Route::post('/keluarga', [KeluargaController::class, 'store'])->name('keluarga.store');
    Route::delete('/keluarga/{id}', [KeluargaController::class, 'destroy'])->name('keluarga.destroy');

    // Penatua
    Route::get('/penatua', [PenatuaController::class, 'index'])->name('penatua');
    Route::get('/penatua/create', [PenatuaController::class, 'create'])->name('penatua.create');
    Route::post('/penatua', [PenatuaController::class, 'store'])->name('penatua.store');
    Route::get('/penatua/{id}/edit', [PenatuaController::class, 'edit'])->name('penatua.edit');
    Route::put('/penatua/{id}', [PenatuaController::class, 'update'])->name('penatua.update');
    Route::delete('/penatua/{id}', [PenatuaController::class, 'destroy'])->name('penatua.destroy');

    // Ibadah 
    Route::get('/ibadah', [WartaController::class, 'index'])->name('ibadah');
    Route::post('/ibadah', [IbadahController::class, 'store'])->name('ibadah.store');
    // Warta (create/store)
    Route::get('/ibadah/warta/create', [WartaController::class, 'create'])->name('ibadah.warta.create');
    Route::post('/ibadah/warta', [WartaController::class, 'store'])->name('ibadah.warta.store');
    Route::get('/ibadah/warta/{id}/edit', [WartaController::class, 'edit'])->name('ibadah.warta.edit');
    Route::post('/ibadah/warta', [WartaController::class, 'store'])->name('ibadah.warta.store');
    Route::put('/ibadah/warta/{id}', [WartaController::class, 'update'])->name('ibadah.warta.update');
    Route::delete('/ibadah/warta/{id}', [WartaController::class, 'destroy'])->name('ibadah.warta.destroy');

    // Berita
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
    Route::get('/berita/create', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::post('/berita/upload-image', [BeritaController::class, 'uploadImage'])->name('berita.uploadImage');
    Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('berita.show');
    Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

    // Pelayan
    Route::get('/pelayan', [PelayanController::class, 'index'])->name('pelayan');
    Route::get('/pelayan/create', [PelayanController::class, 'create'])->name('pelayan.create');
    Route::post('/pelayan', [PelayanController::class, 'store'])->name('pelayan.store');
    Route::get('/pelayan/{id}', [PelayanController::class, 'show'])->name('pelayan.show');

    // Galeri
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
    Route::get('/galeri/create', [GaleriController::class, 'create'])->name('galeri.create');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');

    // Pendeta
    Route::get('/pendeta', [PendetaController::class, 'index'])->name('pendeta');
    Route::get('/pendeta/create', [PendetaController::class, 'create'])->name('pendeta.create');
    Route::post('/pendeta', [PendetaController::class, 'store'])->name('pendeta.store');
    Route::get('/pendeta/{id}/edit', [PendetaController::class, 'edit'])->name('pendeta.edit');
    Route::put('/pendeta/{id}', [PendetaController::class, 'update'])->name('pendeta.update');
    Route::delete('/pendeta/{id}', [PendetaController::class, 'destroy'])->name('pendeta.destroy');

    // User 
    Route::get('/user', [UserController::class, 'index'])->name('user');
});


/* --------------------------------------------------------------------------
   Penatua Routes
   --------------------------------------------------------------------------*/
Route::middleware('auth')->prefix('penatua')->name('penatua.')->group(function () {
    Route::get('/dashboard', [PenatuaAreaController::class, 'index'])->name('dashboard');
    // Penatua - jemaat area
    Route::get('/jemaat', [PenatuaJemaatController::class, 'index'])->name('jemaat');
    Route::get('/jemaat/create', [PenatuaJemaatController::class, 'create'])->name('jemaat.create');
    Route::post('/jemaat', [PenatuaJemaatController::class, 'store'])->name('jemaat.store');
    Route::get('/jemaat/{id}', [PenatuaJemaatController::class, 'show'])->name('jemaat.show');
    Route::get('/jemaat/{id}/edit', [PenatuaJemaatController::class, 'edit'])->name('jemaat.edit');
    Route::put('/jemaat/{id}', [PenatuaJemaatController::class, 'update'])->name('jemaat.update');
});

/* --------------------------------------------------------------------------
   Pendeta Routes
   --------------------------------------------------------------------------*/
Route::middleware('auth')->prefix('pendeta')->name('pendeta.')->group(function () {
    Route::get('/dashboard', [PendetaAreaController::class, 'index'])->name('dashboard');
    // Renungan (Pendeta) - standard resource-like names
    Route::get('/renungan', [PendetaRenunganController::class, 'index'])->name('renungan.index');
    Route::get('/renungan/create', [PendetaRenunganController::class, 'create'])->name('renungan.create');
    Route::post('/renungan', [PendetaRenunganController::class, 'store'])->name('renungan.store');
    Route::get('/renungan/{id}', [PendetaRenunganController::class, 'show'])->name('renungan.show');
    Route::get('/renungan/{id}/edit', [PendetaRenunganController::class, 'edit'])->name('renungan.edit');
    Route::put('/renungan/{id}', [PendetaRenunganController::class, 'update'])->name('renungan.update');
    Route::delete('/renungan/{id}', [PendetaRenunganController::class, 'destroy'])->name('renungan.destroy');
    Route::post('/renungan/{id}/toggle-status', [PendetaRenunganController::class, 'toggleStatus'])->name('renungan.toggleStatus');
    // add other pendeta routes here
});
