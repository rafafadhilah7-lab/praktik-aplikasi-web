# 01 - Design Tokens SIPINJAM-LAB

Pertemuan 4 - Praktik Aplikasi Web (INF60295)
Penyusun: Rafa Fadhilah

## 1. Sumber dan keputusan

Token diambil dari `DESIGN.md` hasil ekspor Stitch (folder `Pertemuan03`). Dokumen itu memuat dua palet yang berbeda:

| Token | Palet A (blok konfigurasi, dipakai di semua `code.html`) | Palet B (teks penjelasan) |
| :--- | :--- | :--- |
| primary | `#091426` | `#1E293B` |
| surface | `#f8f9ff` | `#F8FAFC` |
| aksi/secondary | `#0051d5` | `#2563EB` |
| error | `#ba1a1a` | `#DC2626` |

**Keputusan: memakai Palet A**, karena itulah yang dirender pada layar desain Pertemuan 3 sehingga hasil browser bisa dibandingkan langsung dengan prototype. Palet A tidak memiliki warna sukses dan peringatan, sehingga `#059669` (sukses) dan `#d97706` (peringatan) dipinjam dari teks Palet B. Warna badge status juga dipinjam dari Palet B.

Token tersimpan di `sipinjam-lab/resources/css/tokens.css` dan dipakai oleh `components.css`. Nilai warna, jarak, dan radius tidak ditulis ulang di tempat lain.

## 2. Warna

| Token | Nilai | Contoh penggunaan |
| :--- | :--- | :--- |
| `--color-primary` | `#091426` | Header, tombol utama |
| `--color-primary-hover` | `#1e293b` | Hover tombol utama, bayangan modal |
| `--color-action` | `#0051d5` | Fokus input, tautan, aksi |
| `--color-surface` | `#f8f9ff` | Latar halaman |
| `--color-card` | `#ffffff` | Latar kartu dan field |
| `--color-inset` | `#eff4ff` | Latar nonaktif dan kartu tidak tersedia |
| `--color-text` | `#0b1c30` | Judul dan isi |
| `--color-text-muted` | `#45474c` | Keterangan sekunder |
| `--color-placeholder` | `#62646a` | Placeholder (disesuaikan dari `#75777d`, lihat bagian 5) |
| `--color-border` | `#c5c6cd` | Garis kartu dan field |
| `--color-border-strong` | `#75777d` | Hover tombol sekunder |
| `--color-danger` | `#ba1a1a` | Pesan galat dan bingkai field galat |
| `--color-success` | `#059669` | Hanya ikon dan garis, bukan teks |
| `--color-warning` | `#d97706` | Hanya ikon dan garis, bukan teks |

### Warna badge status

| Status | Latar | Teks | Garis |
| :--- | :--- | :--- | :--- |
| success (Tersedia, Disetujui) | `#ecfdf5` | `#065f46` | `#a7f3d0` |
| warning (Menunggu verifikasi) | `#fffbeb` | `#92400e` | `#fde68a` |
| info (Dipinjam) | `#eff6ff` | `#1e40af` | `#bfdbfe` |
| danger (Ditolak, Terlambat, Stok habis) | `#fef2f2` | `#991b1b` | `#fecaca` |

## 3. Tipografi

| Token | Ukuran / tinggi baris | Penggunaan |
| :--- | :--- | :--- |
| `--text-display` | 44px / 52px (mobile 32px / 40px) | Judul besar |
| `--text-h1` | 30px / 38px (mobile 24px / 32px) | Judul halaman |
| `--text-h2` | 20px / 28px | Subjudul |
| `--text-h3` | 16px / 24px | Judul kartu |
| `--text-body-lg` | 16px / 26px | Isi besar |
| `--text-body` | 14px / 22px | Isi |
| `--text-caption` | 13px / 18px | Teks bantuan |
| `--text-label` | 12px / 16px | Label field |
| `--text-code` | 11px / 14px | Kode aset, badge |

Font judul: Space Grotesk. Font isi: Geist. Keduanya dimuat dari Google Fonts di `layouts/app.blade.php` dengan fallback `system-ui`.

## 4. Jarak, bentuk, dan lainnya

| Token | Nilai |
| :--- | :--- |
| `--space-1` sampai `--space-12` | 4, 8, 12, 16, 24, 32, 48 px |
| `--radius` | 0 (sudut kotak tajam, sesuai `DESIGN.md`) |
| `--border-width` | 1px |
| `--control-height` | 40px (tombol kecil 32px) |
| `--focus-ring` | 2px solid `#0051d5` |
| `--shadow-modal` | `4px 4px 0 0 #1e293b` (belum dipakai) |

## 5. Pemeriksaan kontras (WCAG)

Rasio dihitung dengan rumus luminansi relatif WCAG. Batas minimal teks biasa adalah 4,5:1.

| Pasangan | Rasio | Hasil |
| :--- | :--- | :--- |
| Teks utama `#0b1c30` di `#f8f9ff` | 16,34 | Lolos |
| Teks utama di kartu putih | 17,17 | Lolos |
| Teks sekunder `#45474c` di putih | 9,30 | Lolos |
| Placeholder `#62646a` di putih | 5,91 | Lolos |
| Putih di tombol `#091426` | 18,43 | Lolos |
| Aksi `#0051d5` di putih | 6,69 | Lolos |
| Galat `#ba1a1a` di putih | 6,46 | Lolos |
| Badge tersedia / menunggu / dipinjam / danger | 7,29 / 6,84 / 8,01 / 7,60 | Lolos |

Penyesuaian dan batasan:

- Placeholder bawaan `DESIGN.md` (`#75777d`) hanya 4,48:1, sehingga diganti `#62646a`.
- Hijau `#059669` di putih hanya 3,77:1 dan amber `#d97706` hanya 3,19:1. Keduanya **tidak dipakai untuk teks**.

## 6. Yang belum diuji

- Warna badge dan hover belum dibandingkan piksel demi piksel dengan layar Stitch.
- Radius 0: konfigurasi Tailwind pada HTML Stitch menetapkan radius bawaan 0,25rem, sedangkan `DESIGN.md` menyatakan 0. Implementasi mengikuti `DESIGN.md`.
- Kepatuhan halaman pencarian, detail, form, dan konfirmasi terhadap token belum diperiksa satu per satu.