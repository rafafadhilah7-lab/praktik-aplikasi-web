# Keputusan Desain: SIPINJAM-LAB (Pertemuan 3)

| Keputusan | Dasar persona / user story | Alternatif | Alasan dipilih | Perlu diuji lagi? |
|---|---|---|---|---|
| Scope dipersempit ke US-01, US-02, US-06 | Backlog Must; satu tujuan utama (mengajukan dan menyerahterimakan alat) | Memasukkan US-05 dan US-08 (denda, QRIS) | Denda dan QRIS butuh layar Pengembalian, Detail Denda, dan QRIS; tidak muat dalam 200 menit. Tidak ada klaim AC tanpa layar | Tidak |
| Prototipe dan wireframe berpasangan desktop + mobile, mobile-first untuk Dimas | Persona Dimas memakai ponsel di sekitar lab; US-02 | Desktop dulu | Pengajuan dilakukan dekat lab; satu kolom dan target sentuh besar lebih sesuai | Ya |
| Detail alat, konfirmasi, dan detail pengajuan dijadikan panel / langkah, bukan halaman terpisah | Prinsip arsitektur informasi modul; hierarki maksimal 3 tingkat | Halaman terpisah P03, P05, P10 | Mengurangi jumlah halaman dan jalan buntu; panel menjadi bottom sheet di mobile | Ya |
| Navigasi dua menu untuk mahasiswa: Daftar Alat dan Peminjaman Saya (tab bar bawah di mobile) | Temuan walkthrough #2 dan #5 | Dashboard + tiga menu | Temuan menunjukkan pengguna bingung memilih menu dan mencari status | Ya |
| NIM terverifikasi otomatis dan tidak bisa diubah di form | US-01, AC-01 | Mahasiswa mengetik NIM manual di form | Mengurangi salah ketik; nama tampil sebagai konfirmasi identitas | Ya |
| Tanggal kembali dihitung otomatis dari lama peminjaman | US-02, AC-05 | Mahasiswa mengetik tanggal kembali | Mencegah tanggal kembali lebih awal dari tanggal pinjam, sehingga kasus AC-05 dicegah lewat desain; kolom wajib tetap divalidasi | Ya |
| Validasi ditampilkan sebagai banner + pesan di bawah kolom, dengan tanda wajib (*) | US-02; temuan walkthrough #3 | Dialog peringatan | Pesan tetap terlihat dan menunjuk kolom yang harus diperbaiki | Ya |
| Konfirmasi dua langkah sebelum pengajuan dikirim | US-02, AC-04 | Kirim langsung dari form | Mahasiswa dapat memeriksa jadwal dan jaminan sebelum status Menunggu Verifikasi dibuat | Ya |
| Jaminan KTM/KTP dipilih di form dengan penjelasan prosedur loket | US-06; temuan walkthrough #4 | Dipilih saat serah terima saja | Mahasiswa tahu sejak awal bahwa jaminan fisik wajib dibawa | Ya |
| Tombol Konfirmasi Serah Terima nonaktif sampai jaminan dicentang, disertai pesan | US-06, AC-08 | Tombol aktif lalu menampilkan galat | Mencegah status Aktif tanpa jaminan; laboran melihat penyebabnya langsung | Ya |
| Wireframe low-fidelity abu-abu dengan satu warna aksen | Aturan wireframe modul | Mockup visual berwarna (hasil Stitch sebelumnya) | Fokus pada struktur dan state; gaya visual ditentukan di pertemuan design system | Tidak |
