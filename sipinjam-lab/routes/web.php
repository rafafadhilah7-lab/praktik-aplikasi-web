<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\AlatManageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PeminjamanManageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIPINJAM-LAB
|--------------------------------------------------------------------------
*/

// Redirect halaman utama ke katalog alat
Route::get('/', function () {
    return redirect()->route('alat.beranda');
});

// Halaman awal praktikum & galeri komponen UI (tetap dipertahankan)
Route::get('/awal', function () {
    return view('awal');
});
Route::view('/komponen', 'pages.komponen')->name('komponen');

// --------------------------------------------------------------------------
// 1. Katalog & Pencarian Alat (Publik)
// --------------------------------------------------------------------------
Route::get('/alat', [AlatController::class, 'beranda'])->name('alat.beranda');
Route::get('/alat/hasil', [AlatController::class, 'hasil'])->name('alat.hasil');
Route::get('/alat/{id}', [AlatController::class, 'detail'])->whereNumber('id')->name('alat.detail');

// --------------------------------------------------------------------------
// 2. Autentikasi (Tamu / Guest)
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// --------------------------------------------------------------------------
// 3. Area Pengguna Terautentikasi (Umum untuk Semua Role)
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna Sendiri
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');

    // Detail Peminjaman (diotorisasi lewat PeminjamanPolicy di controller)
    Route::get('/peminjaman/{id}', [PeminjamanManageController::class, 'detail'])
        ->whereNumber('id')
        ->name('peminjaman.detail');

    // Notifikasi Pengguna
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'baca'])->name('notifikasi.baca');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.bacaSemua');
});

// --------------------------------------------------------------------------
// 4. Area Khusus Peminjam (Mahasiswa)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'role:peminjam'])->group(function () {
    Route::get('/peminjam/dashboard', [DashboardController::class, 'peminjam'])->name('peminjam.dashboard');
    Route::get('/peminjam/riwayat', [PeminjamanManageController::class, 'riwayatSaya'])->name('peminjam.riwayat');
    Route::get('/peminjam/riwayat/{id}/edit', [PeminjamanManageController::class, 'edit'])->whereNumber('id')->name('peminjam.riwayat.edit');
    Route::put('/peminjam/riwayat/{id}', [PeminjamanManageController::class, 'update'])->whereNumber('id')->name('peminjam.riwayat.update');
    Route::patch('/peminjam/riwayat/{id}/batalkan', [PeminjamanManageController::class, 'batalkan'])->whereNumber('id')->name('peminjam.riwayat.batalkan');

    // Pengajuan peminjaman (wajib login sebagai peminjam)
    Route::get('/alat/{id}/pinjam', [AlatController::class, 'formPinjam'])->whereNumber('id')->name('alat.pinjam');
    Route::post('/alat/{id}/pinjam', [AlatController::class, 'ajukan'])->whereNumber('id')->name('alat.ajukan');

    // Halaman konfirmasi setelah pengajuan (GET, aman untuk di-refresh)
    Route::get('/peminjaman/{id}/konfirmasi', [AlatController::class, 'konfirmasi'])
        ->whereNumber('id')
        ->name('peminjaman.konfirmasi');
});

// --------------------------------------------------------------------------
// 5. Area Khusus Laboran & Admin (Pengelolaan Transaksi Peminjaman)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'role:laboran,admin'])->group(function () {
    Route::get('/laboran/dashboard', [DashboardController::class, 'laboran'])->name('laboran.dashboard');

    // Manajemen Transaksi & Riwayat Seluruh Peminjaman
    Route::get('/peminjaman', [PeminjamanManageController::class, 'index'])->name('peminjaman.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/aktivitas', [NotifikasiController::class, 'aktivitas'])->name('aktivitas.index');
    Route::post('/peminjaman/{id}/setujui', [PeminjamanManageController::class, 'setujui'])->whereNumber('id')->name('peminjaman.setujui');
    Route::post('/peminjaman/{id}/tolak', [PeminjamanManageController::class, 'tolak'])->whereNumber('id')->name('peminjaman.tolak');
    Route::post('/peminjaman/{id}/serahkan', [PeminjamanManageController::class, 'serahkan'])->whereNumber('id')->name('peminjaman.serahkan');
    Route::post('/peminjaman/{id}/kembalikan', [PeminjamanManageController::class, 'kembalikan'])->whereNumber('id')->name('peminjaman.kembalikan');
});

// --------------------------------------------------------------------------
// 6. Area Khusus Admin (Pengelolaan Alat, Pengguna & Konfigurasi Sistem)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Manajemen Alat
    Route::resource('alat', AlatManageController::class)->except(['show']);

    // Manajemen Pengguna & Role
    Route::resource('users', UserManageController::class)->except(['show']);
});