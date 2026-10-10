
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatController;

// Halaman utama Laravel
Route::get('/', function () {
    return view('welcome');
});

// Halaman awal praktikum
Route::get('/awal', function () {
    return view('awal');
});

// Galeri komponen UI
Route::view('/komponen', 'pages.komponen');

// Beranda pencarian alat laboratorium
Route::get('/alat', [AlatController::class, 'beranda'])
    ->name('alat.beranda');

// Halaman hasil pencarian alat
Route::get('/alat/hasil', [AlatController::class, 'hasil'])
    ->name('alat.hasil');

// Halaman detail alat
Route::get('/alat/{id}', [AlatController::class, 'detail'])
    ->whereNumber('id')
    ->name('alat.detail');

// Form pengajuan peminjaman
Route::get('/alat/{id}/pinjam', [AlatController::class, 'formPinjam'])
    ->whereNumber('id')
    ->name('alat.pinjam');

// Proses pengajuan peminjaman simulasi
Route::post('/alat/{id}/pinjam', [AlatController::class, 'ajukan'])
    ->whereNumber('id')
    ->name('alat.ajukan');
