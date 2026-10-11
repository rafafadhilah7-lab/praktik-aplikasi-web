# Refleksi Individu: Pertemuan 3

**Nama:** Setyo Adi Muhammad Iqbal · **NIM:** 24051130052

**1. Keputusan desain apa yang paling kuat kaitannya dengan persona?**
Keputusan yang paling terkait dengan persona adalah menjadikan jaminan KTM/KTP terlihat sejak form pengajuan, dan membuat tombol Konfirmasi Serah Terima nonaktif sampai jaminan dicentang. Bu Sari sebagai laboran butuh jaminan fisik tercatat sebelum alat diserahkan, sehingga layar tidak boleh membiarkan status berubah menjadi Aktif tanpa jaminan (US-06, AC-08). Untuk Dimas, saya memakai pola mobile-first dan form ringkas dengan NIM yang terverifikasi otomatis, karena ia mengajukan peminjaman dari ponsel di sekitar lab dan ingin prosesnya cepat.

**2. Bagian user flow mana yang berubah setelah dibuat wireframe?**
Setelah wireframe dibuat, Detail Alat, Konfirmasi, dan Detail Pengajuan yang awalnya halaman terpisah menjadi panel atau langkah di halaman yang sama, supaya jumlah halaman berkurang dan tidak ada jalan buntu. Selain itu tanggal kembali dihitung otomatis dari lama peminjaman, sehingga kasus tanggal kembali lebih awal dari tanggal pinjam (AC-05) dicegah lewat desain. Ruang lingkup juga dipersempit ke US-01, US-02, dan US-06 karena denda dan QRIS membutuhkan layar tambahan.

**3. Apa perbedaan terpenting antara layout desktop dan mobile?**
Di desktop, navigasi ada di atas dan panel detail muncul di sisi kanan daftar sehingga pengguna melihat daftar dan detail sekaligus. Di mobile, konten menjadi satu kolom, navigasi pindah ke tab bar bawah yang mudah dijangkau jempol, panel detail berubah menjadi bottom sheet, dan tombol utama selebar layar dengan tinggi minimal 44 px. Jadi versi mobile bukan sekadar diperkecil, tetapi susunan prioritas dan navigasinya diubah.

**4. Temuan usability apa yang paling berpengaruh?**
Temuan yang paling berpengaruh adalah penguji sempat mencari status pengajuan di Dashboard sebelum menemukan menu Peminjaman Saya, dan bingung memilih antara Dashboard dan Daftar Alat. Akibatnya menu mahasiswa disederhanakan menjadi dua pilihan yang selalu terlihat (Daftar Alat dan Peminjaman Saya), dan status langsung tampil setelah pengajuan dikirim. Temuan ini penting karena memastikan pengguna dapat menemukan kembali status pengajuan, yang merupakan inti skenario uji.

**5. Apa kontribusi Anda dan bukti commit atau artefaknya?**
[Isi sendiri dengan jujur: bagian yang Anda kerjakan, misalnya sitemap, user flow, wireframe, atau sesi walkthrough, beserta nama berkas dan pesan commit sebagai bukti.]
