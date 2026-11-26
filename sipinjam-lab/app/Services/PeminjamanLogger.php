<?php

namespace App\Services;

use App\Models\AktivitasPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use App\Notifications\PeminjamanStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PeminjamanLogger
{
    /**
     * Mencatat riwayat aktivitas transaksi peminjaman secara aman.
     */
    public static function catat(?User $pelaku, ?Peminjaman $peminjaman, string $aktivitas, ?string $keterangan = null): void
    {
        if (! Schema::hasTable('aktivitas_peminjaman')) {
            return;
        }

        AktivitasPeminjaman::create([
            'user_id' => $pelaku?->id,
            'peminjaman_id' => $peminjaman?->id,
            'aktivitas' => $aktivitas,
            'keterangan' => $keterangan,
            'created_at' => now(),
        ]);
    }

    /**
     * Mengirim notifikasi ke satu user dengan pencegahan duplikasi pada aksi yang sama.
     */
    public static function kirimKeUser(
        ?User $penerima,
        ?int $peminjamanId,
        string $judul,
        string $pesan,
        string $status,
        string $url
    ): void {
        if ($penerima === null || ! Schema::hasTable('notifications')) {
            return;
        }

        $duplikat = DB::table('notifications')
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $penerima->id)
            ->whereNull('read_at')
            ->where('created_at', '>=', now()->subSeconds(5))
            ->get()
            ->contains(function ($row) use ($peminjamanId, $judul) {
                $data = json_decode((string) $row->data, true);

                return is_array($data)
                    && ($data['peminjaman_id'] ?? null) === $peminjamanId
                    && ($data['judul'] ?? null) === $judul;
            });

        if ($duplikat) {
            return;
        }

        $penerima->notify(new PeminjamanStatusNotification(
            $peminjamanId,
            $judul,
            $pesan,
            $status,
            $url
        ));
    }

    /**
     * Mengirim notifikasi ke semua pengguna dengan role tertentu.
     */
    public static function kirimKeRole(
        string|array $roles,
        ?int $peminjamanId,
        string $judul,
        string $pesan,
        string $status,
        string $url
    ): void {
        if (! Schema::hasTable('notifications')) {
            return;
        }

        $daftarRole = is_array($roles) ? $roles : [$roles];
        $penerima = User::query()->whereIn('role', $daftarRole)->get();

        foreach ($penerima as $user) {
            self::kirimKeUser($user, $peminjamanId, $judul, $pesan, $status, $url);
        }
    }
}
