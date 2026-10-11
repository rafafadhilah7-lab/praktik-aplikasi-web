# 06 - Checklist Uji End-to-End

Isi kolom Hasil setelah menjalankan sendiri di browser. Simpan screenshot browser dan tabel database untuk tiap skenario ke `07-screenshots/` (nama berkas sesuai nomor).

Tes otomatis: `cd sipinjam-lab && php artisan test` (AlatDatabaseTest 11 tes, PeminjamanDatabaseTest 14 tes, ditambah ExampleTest bawaan). Tes otomatis melengkapi, bukan menggantikan, uji manual.

| No | Skenario | Langkah singkat | Bukti | Tes otomatis | Hasil |
|---|---|---|---|---|---|
| 1 | Daftar alat dari database | Buka `/alat/hasil` | Data browser = tabel `alat` (6 baris) | `test_beranda_dan_hasil_menampilkan_data_dari_database` | Belum / Sudah |
| 2 | Tambah alat | `/alat/tambah`, isi "Function Generator" | Record baru di `alat` | `test_tambah_alat` | Belum / Sudah |
| 3 | Ubah alat | Ubah stok pada alat uji | Nilai `stok` berubah di DB | `test_ubah_alat` | Belum / Sudah |
| 4 | Hapus alat | Hapus alat uji (tanpa peminjaman) | Record hilang, halaman tidak error | `test_hapus_alat_tanpa_peminjaman_aktif` | Belum / Sudah |
| 5 | Pengajuan valid | Ajukan 2 Multimeter, besok, 3 hari | Record `peminjaman` status `menunggu`, tampil halaman konfirmasi; reload tidak mengirim ulang | `test_pengajuan_valid_tersimpan_...` | Belum / Sudah |
| 6 | Jumlah > stok | Ajukan 3 Osiloskop (stok 2) | Galat pada field Jumlah Unit; tidak ada record baru | `test_jumlah_melebihi_stok_ditolak` | Belum / Sudah |
| 7 | Jadwal bentrok | Ajukan alat pada rentang yang stoknya habis | Galat "Jadwal bertabrakan..." dengan sisa unit | `test_skenario_bentrok_a_sampai_d` | Belum / Sudah |
| 8 | Ubah pengajuan | `/peminjaman` -> Ubah, status jadi Disetujui | Perubahan tersimpan di DB | `test_ubah_pengajuan_tidak_bentrok_...` | Belum / Sudah |
| 9 | Hapus pengajuan | Hapus dari `/peminjaman` | Record hilang | `test_hapus_pengajuan` | Belum / Sudah |
| 10 | Daftar pengajuan memuat nama alat | Buka `/peminjaman` | Kolom Alat terisi lewat relasi | `test_daftar_peminjaman_menampilkan_nama_alat_lewat_relasi` | Belum / Sudah |
| 11 | Pencarian dengan tanggal | `/alat/hasil?tanggal=<tanggal yang sudah ada pengajuan>` | Kartu menampilkan "Sisa pada tanggal dipilih" | `test_hasil_dengan_tanggal_menampilkan_sisa_unit` | Belum / Sudah |
| 12 | Alat stok 0 tidak dapat diajukan | Buka `/alat/22/pinjam` | Dialihkan ke `/alat/22` dengan pesan galat | `test_alat_stok_kosong_tidak_bisa_diajukan` | Belum / Sudah |
| 13 | Responsif mobile | DevTools lebar 390 px | Form dan tabel tidak meluber (tabel menggulir di dalam kartu) | manual | Belum / Sudah |

## Bukti database sebelum dan sesudah

```sql
SELECT id, kode, nama, stok FROM alat;
SELECT p.id, a.nama AS alat, p.nama_peminjam, p.tanggal_pinjam, p.tanggal_selesai, p.jumlah, p.status
FROM peminjaman p JOIN alat a ON a.id = p.alat_id ORDER BY p.id;
```

Jalankan sebelum dan sesudah tiap operasi lalu screenshot hasilnya.
