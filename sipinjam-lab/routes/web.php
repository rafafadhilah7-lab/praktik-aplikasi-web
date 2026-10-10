
<?php

use Illuminate\Support\Facades\Route;

// Halaman utama Laravel
Route::get('/', function () {
    return view('welcome');
});

// Halaman awal praktikum
Route::get('/awal', function () {
    return view('awal');
});

// Galeri komponen UI dari praktikum sebelumnya
Route::view('/komponen', 'pages.komponen');

// Beranda pencarian alat laboratorium
Route::get(
    '/alat',
    [\App\Http\Controllers\AlatController::class, 'beranda']
)->name('alat.beranda');

// Halaman hasil pencarian alat
Route::get(
    '/alat/hasil',
    [\App\Http\Controllers\AlatController::class, 'hasil']
)->name('alat.hasil');
