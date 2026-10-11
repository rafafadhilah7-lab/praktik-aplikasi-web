# 01 - Pemetaan Antarmuka ke Model Data

Sumber: halaman Pertemuan 04 (Beranda/Cari Alat, Hasil Pencarian, Detail Alat, Form Peminjaman) dan bagian "Pemetaan untuk basis data" di `Pertemuan04/05-keputusan-implementasi.md`.

## Relasi

```
alat (1) ───< (N) peminjaman        peminjaman.alat_id -> alat.id (cascadeOnDelete)
```

## Pemetaan field

| Antarmuka | Field | Kolom | Tipe | Wajib |
|---|---|---|---|---|
| Kartu/Detail Alat | Kode | alat.kode | varchar(20), unik | ya |
| Kartu/Detail Alat | Nama | alat.nama | varchar(100) | ya |
| Kartu/Detail Alat | Kategori | alat.kategori | varchar(50) | ya |
| Kartu/Detail Alat | Stok | alat.stok | unsigned int | ya |
| Kartu/Detail Alat | Lokasi | alat.lokasi | varchar(150) | ya |
| Detail Alat | Deskripsi | alat.deskripsi | text | tidak |
| Form Peminjaman | (alat dipilih dari URL) | peminjaman.alat_id | FK -> alat.id | ya |
| Form Peminjaman | Nama peminjam | peminjaman.nama_peminjam | varchar(100) | ya |
| Form Peminjaman | NIM | peminjaman.nim | varchar(20) | ya, 8-20 digit |
| Form Peminjaman | Tanggal peminjaman | peminjaman.tanggal_pinjam | date | ya, tidak boleh lampau (pengajuan baru) |
| Form Peminjaman | Durasi (hari) | peminjaman.durasi | unsigned smallint | ya, 1-30 |
| Form Peminjaman | Jumlah unit (baru) | peminjaman.jumlah | unsigned int | ya, 1 s.d. stok |
| Form Peminjaman | Keperluan | peminjaman.keperluan | text | ya, maks 500 karakter |
| (sistem) | Tanggal selesai | peminjaman.tanggal_selesai | date | otomatis |
| (sistem) | Status | peminjaman.status | varchar(20) | otomatis `menunggu` |
| (sistem) | Waktu dibuat/diubah | created_at, updated_at | timestamp | otomatis |

Status: `menunggu`, `disetujui`, `dipinjam`, `dikembalikan`, `ditolak`. Status **aktif** (menahan stok): `menunggu`, `disetujui`, `dipinjam`.

## Aturan bisnis

1. Semua field wajib terisi; NIM hanya angka 8-20 digit.
2. Pengajuan baru: tanggal pinjam >= hari ini; durasi 1-30 hari.
3. Jumlah unit <= stok alat.
4. Alat berstok 0 tidak dapat diajukan (form dan POST dialihkan ke detail).
5. Bentrok jadwal: untuk setiap hari dalam rentang pengajuan, `unit terpakai (status aktif) + jumlah diminta <= stok`.
6. Saat ubah pengajuan, pengajuan itu sendiri tidak dihitung; status `dikembalikan`/`ditolak` tidak diperiksa dan tidak menahan stok.
7. Stok alat tidak boleh diubah lebih kecil dari unit yang sudah dipesan pada jadwal mendatang.
8. Alat dengan peminjaman aktif mendatang tidak dapat dihapus.
