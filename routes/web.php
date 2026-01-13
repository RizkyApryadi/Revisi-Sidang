<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenatuaController;
use App\Http\Controllers\PendetaController;
use App\Http\Controllers\WijkController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\JemaatController;
use App\Http\Controllers\WartaController;
use App\Http\Controllers\IbadahController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\Penatua\KatekisasiController;
use App\Http\Controllers\KatekiController;
use App\Http\Controllers\PernikahanController;
use App\Http\Controllers\Penatua\NikahController as PenatuaNikahController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Tampilkan dashboard di root tanpa memaksa autentikasi (publik)
Route::get('/', function () {
    return view('pages.guest.dashboard');
})->name('pages.guest.dashboard');

// Public guest pages used by the front-end navigation
Route::get('/jadwal', function () {
    return view('pages.guest.jadwal');
})->name('guest.jadwal');

Route::get('/kegiatan', function () {
    return view('pages.guest.kegiatan');
})->name('guest.kegiatan');

Route::get('/layanan', function () {
    return view('pages.guest.layanan');
})->name('guest.layanan');

Route::get('/galeri', function () {
    return view('pages.guest.galeri');
})->name('guest.galeri');

// Alias route name used in some templates — redirect to root dashboard
Route::redirect('/guest', '/', 302)->name('guest.dashboard');

