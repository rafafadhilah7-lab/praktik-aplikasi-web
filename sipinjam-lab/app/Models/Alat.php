<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alat extends Model
{
    use SoftDeletes;

    /**
     * Nama tabel database.
     */
    protected $table = 'alat';

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'stok',
        'lokasi',
        'deskripsi',
    ];

    /**
     * Konversi tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'stok' => 'integer',
        ];
    }

    /**
     * Satu alat dapat memiliki banyak riwayat peminjaman.
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'alat_id');
    }

    /**
     * Pengecekan apakah alat sedang memiliki transaksi peminjaman aktif.
     */
    public function hasActiveLoans(): bool
    {
        return $this->peminjaman()
            ->whereIn('status', Peminjaman::AKTIF)
            ->exists();
    }

    /**
     * Menghitung sisa unit alat yang tersedia pada tanggal tertentu.
     */
    public function sisaPada(string $tanggal): int
    {
        $terpakai = Peminjaman::puncakTerpakai($this->id, $tanggal, $tanggal);

        return max(0, (int) $this->stok - $terpakai);
    }
}
