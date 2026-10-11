# Component Inventory — SIPINJAM-LAB

## 1. Tujuan

Dokumen ini mencatat komponen Blade yang digunakan pada antarmuka SIPINJAM-LAB, termasuk parameter (props), perilaku, state, dan komponen lain yang digunakan di dalamnya.

## 2. Komponen `x-alat-card`

**File sumber:** `resources/views/components/alat-card.blade.php`

### Props

| Prop | Tipe | Nilai default | Keterangan |
|---|---|---|---|
| `alat` | Array | Tidak ditentukan | Data alat yang ditampilkan pada kartu. |

### Data yang ditampilkan

| Data | Penggunaan |
|---|---|
| `nama` | Judul kartu alat. |
| `kode` | Kode alat yang diteruskan ke `x-card`. |
| `stok` | Jumlah stok yang ditampilkan. |
| `kategori` | Kategori alat. |
| `lokasi` | Lokasi alat. |
| `id` | Digunakan untuk membentuk tautan ke halaman detail. |

### State dan perilaku

Komponen menghitung variabel `$ada` berdasarkan kondisi `$alat['stok'] > 0`.

| Kondisi | Tampilan/perilaku |
|---|---|
| Stok lebih dari 0 | Status `normal`, badge `tersedia`, dan tombol **Lihat Detail**. |
| Stok 0 atau kurang | Status `unavailable`, badge `habis`, dan tombol **Tidak tersedia** yang dinonaktifkan. |

### Komponen yang digunakan

- `x-card` untuk wadah kartu.
- `x-badge` untuk status ketersediaan.
- `x-button` untuk tindakan pengguna.

## 3. Komponen `x-search-form`

**File sumber:** `resources/views/components/search-form.blade.php`

### Props

| Prop | Tipe | Nilai default | Keterangan |
|---|---|---|---|
| `q` | String | `''` | Nilai pencarian nama alat. |
| `kategori` | String | `''` | Kategori yang dipilih. |
| `tanggal` | String | `''` | Tanggal peminjaman. |
| `daftarKategori` | Array | `[]` | Daftar opsi kategori pada dropdown. |

### Elemen formulir

| Elemen | Fungsi |
|---|---|
| Nama alat | Input pencarian dengan contoh placeholder `Multimeter`. |
| Kategori | Dropdown dengan pilihan awal `Semua kategori` dan opsi dari `daftarKategori`. |
| Tanggal pinjam | Input bertipe tanggal. |
| Cari Alat | Tombol untuk mengirim formulir. |

### Perilaku

- Formulir menggunakan metode `GET`.
- Formulir mengirim data ke route `alat.hasil`.
- Nilai pencarian dan tanggal diteruskan melalui props.
- Pilihan kategori ditandai menggunakan `@selected($kategori === $k)`.
- Formulir menggunakan atribut `role="search"`.

### Komponen yang digunakan

- `x-field` untuk input nama alat dan tanggal.
- `x-button` untuk tombol pencarian.

## 4. Ringkasan

| Komponen | Tanggung jawab utama | Interaksi |
|---|---|---|
| `x-alat-card` | Menampilkan ringkasan alat dan ketersediaannya. | Membuka detail alat jika stok tersedia. |
| `x-search-form` | Menyediakan filter pencarian alat. | Mengirim kata kunci, kategori, dan tanggal melalui GET. |

## 5. Catatan

Inventaris ini dibuat berdasarkan kode `alat-card.blade.php` dan `search-form.blade.php`. Komponen lain seperti `x-card`, `x-badge`, `x-button`, dan `x-field` disebut sebagai dependensi; rincian props internalnya tidak dijabarkan dalam dokumen ini.