// Dashboard user (semua user login & terverifikasi)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ================= ADMIN ROUTES =================
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('pages.admin.dashboard');
        })->name('dashboard');

        Route::controller(WijkController::class)->group(function () {
            Route::get('/wijk', 'index')->name('wijk');
            Route::post('/wijk', 'store')->name('wijk.store');
            Route::put('/wijk/{id}', 'update')->name('wijk.update');
            Route::delete('/wijk/{id}', 'destroy')->name('wijk.destroy');
        });

        Route::get('/jemaat/create', [JemaatController::class, 'create'])->name('jemaat.create');
        Route::post('/jemaat', [JemaatController::class, 'store'])->name('jemaat.store');

        // Edit & update jemaat
        Route::get('/jemaat/{id}/edit', [JemaatController::class, 'edit'])->name('jemaat.edit');
        Route::put('/jemaat/{id}', [JemaatController::class, 'update'])->name('jemaat.update');
        Route::delete('/jemaat/{id}', [JemaatController::class, 'destroy'])->name('jemaat.destroy');

        Route::get('/jemaat', [JemaatController::class, 'index'])->name('jemaat');
        // Pending jemaat submitted by penatua
        Route::get('/jemaat/pending', [JemaatController::class, 'pending'])->name('jemaat.pending');
        Route::post('/jemaat/{id}/approve', [JemaatController::class, 'approve'])->name('jemaat.approve');
        Route::post('/jemaat/{id}/reject', [JemaatController::class, 'reject'])->name('jemaat.reject');
        // Show jemaat (read-only) - constrain id to numeric so 'pending' isn't captured
        Route::get('/jemaat/{id}', [JemaatController::class, 'show'])->where('id', '[0-9]+')->name('jemaat.show');

        Route::get('/berita/create', [BeritaController::class, 'create'])->name('berita.create');
        Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');

        Route::get('/berita', [BeritaController::class, 'index'])->name('berita');

        Route::get('/ibadah', [IbadahController::class, 'index'])->name('ibadah');

        // Warta routes (WartaController)
        Route::get('/ibadah/warta/create', [WartaController::class, 'create'])->name('ibadah.warta.create');
        Route::post('/ibadah/warta', [WartaController::class, 'store'])->name('ibadah.warta.store');
        // Ibadah store
        Route::post('/ibadah', [IbadahController::class, 'store'])->name('ibadah.store');

        Route::get('/pelayan/create', [\App\Http\Controllers\PelayanController::class, 'create'])->name('pelayan.create');
        Route::post('/pelayan', [\App\Http\Controllers\PelayanController::class, 'store'])->name('pelayan.store');

        Route::get('/pelayan', [\App\Http\Controllers\PelayanController::class, 'index'])->name('pelayan');
        Route::get('/pelayan/{id}', [\App\Http\Controllers\PelayanController::class, 'show'])->name('pelayan.show');

        Route::get('/penatua', [PenatuaController::class, 'index'])->name('penatua');
        Route::put('/penatua/{id}', [PenatuaController::class, 'update'])->name('penatua.update');
        Route::delete('/penatua/{id}', [PenatuaController::class, 'destroy'])->name('penatua.destroy');

        // Penatua pending routes: count (AJAX) and pending list
        Route::get('/penatua/pending-count', [\App\Http\Controllers\PenatuaController::class, 'pendingCount'])
            ->name('penatua.pending.count');

        Route::get('/penatua/pending', [\App\Http\Controllers\PenatuaController::class, 'pendingList'])
            ->name('penatua.pending');

        Route::get('/pendeta', [PendetaController::class, 'index'])->name('pendeta');
        Route::put('/pendeta/{id}', [PendetaController::class, 'update'])->name('pendeta.update');
        Route::delete('/pendeta/{id}', [PendetaController::class, 'destroy'])->name('pendeta.destroy');

        // Pendeta pending routes: count (AJAX) and pending list
        Route::get('/pendeta/pending-count', [\App\Http\Controllers\PendetaController::class, 'pendingCount'])
            ->name('pendeta.pending.count');

        Route::get('/pendeta/pending', [\App\Http\Controllers\PendetaController::class, 'pendingList'])
            ->name('pendeta.pending');

        Route::get('/pendeta/create', [PendetaController::class, 'create'])->name('pendeta.create');
        Route::post('/pendeta', [PendetaController::class, 'store'])->name('pendeta.store');

        Route::get('/user', [UserController::class, 'index'])->name('user');

        // User management
        Route::post('/user', [UserController::class, 'store'])->name('users.store');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('users.destroy');


        Route::get('/penatua/create', [PenatuaController::class, 'create'])->name('penatua.create');
        Route::post('/penatua', [PenatuaController::class, 'store'])->name('penatua.store');

        Route::get('/galeri/create', [GaleriController::class, 'create'])->name('galeri.create');
        Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');

        Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');

        // Pelayanan pages (MainMenu)
        Route::get('/pelayanan/baptisan', function () {
            return view('pages.admin.MainMenu.baptisan.index');
        })->name('pelayanan.baptisan');

        Route::get('/pelayanan/katekisasi', [KatekiController::class, 'index'])
            ->name('pelayanan.katekisasi');

        // Approve / Reject pendaftaran_sidi (admin moderation)
        Route::post('/pelayanan/katekisasi/pendaftaran/{id}/approve', [\App\Http\Controllers\Admin\PendaftaranSidiController::class, 'approve'])
            ->name('pelayanan.katekisasi.pendaftaran.approve');
        Route::post('/pelayanan/katekisasi/pendaftaran/{id}/reject', [\App\Http\Controllers\Admin\PendaftaranSidiController::class, 'reject'])
            ->name('pelayanan.katekisasi.pendaftaran.reject');

        // Create page for Admin katekisasi
        Route::get('/pelayanan/katekisasi/create', [KatekiController::class, 'create'])
            ->name('pelayanan.katekisasi.create');
        Route::post('/pelayanan/katekisasi', [KatekiController::class, 'store'])
            ->name('pelayanan.katekisasi.store');

        Route::get('/pelayanan/kedukaan', function () {
            return view('pages.admin.MainMenu.kedukaan.index');
        })->name('pelayanan.kedukaan');

        Route::get('/pelayanan/pernikahan', function () {
            return view('pages.admin.MainMenu.pernikahan.index');
        })->name('pelayanan.pernikahan');

        Route::get('/pelayanan/pindah', function () {
            return view('pages.admin.MainMenu.pindah.index');
        })->name('pelayanan.pindah');
    });



// ================= PROFILE ROUTES ================= 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (Laravel Breeze / Jetstream)
require __DIR__ . '/auth.php';

