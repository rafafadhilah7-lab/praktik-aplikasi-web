# 05 - Keputusan Implementasi SIPINJAM-LAB

Pertemuan 4 - Praktik Aplikasi Web (INF60295)
Penyusun: Rafa Fadhilah
Tanggal: 11 Oktober 2026

## 1. Ringkasan

Antarmuka awal dibangun dengan Laravel 13.35.0 (Blade, Vite, Tailwind CSS 4) dan PHP 8.3.33. Data alat berupa data contoh di controller, dan pengajuan peminjaman bersifat simulasi (tidak disimpan ke database).

## 2. Halaman dan route

| URL | Nama route | Penanganan | View | Fungsi |
| :--- | :--- | :--- | :--- | :--- |
| `/` | - | closure | `welcome` | Halaman bawaan Laravel (belum diganti) |
| `/awal` | - | closure | `awal` | Halaman praktikum Pertemuan 1 |
| `/komponen` | - | `Route::view` | `pages.komponen` | Galeri komponen UI |
| `/alat` | `alat.beranda` | `AlatController@beranda` | `pages.beranda` | Beranda dan pencarian alat |
| `/alat/hasil` | `alat.hasil` | `AlatController@hasil` | `pages.hasil` | Hasil pencarian |
| `/alat/{id}` | `alat.detail` | `AlatController@detail` | `pages.detail-alat` | Detail alat |
| `GET /alat/{id}/pinjam` | `alat.pinjam` | `AlatController@formPinjam` | `pages.form-pinjam` | Form pengajuan |
| `POST /alat/{id}/pinjam` | `alat.ajukan` | `AlatController@ajukan` | `pages.konfirmasi-pinjam` | Validasi dan konfirmasi (simulasi) |

## 3. Keputusan implementasi

### K1. Data contoh di controller, belum database
- Keputusan: enam alat contoh disimpan sebagai array di `AlatController::semuaAlat()`.
- Alternatif: langsung memakai SQLite dan migration.
- Alasan: modul Pertemuan 4 berfokus pada antarmuka. Basis data dibahas pada pertemuan berikutnya.
- Konsekuensi: stok tidak berkurang dan pengajuan tidak tersimpan.

### K2. Pencarian lewat query string (metode GET)
- Keputusan: form pencarian mengirim `q` (nama), `kategori`, dan `tanggal` ke `/alat/hasil`. Pencarian nama tidak peka huruf besar-kecil dan kategori dicocokkan persis.
- Alternatif: penyaringan di browser dengan JavaScript.
- Alasan: hasil pencarian dapat dibuka ulang lewat URL dan mudah diuji.
- Catatan: `tanggal` baru diteruskan ke tampilan dan belum memengaruhi hasil, karena jadwal peminjaman baru ada setelah basis data tersedia.

### K3. Alat berstok kosong tetap ditampilkan, tetapi tidak dapat diajukan
- Keputusan: alat berstok 0 muncul dengan badge "Stok habis" dan tombol nonaktif. Jika URL form peminjaman atau POST pengajuannya tetap dibuka langsung, pengguna dialihkan ke halaman detail dengan pesan "Alat sedang tidak tersedia untuk dipinjam."
- Alasan: pengguna perlu tahu alat itu ada, dan pemeriksaan di sisi server diperlukan karena URL dapat diketik langsung.
- Hasil uji: `/alat/22/pinjam` mengembalikan status 302 ke `/alat/22`.

### K4. Validasi di sisi server
- Keputusan: pengajuan divalidasi dengan `$request->validate()`. Jika gagal, pesan galat ditampilkan pada form dan isian yang sudah diketik dipertahankan.
- Alternatif: validasi JavaScript saja.
- Alasan: validasi server tidak dapat dilewati, dan sesuai contoh pesan "Lama peminjaman wajib dipilih." pada failure path.

