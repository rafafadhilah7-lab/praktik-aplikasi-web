# 02 - Migration dan Model

Berkas (di `sipinjam-lab/`):

| Berkas | Isi |
|---|---|
| `database/migrations/2026_10_11_100001_create_alat_table.php` | Tabel `alat` |
| `database/migrations/2026_10_11_100002_create_peminjaman_table.php` | Tabel `peminjaman` + FK `alat_id` + indeks (`alat_id`, `tanggal_pinjam`, `tanggal_selesai`) |
| `app/Models/Alat.php` | `$table = 'alat'`, `$fillable`, `hasMany(Peminjaman)`, `sisaPada($tanggal)` |
| `app/Models/Peminjaman.php` | `$table = 'peminjaman'`, `$fillable`, `belongsTo(Alat)`, konstanta `STATUS`/`AKTIF`, `puncakTerpakai()` |

Catatan:

- Nama tabel ditulis eksplisit (`alat`, `peminjaman`) karena Laravel akan menebak `alats`/`peminjamen`.
- Migration `alat` berjalan sebelum `peminjaman` (urutan nama berkas) agar FK valid.
- `Peminjaman` mengisi `tanggal_selesai` otomatis pada event `saving`, sehingga tidak bisa tidak konsisten dengan `tanggal_pinjam` dan `durasi`.
- `Peminjaman::puncakTerpakai($alatId, $mulai, $selesai, $abaikanId)` menghitung unit terpakai terbanyak pada satu hari mana pun dalam rentang. Dipakai untuk cek bentrok dan sisa unit pada pencarian.

Perintah:

```bash
php artisan migrate:status
php artisan migrate:fresh --seed
php artisan tinker
>>> App\Models\Alat::find(1)->peminjaman;
>>> App\Models\Peminjaman::with('alat')->first()->alat->nama;
```

Bukti yang dilampirkan: screenshot `migrate:status` (semua `Ran`) dan struktur tabel `alat`, `peminjaman` di phpMyAdmin/HeidiSQL.