// ================= PENDETA ROUTES =================
Route::middleware(['auth', 'verified', 'role:pendeta'])
    ->prefix('pendeta')
    ->name('pendeta.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.pendeta.dashboard');
        })->name('dashboard');

        // Tambah route khusus pendeta di sini (mis. profile, jadwal)
    });

// ================= PENATUA ROUTES =================
Route::middleware(['auth', 'verified', 'role:penatua'])
    ->prefix('penatua')
    ->name('penatua.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('pages.penatua.dashboard');
        })->name('dashboard');

        // Tambah route khusus penatua di sini
        // Jemaat routes for penatua (use controller so we can store with penatua's wijk)
        Route::get('/jemaat/create', [\App\Http\Controllers\Penatua\JemaatController::class, 'create'])->name('jemaat.create');
        Route::post('/jemaat', [\App\Http\Controllers\Penatua\JemaatController::class, 'store'])->name('jemaat.store');

        Route::get('/jemaat', function () {
            return view('pages.penatua.jemaat.index');
        })->name('jemaat');

        // Pelayanan pages available to Penatua (read-only indexes)
        Route::get('/pelayanan/baptisan', function () {
            return view('pages.penatua.baptisan.index');
        })->name('pelayanan.baptisan');

        // Create page for Penatua baptisan
        Route::get('/pelayanan/baptisan/create', function () {
            return view('pages.penatua.baptisan.create');
        })->name('pelayanan.baptisan.create');

        Route::get('/pelayanan/katekisasi', [KatekisasiController::class, 'index'])
            ->name('pelayanan.katekisasi');

        // Create page for Penatua katekisasi
        Route::get('/pelayanan/katekisasi/create', [KatekisasiController::class, 'create'])
            ->name('pelayanan.katekisasi.create');

        // Store pendaftaran sidi submitted by penatua
        Route::post('/pelayanan/katekisasi', [KatekisasiController::class, 'store'])
            ->name('pelayanan.katekisasi.store');

        // Show, edit, delete for a specific katekisasi (penatua)
        Route::get('/pelayanan/katekisasi/{id}', [KatekisasiController::class, 'show'])
            ->name('pelayanan.katekisasi.show');
        Route::get('/pelayanan/katekisasi/{id}/edit', [KatekisasiController::class, 'edit'])
            ->name('pelayanan.katekisasi.edit');
        Route::delete('/pelayanan/katekisasi/{id}', [KatekisasiController::class, 'destroy'])
            ->name('pelayanan.katekisasi.destroy');

        // Fallback: accept POSTs accidentally sent to the create URL (some forms/clients still post to /create)
        Route::post('/pelayanan/katekisasi/create', [KatekisasiController::class, 'store']);

        Route::get('/pelayanan/kedukaan', function () {
            return view('pages.penatua.kedukaan.index');
        })->name('pelayanan.kedukaan');

        // Create page for Penatua kedukaan
        Route::get('/pelayanan/kedukaan/create', function () {
            return view('pages.penatua.kedukaan.create');
        })->name('pelayanan.kedukaan.create');

        // Create page for Penatua pernikahan
        Route::get('/pelayanan/pernikahan/create', function () {
            return view('pages.penatua.pernikahan.create');
        })->name('pelayanan.pernikahan.create');

        // Accept penatua submissions for pernikahan (store) -> use Penatua\NikahController
        Route::post('/pelayanan/pernikahan', [PenatuaNikahController::class, 'store'])
            ->name('pelayanan.pernikahan.store');

        // Fallback: accept POSTs sent to the create URL (some forms post to /create)
        Route::post('/pelayanan/pernikahan/create', [PenatuaNikahController::class, 'store']);

        Route::get('/pelayanan/pernikahan', [PenatuaNikahController::class, 'index'])
            ->name('pelayanan.pernikahan');

        // Create page for Penatua pindah
        Route::get('/pelayanan/pindah/create', function () {
            return view('pages.penatua.pindah.create');
        })->name('pelayanan.pindah.create');

        Route::get('/pelayanan/pindah', function () {
            return view('pages.penatua.pindah.index');
        })->name('pelayanan.pindah');
    });
