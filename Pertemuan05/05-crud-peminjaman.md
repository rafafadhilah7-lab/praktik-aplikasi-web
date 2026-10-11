# 05 - CRUD Pengajuan Peminjaman

Controller: `app/Http/Controllers/PeminjamanController.php`. View: `form-pinjam`, `konfirmasi-pinjam`, `peminjaman/index`, `peminjaman/edit`.

| Operasi | Method + URL | Nama route | Catatan |
|---|---|---|---|
| Create (form) | GET `/alat/{alat}/pinjam` | `alat.pinjam` | Alat stok 0 dialihkan ke detail dengan pesan galat |
| Create (simpan) | POST `/alat/{alat}/pinjam` | `alat.ajukan` | Status awal `menunggu`; redirect ke konfirmasi (PRG) |
| Read (konfirmasi) | GET `/peminjaman/{peminjaman}` | `peminjaman.show` | Ringkasan satu pengajuan |
| Read (daftar) | GET `/peminjaman` | `peminjaman.index` | Nama alat dari relasi `Peminjaman::with('alat')` |
| Update | GET `/peminjaman/{peminjaman}/ubah`, PUT `/peminjaman/{peminjaman}` | `peminjaman.edit`, `peminjaman.update` | Ubah data dan status |
| Delete | DELETE `/peminjaman/{peminjaman}` | `peminjaman.destroy` | |

## Validasi

| Field | Aturan |
|---|---|
| `nama_peminjam` | wajib, teks, maks 100 |
| `nim` | wajib, 8-20 digit angka |
| `tanggal_pinjam` | wajib, tanggal valid, tidak boleh lampau (pengajuan baru) |
| `durasi` | wajib, bilangan bulat 1-30 |
| `jumlah` | wajib, bilangan bulat >= 1 |
| `keperluan` | wajib, teks, maks 500 |
| `status` (hanya ubah) | salah satu dari `menunggu`, `disetujui`, `dipinjam`, `dikembalikan`, `ditolak` |

## Aturan bisnis (setelah validasi dasar)

1. `jumlah` <= `stok` alat, jika tidak galat pada field `jumlah`.
2. Bentrok jadwal: `Peminjaman::puncakTerpakai(...)` + `jumlah` <= `stok`, jika tidak galat pada field `tanggal_pinjam` beserta sisa unit.

## Skenario bentrok (padanan Kasus A-D modul)

Contoh: osiloskop stok 2, jadwal lama 2 unit pada hari X sampai X+2 (disetujui).

| Kasus | Alat | Jadwal baru | Hasil |
|---|---|---|---|
| A | Osiloskop | X+3 sampai X+4, 1 unit | Diterima |
| B | Osiloskop | X+1 sampai X+3, 1 unit | Ditolak (bentrok) |
| C | Osiloskop | X-2 sampai X-1, 2 unit | Diterima |
| D | Multimeter | X+1 sampai X+3, 1 unit | Diterima (alat lain) |

Tes otomatisnya: `PeminjamanDatabaseTest::test_skenario_bentrok_a_sampai_d`.
