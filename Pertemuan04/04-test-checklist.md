# 04 - Checklist Uji Antarmuka SIPINJAM-LAB

Pertemuan 4 - Praktik Aplikasi Web (INF60295)
Penyusun: Rafa Fadhilah
Tanggal uji otomatis: 11 Oktober 2026 (server lokal `php artisan serve`, PHP 8.3.33, Laravel 13.35.0)

Data alat pada aplikasi adalah data contoh (belum dari database).

## 1. Checklist modul

| No. | Pemeriksaan | Hasil | Catatan |
| :---: | :--- | :---: | :--- |
| 1 | Halaman dapat dijalankan dari instruksi README | Belum | README belum dibuat |
| 2 | Tidak ada error pada console browser | Belum | Belum diperiksa lewat DevTools |
| 3 | Warna, tipografi, jarak, dan radius mengikuti token | Belum | Belum diperiksa pada halaman baru |
| 4 | Navigasi dari hasil ke detail dan form berjalan | Sudah | Delapan tautan unik dari enam halaman semuanya berstatus 200 (uji otomatis) |
| 5 | State kosong, error, dan sukses dapat ditampilkan | Sudah | Hasil kosong dan alat stok kosong lolos uji otomatis. Pesan validasi dan halaman konfirmasi dicoba manual (uji 1 sampai 5) |
| 6 | Tampilan desktop dan mobile tidak terpotong atau meluber | Sudah | Dicoba manual pada lebar 390 px (uji 7) |
| 7 | Tombol dan field memiliki label yang jelas | Belum | Belum diperiksa manual |
| 8 | Kontras teks dan latar dapat dibaca | Sebagian | Rasio pasangan warna token dihitung dengan rumus WCAG. Kontras pada halaman nyata belum diperiksa |
| 9 | Kode komponen tidak diduplikasi tanpa alasan | Belum | Belum ditinjau |
| 10 | Perubahan sudah di-commit dan didorong ke repositori | Sebagian | Kode ada di `origin/main` (commit `e25138b`). Dokumen Pertemuan 4 belum di-commit |

## 2. Hasil uji otomatis alamat

| Alamat | Status | Kartu | Keterangan |
| :--- | :---: | :---: | :--- |
| `/` | 200 | 0 | Masih halaman bawaan Laravel |
| `/alat` | 200 | 3 | Beranda/Cari Alat |
| `/alat/hasil` | 200 | 6 | Semua alat |
| `/alat/hasil?q=multi` | 200 | 1 | Pencarian nama |
| `/alat/hasil?q=zzz` | 200 | 0 | Hasil kosong |
| `/alat/hasil?kategori=Elektronika` | 200 | 4 | Filter kategori |
| `/alat/1` | 200 | - | Detail Alat |
| `/alat/1/pinjam` | 200 | - | Form Peminjaman Alat |
| `/alat/22` | 200 | - | Detail alat stok kosong |
| `/alat/22/pinjam` | 302 | - | Dialihkan ke `/alat/22` (alat stok kosong tidak dapat diajukan) |
| `/alat/999` | 404 | - | Alat tidak ada |

Hasil uji tautan: `/alat`, `/alat/1`, `/alat/1/pinjam`, `/alat/14`, `/alat/31`, `/alat/40`, `/alat/52`, `/alat/hasil` semuanya berstatus 200.

## 3. Uji manual (diisi setelah dicoba di browser)

| No. | Uji | Hasil | Catatan |
| :---: | :--- | :---: | :--- |
| 1 | Form `/alat/1/pinjam` dikirim dalam keadaan kosong menampilkan pesan validasi | Sudah | Dicoba langsung oleh penyusun di browser |
| 2 | Isian yang sudah diketik tidak hilang saat validasi gagal | Sudah | Dicoba langsung oleh penyusun di browser |
| 3 | Form valid menampilkan halaman konfirmasi dengan data yang diisi | Sudah | Dicoba langsung oleh penyusun di browser |
| 4 | Alat stok kosong tidak dapat diajukan dan ada penjelasan | Sudah | Dicoba langsung oleh penyusun di browser |
| 5 | Hasil kosong menampilkan pesan yang jelas | Sudah | Dicoba langsung oleh penyusun di browser |
| 6 | Tombol dan tautan kembali tidak mengarah ke halaman kosong | Sudah | Dicoba langsung oleh penyusun di browser |
| 7 | Semua halaman pada 390 px tidak meluber | Sudah | Dicoba langsung oleh penyusun di browser |

## 4. Temuan yang belum diperbaiki

- Judul halaman detail alat ("Detail Alat") belum memakai akhiran "- SIPINJAM-LAB" seperti halaman lain.
- Alamat `/` masih halaman bawaan Laravel.