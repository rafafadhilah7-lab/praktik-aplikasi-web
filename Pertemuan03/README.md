
# SIPINJAM-LAB — Pertemuan 3

**Arsitektur Informasi, User Flow, Wireframe, dan Prototipe Awal**

- **Mata kuliah:** Praktik Aplikasi Web (INF60295)
- **Dosen:** Zaenal Mustofa, M.Kom.
- **Nama proyek:** SIPINJAM-LAB — Sistem Peminjaman Alat Laboratorium
- **Anggota:** Setyo Adi Muhammad Iqbal dan Rafa Fadhilah
- **Tanggal revisi:** 11 Oktober 2026
- **Repositori:** [praktik-aplikasi-web](https://github.com/rafafadhilah7-lab/praktik-aplikasi-web)

## 1. Anggota Kelompok

| Nama | NIM | Kontribusi | Bukti (commit/artefak) |
|---|---|---|---|
| Setyo Adi Muhammad Iqbal | 24051130052 | Isi sesuai pekerjaan yang dilakukan | Isi bukti pekerjaan atau commit |
| Rafa Fadhilah | 24051130041 | Isi sesuai pekerjaan yang dilakukan | Isi bukti pekerjaan atau commit |

**Catatan:** Lengkapi kolom kontribusi berdasarkan pekerjaan masing-masing anggota. Jangan mencantumkan pekerjaan atau bukti yang belum dilakukan.

## 2. Daftar Artefak

| Berkas | Keterangan |
|---|---|
| `01-scope-canvas.pdf` | Seleksi user story, scope canvas, keterhubungan acceptance criteria dengan layar, dan inventaris layar. |
| `02-sitemap.pdf` | Sitemap dan daftar halaman aplikasi. |
| `03-user-flow-pengguna.pdf` | User flow mahasiswa, termasuk happy path dan jalur gagal. |
| `04-user-flow-admin.pdf` | User flow laboran, termasuk happy path dan jalur gagal. |
| `05-wireframe-desktop.pdf` | Wireframe desktop dengan lebar acuan 1280 px, mencakup enam layar (W1–W6) beserta state. |
| `06-wireframe-mobile.pdf` | Wireframe mobile dengan lebar acuan 390 px, mencakup enam layar yang dipasangkan dengan versi desktop. |
| `07-usability-walkthrough.pdf` | Skenario, peserta, temuan, dan revisi usability walkthrough. |
| `08-keputusan-desain.md` | Keputusan desain dan alasan pemilihannya. |
| `prototype/index.html` | Prototipe interaktif berbasis HTML, CSS, dan JavaScript. |
| `refleksi-individu-Setyo-Adi-Muhammad-Iqbal.md` | Dokumen refleksi individu atas nama Setyo Adi Muhammad Iqbal. |
| `refleksi-individu-TEMPLATE.md` | Template refleksi individu yang perlu disalin dan diisi oleh setiap mahasiswa. |

## 3. Ruang Lingkup

Desain pada pertemuan ini berfokus pada user story berikut:

- **US-01:** Akses atau autentikasi pengguna.
- **US-02:** Pemilihan alat dan pengajuan peminjaman.
- **US-06:** Pemeriksaan jaminan fisik oleh laboran saat serah terima.

US-05 mengenai pembayaran denda melalui QRIS dan US-08 mengenai perhitungan denda ditunda ke pertemuan berikutnya karena membutuhkan layar tambahan. Alasan penentuan ruang lingkup dijelaskan dalam `01-scope-canvas.pdf`.

## 4. Prototipe Interaktif

Prototipe dapat dibuka secara lokal melalui browser dengan membuka berkas:

`prototype/index.html`

Akun uji yang digunakan dalam prototipe:

- **NIM:** `2200012345`
- **Kata sandi:** bebas sesuai mekanisme prototipe

Akun tersebut merupakan akun uji prototipe, bukan bukti bahwa sistem telah terhubung ke database mahasiswa atau sistem akademik kampus.

### 4.1 Alur Utama

Alur utama yang didokumentasikan adalah:

1. Login.
2. Daftar Alat.
3. Detail Alat.
4. Formulir Peminjaman dengan durasi dua hari.
5. Konfirmasi Pengajuan.
6. Pengiriman Pengajuan.
7. Halaman Peminjaman Saya.
8. Rincian Pengajuan.

### 4.2 Skenario Jalur Gagal

- **Login gagal:** masukkan NIM selain `2200012345` untuk memeriksa pesan NIM tidak ditemukan.
- **Durasi belum dipilih:** pada formulir, tekan tombol Ajukan tanpa memilih lama peminjaman untuk memeriksa validasi.
- **Alat tidak tersedia:** buka detail Osiloskop Digital dan periksa apakah tombol Pinjam dinonaktifkan.

Skenario tersebut perlu diuji kembali sebelum laporan final dikumpulkan.

### 4.3 Memulai Ulang Prototipe

Gunakan tombol **Mulai ulang prototipe** jika tersedia pada tampilan prototipe untuk mengulang skenario pengujian.

## 5. Tautan Prototipe Online

Tautan GitHub Pages yang direncanakan:

[Prototipe SIPINJAM-LAB](https://rafafadhilah7-lab.github.io/praktik-aplikasi-web/Pertemuan03/prototype/)

Tautan ini baru dapat digunakan setelah GitHub Pages diaktifkan dan proses publikasi berhasil. Periksa tautan melalui browser, termasuk jendela incognito, sebelum menyerahkannya kepada dosen.

## 6. Pemeriksaan Sebelum Pengumpulan

- [ ] Semua PDF dapat dibuka dan isinya terbaca.
- [ ] Sitemap sesuai dengan halaman yang dirancang.
- [ ] User flow sesuai dengan wireframe dan prototipe.
- [ ] Wireframe desktop dan mobile konsisten.
- [ ] Prototipe diuji melalui alur utama dan jalur gagal.
- [ ] Hasil walkthrough sesuai dengan pengujian yang benar-benar dilakukan.
- [ ] Kontribusi setiap anggota telah diisi dengan benar.
- [ ] Refleksi individu telah dilengkapi oleh anggota yang bersangkutan.
- [ ] Tautan GitHub Pages telah diuji.
- [ ] File lama yang tidak lagi digunakan telah diperiksa sebelum dihapus dari repositori.

## 7. Git

Setelah seluruh perubahan diperiksa, gunakan Git untuk menyimpan dan mengirim perubahan ke repositori.

Periksa terlebih dahulu perubahan yang akan disertakan:

```bash
git status
```

Setelah yakin file yang berubah sudah benar, tambahkan file yang memang ingin disimpan:

```bash
git add Pertemuan03
git add Pertemuan02/03-user-story.xlsx
```

Periksa kembali file yang masuk ke staging:

```bash
git status
git diff --cached --stat
```

Jika semuanya sudah sesuai, buat commit:

```bash
git commit -m "docs: revise SIPINJAM-LAB meeting 3 artifacts"
```

Kemudian kirim ke GitHub:

```bash
git push origin main
```

**Penting:** Perintah `git add Pertemuan03` juga akan memasukkan penghapusan file lama di folder tersebut. Periksa hasil staging sebelum melakukan commit. Jika masih ada file lama yang seharusnya dipertahankan, jangan lanjutkan commit sebelum memperbaikinya.
