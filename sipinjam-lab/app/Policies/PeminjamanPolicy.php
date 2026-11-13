<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\User;

class PeminjamanPolicy
{
    /**
     * Laboran dan admin dianggap petugas.
     */
    private function isPetugas(User $user): bool
    {
        return $user->hasRole([User::ROLE_ADMIN, User::ROLE_LABORAN]);
    }

    /**
     * Pemilik ditentukan HANYA dari user_id, bukan NIM.
     */
    private function isPemilik(User $user, Peminjaman $peminjaman): bool
    {
        return $peminjaman->user_id !== null
            && (int) $peminjaman->user_id === (int) $user->id;
    }

    /**
     * Melihat detail: petugas atau pemilik pengajuan.
     */
    public function view(User $user, Peminjaman $peminjaman): bool
    {
        return $this->isPetugas($user) || $this->isPemilik($user, $peminjaman);
    }

    /**
     * Mengubah pengajuan: admin, atau pemilik selama masih "menunggu".
     */
    public function update(User $user, Peminjaman $peminjaman): bool
    {
        return $user->isAdmin()
            || ($this->isPemilik($user, $peminjaman) && $peminjaman->isMenunggu());
    }

    /**
     * Menghapus pengajuan: aturan sama dengan update.
     */
    public function delete(User $user, Peminjaman $peminjaman): bool
    {
        return $this->update($user, $peminjaman);
    }

    /**
     * Menyetujui, menolak, menyerahkan, dan menerima pengembalian.
     */
    public function review(User $user, Peminjaman $peminjaman): bool
    {
        return $this->isPetugas($user);
    }
}