# Pertemuan 05 - Basis Data, Migration, Seeder, dan CRUD SIPINJAM-LAB

Praktik Aplikasi Web (INF60295). Kelanjutan Pertemuan 04: data alat contoh di controller diganti tabel database dan pengajuan peminjaman benar-benar tersimpan.

## Daftar Dokumen

| Berkas | Keterangan |
|---|---|
| `01-data-model.md` | Pemetaan antarmuka ke tabel, relasi, aturan bisnis |
| `02-migration-model.md` | Migration dan model Eloquent |
| `03-seeder.md` | Seeder dan cara menjalankan |
| `04-crud-alat.md` | CRUD alat: route, controller, view |
| `05-crud-peminjaman.md` | CRUD pengajuan, validasi stok, dan cek bentrok jadwal |
| `06-test-checklist.md` | Checklist uji end-to-end dan tes otomatis |
| `07-screenshots/` | Bukti tangkapan layar (diisi setelah uji manual) |
| `refleksi.md` | Draf refleksi individu |

## Menjalankan aplikasi

Aplikasi ada di folder `sipinjam-lab/`.

1. Aktifkan MySQL/MariaDB (XAMPP/Laragon) dan buat database kosong `sipinjam_lab`.
2. Di `.env` (salin dari `.env.example` bila belum ada) pastikan:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sipinjam_lab
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   `.env` sudah ada di `.gitignore`; jangan di-commit.
3. Jalankan:
   ```bash
   cd sipinjam-lab
   composer install
   npm install --ignore-scripts && npm run build   # atau npm run dev
   php artisan key:generate                        # jika APP_KEY masih kosong
   php artisan config:clear
   php artisan migrate:fresh --seed
   php artisan serve
   ```
4. Buka `http://127.0.0.1:8000/alat`.
5. Tes otomatis (memakai SQLite di memori, tidak menyentuh MySQL): `php artisan test`

## Keputusan dan perbedaan dengan modul

| Modul | SIPINJAM-LAB | Alasan |
|---|---|---|
| Tabel `rooms`, `bookings` | `alat`, `peminjaman` | Nama field Pertemuan 04 sudah berbahasa Indonesia (`kode`, `nama`, `stok`, `nama_peminjam`, ...) dan dipertahankan agar komponen Blade lama tetap berfungsi tanpa diubah. |
| `capacity` ruang | `stok` alat | Alat dipinjam per unit. Stok 0 berarti tidak tersedia (keputusan K3 Pertemuan 04), sehingga tidak ada kolom `is_available`. |
| `participants` | `jumlah` (field baru pada form) | Diperlukan untuk validasi "jumlah tidak melebihi stok". |
| `date`, `start_time`, `end_time` | `tanggal_pinjam`, `durasi` (hari), `tanggal_selesai` | Alat dipinjam per hari, sesuai form Pertemuan 04. `tanggal_selesai` = `tanggal_pinjam` + `durasi` - 1 dan diisi otomatis oleh model. |
| Bentrok = jadwal ruang tumpang tindih | Pada hari mana pun, unit terpakai + unit diminta <= stok | Satu alat bisa dipinjam beberapa orang selama stok cukup. |
| Pengajuan menampilkan konfirmasi dari respons POST | Redirect ke `/peminjaman/{id}` (Post/Redirect/Get) | Menutup catatan K5 Pertemuan 04: reload tidak mengirim ulang form. |
| Hapus alat memicu cascade | Ditolak jika masih ada peminjaman aktif mendatang | Mencegah pengajuan aktif hilang tanpa sengaja. Riwayat selesai/ditolak tetap ikut terhapus (cascade). |
| Pesan validasi bawaan (Inggris) | `App\Support\PesanValidasi` (Indonesia) | Proyek belum punya folder `lang/id`. |

Temuan Pertemuan 04 yang ikut diperbaiki: filter `tanggal` pada pencarian kini berpengaruh (menampilkan sisa unit), tanggal lampau ditolak, pencarian id/404/cek stok tidak lagi diulang (route model binding), judul halaman detail memakai akhiran "- SIPINJAM-LAB", dan README aplikasi dibuat.

## Keterbatasan yang diketahui

- Tidak ada autentikasi: siapa pun dapat menambah, mengubah, atau menghapus alat dan mengubah status pengajuan. Dibahas pada pertemuan autentikasi/otorisasi.
- Verifikasi NIM, jaminan KTM/KTP, denda keterlambatan, dan QRIS (US-01, US-05, US-06, US-08) belum diimplementasikan.
- Pembatasan 1-30 hari (dari Pertemuan 04) masih asumsi dan belum dicocokkan dengan acceptance criteria US-02.