### K5. Konfirmasi ditampilkan langsung dari respons POST
- Keputusan: setelah validasi lolos, controller langsung menampilkan halaman konfirmasi tanpa menyimpan data dan tanpa pengalihan.
- Konsekuensi: jika halaman konfirmasi dimuat ulang, browser meminta persetujuan untuk mengirim ulang form. Pola Post/Redirect/Get akan dipakai ketika data sudah disimpan.

### K6. Urutan route dan batasan parameter
- `/alat/hasil` didefinisikan sebelum `/alat/{id}`, dan `{id}` dibatasi angka dengan `whereNumber('id')`, sehingga kata "hasil" tidak dianggap id.
- Hasil uji: `/alat/999` menghasilkan 404.

### K7. Komponen Blade dan token
- Keputusan: tampilan memakai komponen Blade (`button`, `field`, `card`, `badge`, `alat-card`, `search-form`) dengan gaya di `components.css` yang bersumber dari `tokens.css`. Tailwind dipakai untuk tata letak.
- Rincian ada di `01-design-tokens.md` dan `02-component-inventory.md`.

## 4. Aturan validasi pengajuan

| Field | Aturan |
| :--- | :--- |
| `nama_peminjam` | wajib, teks, maksimal 100 karakter |
| `nim` | wajib, teks, maksimal 30 karakter |
| `tanggal_pinjam` | wajib, format tanggal valid |
| `durasi` | wajib, bilangan bulat, 1 sampai 30 (hari) |
| `keperluan` | wajib, teks, maksimal 500 karakter |

Catatan: batas durasi 1 sampai 30 hari adalah asumsi implementasi dan belum dicocokkan dengan acceptance criteria US-02.

## 5. Keterkaitan dengan user story

| User story | Status pada Pertemuan 4 |
| :--- | :--- |
| US-02 Memilih alat dan durasi | Sebagian: alat dipilih lewat daftar dan durasi diisi pada form. Belum dicocokkan butir demi butir dengan acceptance criteria |
| US-01 Verifikasi NIM | Belum: NIM hanya kolom isian dan belum diverifikasi |
| US-06 Jaminan KTM/KTP | Belum diimplementasikan |
| US-08 Denda otomatis | Belum diimplementasikan |
| US-05 Pembayaran denda QRIS | Belum diimplementasikan |

## 6. Keterbatasan dan temuan yang belum diperbaiki

- Pengajuan tidak disimpan dan stok tidak berkurang.
- `tanggal` pada pencarian belum memengaruhi hasil.
- Validasi tanggal hanya memeriksa format, belum menolak tanggal yang sudah lewat.
- Tidak ada autentikasi, nama dan NIM diketik manual.
- Judul halaman detail alat belum memakai akhiran "- SIPINJAM-LAB".
- Alamat `/` masih halaman bawaan Laravel.
- Pencarian alat berdasarkan id, respons 404, dan pemeriksaan stok ditulis berulang di `detail`, `formPinjam`, dan `ajukan`.

## 7. Pemetaan untuk basis data (pertemuan berikutnya)

Alat:

| Field | Tipe | Contoh |
| :--- | :--- | :--- |
| `id` | bilangan bulat | 1 |
| `kode` | teks | ALT-001 |
| `nama` | teks | Multimeter Digital |
| `kategori` | teks | Elektronika |
| `stok` | bilangan bulat | 5 |
| `lokasi` | teks | Lab Elektronika Dasar |

Pengajuan peminjaman (field pada form, ditambah rujukan alat):

| Field | Tipe | Contoh |
| :--- | :--- | :--- |
| `alat_id` | bilangan bulat | 1 |
| `nama_peminjam` | teks | Dimas Prasetyo |
| `nim` | teks | 2200012345 |
| `tanggal_pinjam` | tanggal | 2026-10-12 |
| `durasi` | bilangan bulat (hari) | 3 |
| `keperluan` | teks | Praktikum Rangkaian Listrik |

Data contoh: enam alat (ALT-001, 014, 022, 031, 040, 052) dengan kategori Elektronika, Biologi, dan Kimia. ALT-022 sengaja berstok 0 untuk menguji penanganan alat tidak tersedia.