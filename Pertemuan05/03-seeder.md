# 03 - Seeder

| Seeder | Isi |
|---|---|
| `AlatSeeder` | Enam alat contoh Pertemuan 04 dengan ID asli (1, 14, 22, 31, 40, 52). ALT-022 berstok 0. Memakai `updateOrCreate`, aman dijalankan berulang. |
| `PeminjamanSeeder` | Dua pengajuan contoh (disetujui dan menunggu), hanya jika tabel masih kosong. Tanggal relatif terhadap hari ini. |
| `DatabaseSeeder` | Membuat user uji (bawaan) lalu memanggil kedua seeder di atas. |

```bash
php artisan migrate:fresh --seed
php artisan tinker
>>> App\Models\Alat::count();
=> 6
>>> App\Models\Peminjaman::count();
=> 2
```

Bukti yang dilampirkan: screenshot `Alat::count()` = 6 dan isi tabel `alat`.
