<?php

namespace App\Http\Controllers;

use App\Models\AktivitasPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class NotifikasiController extends Controller
{
    /**
     * Daftar notifikasi milik pengguna yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();
        $tabelTersedia = Schema::hasTable('notifications');

        if (! $tabelTersedia) {
            $daftarNotifikasi = new LengthAwarePaginator([], 0, 15);
            $jumlahBelumDibaca = 0;
        } else {
            $daftarNotifikasi = $user->notifications()->paginate(15);
            $jumlahBelumDibaca = $user->unreadNotifications()->count();
        }

        return view('notifikasi.index', compact('daftarNotifikasi', 'jumlahBelumDibaca', 'tabelTersedia'));
    }

    /**
     * Menandai satu notifikasi milik user sebagai sudah dibaca.
     */
    public function baca(string $id)
    {
        if (! Schema::hasTable('notifications')) {
            return back();
        }

        $notifikasi = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        if ($notifikasi->read_at === null) {
            $notifikasi->markAsRead();
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Menandai semua notifikasi milik user sebagai sudah dibaca.
     */
    public function bacaSemua()
    {
        if (! Schema::hasTable('notifications')) {
            return back();
        }

        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    /**
     * Riwayat aktivitas transaksi peminjaman (Admin & Laboran).
     */
    public function aktivitas()
    {
        $tabelTersedia = Schema::hasTable('aktivitas_peminjaman');

        $daftarAktivitas = $tabelTersedia
            ? AktivitasPeminjaman::with(['user', 'peminjaman.alat'])
                ->orderByDesc('created_at')
                ->paginate(20)
            : new LengthAwarePaginator([], 0, 20);

        return view('aktivitas.index', compact('daftarAktivitas', 'tabelTersedia'));
    }
}
