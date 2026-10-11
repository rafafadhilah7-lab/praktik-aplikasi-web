# 04 - CRUD Alat

Controller: `app/Http/Controllers/AlatController.php`. View: `resources/views/pages/` (`beranda`, `hasil`, `detail-alat`, `alat/create`, `alat/edit`, `alat/_form`).

| Operasi | Method + URL | Nama route | Catatan |
|---|---|---|---|
| Read (beranda) | GET `/alat` | `alat.beranda` | 3 alat berstok > 0 dari database |
| Read (daftar/cari) | GET `/alat/hasil` | `alat.hasil` | Filter `q`, `kategori`, `tanggal` (sisa unit pada tanggal itu) |
| Read (detail) | GET `/alat/{alat}` | `alat.detail` | Route model binding; id tidak ada -> 404. Menampilkan jadwal peminjaman mendatang |
| Create | GET `/alat/tambah`, POST `/alat` | `alat.create`, `alat.store` | `/alat/tambah` didefinisikan sebelum `/alat/{alat}` |
| Update | GET `/alat/{alat}/ubah`, PUT `/alat/{alat}` | `alat.edit`, `alat.update` | Stok tidak boleh di bawah unit yang sudah dipesan |
| Delete | DELETE `/alat/{alat}` | `alat.destroy` | Ditolak bila ada peminjaman aktif mendatang |

Validasi: kode wajib, unik, maks 20; nama maks 100; kategori maks 50; stok 0-1000; lokasi maks 150; deskripsi opsional. Pesan galat tampil di bawah field dan ringkasan sukses/galat tampil di bagian atas halaman (layout).

Uji CRUD alat: lihat nomor 1-4 pada `06-test-checklist.md`.
