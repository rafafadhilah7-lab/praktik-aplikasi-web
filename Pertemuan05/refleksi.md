# Refleksi Individu (draf, sesuaikan dengan pengalaman Anda)

Nama: ............  NIM: ............

1. **Statis vs dinamis.** Pada Pertemuan 04 daftar alat berupa array di `AlatController` dan pengajuan hanya ditampilkan ulang. Sekarang data dibaca dan ditulis ke MySQL lewat Eloquent, sehingga pengajuan bertahan setelah server dimatikan dan stok memengaruhi pengajuan berikutnya.
2. **Migration vs tabel manual.** Migration bisa dijalankan ulang (`migrate:fresh --seed`) oleh anggota kelompok lain dan terlacak di Git; tabel manual di phpMyAdmin tidak punya riwayat.
3. **Relasi Alat-Peminjaman.** Satu alat memiliki banyak peminjaman (`hasMany`), setiap peminjaman milik satu alat (`belongsTo`) lewat `alat_id`. Daftar pengajuan memakai `with('alat')` agar nama alat dimuat tanpa query berulang.
4. **Validasi terpenting.** Cek stok dan bentrok jadwal, karena tanpa itu data tampak valid tetapi tidak masuk akal (unit dipinjam melebihi jumlah fisik). FK dan kode unik mencegah data yatim dan ganda.
5. **Masalah dan perbaikan.** *(Isi dengan masalah nyata yang Anda temui dan solusinya.)*
6. **Kontribusi dan bukti commit.** *(Isi bagian yang Anda kerjakan dan hash/tautan commit.)*
