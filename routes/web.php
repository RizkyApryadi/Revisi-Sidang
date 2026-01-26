<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Guest\GuestController;
use Illuminate\Support\Facades\Route;


/*|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|       Here is where you can register web routes for your application. These
|       are loaded by the RouteServiceProvider within a group which
|       contains the "web" middleware group. Now create something great!
|*/

Route::get('/', function () {
    return view('pages.guest.dashboard');
})->name('pages.guest.dashboard');

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

/* |--------------------------------------------------------------------------
    Admin Routes
    |--------------------------------------------------------------------------
*/

/* |--------------------------------------------------------------------------
    Penatua Routes
    |--------------------------------------------------------------------------
*/

/* |--------------------------------------------------------------------------
    Pendeta Routes
    |--------------------------------------------------------------------------
*/


