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
use Illuminate\Support\Facades\Route;


/*--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|*/


// Guest pages used by blade navigation
Route::get('/jadwal', [GuestController::class, 'jadwal'])->name('guest.jadwal');
Route::get('/kegiatan', [GuestController::class, 'kegiatan'])->name('guest.kegiatan');
Route::get('/layanan', [GuestController::class, 'layanan'])->name('guest.layanan');
Route::get('/galeri', [GuestController::class, 'galeri'])->name('guest.galeri');
Route::get('/renungan', [GuestController::class, 'renungan'])->name('guest.renungan');
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
    Route::get('/ibadah', [IbadahController::class, 'index'])->name('ibadah');
    
    // Berita
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
    
    // Pelayan
    Route::get('/pelayan', [PelayanController::class, 'index'])->name('pelayan');
    
    // Galeri
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');

    // Pendeta
    Route::get('/pendeta', [PendetaController::class, 'index'])->name('pendeta');
    Route::get('/pendeta/create', [PendetaController::class, 'create'])->name('pendeta.create');
    Route::post('/pendeta', [PendetaController::class, 'store'])->name('pendeta.store');
});


/* --------------------------------------------------------------------------
   Penatua Routes
   --------------------------------------------------------------------------*/
Route::middleware('auth')->prefix('penatua')->name('penatua.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.penatua.dashboard');
    })->name('dashboard');
    // add other penatua routes here
});

/* --------------------------------------------------------------------------
   Pendeta Routes
   --------------------------------------------------------------------------*/
Route::middleware('auth')->prefix('pendeta')->name('pendeta.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.pendeta.dashboard');
    })->name('dashboard');
    // add other pendeta routes here
});
