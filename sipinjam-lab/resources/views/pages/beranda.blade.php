@extends('layouts.landing')

@section('title', 'Beranda - SIPINJAM-LAB')

@section('content')
    {{-- HERO SECTION --}}
    <section class="landing-hero relative overflow-hidden p-6 sm:p-10">
        <div class="grid gap-8 lg:grid-cols-[1.25fr_0.75fr] lg:items-center">
            <div class="space-y-5">
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-300/25 bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-sky-100">
                    <span class="h-2 w-2 rounded-full bg-sky-300"></span>
                    SIPINJAM-LAB &bull; Sistem Peminjaman Alat Laboratorium
                </div>

                <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-[2.6rem]">
                    Pinjam Alat Laboratorium Cepat &amp; Mudah
                </h1>

                <p class="max-w-xl text-sm leading-relaxed text-sky-100/90 sm:text-base">
                    Telusuri ketersediaan inventaris alat praktikum dan riset secara transparan, lalu ajukan peminjaman secara daring untuk diverifikasi petugas laboran.
                </p>

                <div class="flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn shadow-md">
                            Buka Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn shadow-md">
                            Mulai Pinjam &rarr;
                        </a>
                    @endauth
                    <a href="{{ route('alat.hasil') }}"
                       class="inline-flex items-center gap-2 rounded-lg border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">
                        Lihat Katalog
                    </a>
                </div>
            </div>

            <div class="flex justify-center lg:justify-end">
                <img
                    src="{{ asset('images/landing/hero-lab.svg') }}"
                    alt="Ilustrasi peralatan laboratorium SIPINJAM-LAB"
                    class="w-full max-w-sm rounded-2xl shadow-lg"
                >
            </div>
        </div>

        {{-- Kolom Pencarian --}}
        <div class="mt-8">
            <x-search-form :daftar-kategori="$daftarKategori" />
        </div>
    </section>

    {{-- STATISTIK NYATA --}}
    <section aria-label="Statistik inventaris laboratorium">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-muted">Total Alat</div>
                    <div class="text-2xl font-extrabold text-heading">{{ $totalAlat }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-emerald-500/15 text-emerald-500">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-muted">Tersedia Hari Ini</div>
                    <div class="text-2xl font-extrabold text-emerald-500">{{ $totalTersediaHariIni }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-muted">Jumlah Kategori</div>
                    <div class="text-2xl font-extrabold text-heading">{{ $jumlahKategori }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-muted">Lokasi Lab</div>
                    <div class="text-2xl font-extrabold text-heading">{{ $jumlahLokasi }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- KATALOG ALAT POPULER --}}
    <section class="space-y-5">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <div class="mb-1 text-xs font-bold uppercase tracking-[0.15em] text-accent">
                    Katalog Laboratorium
                </div>
                <h2 class="text-xl font-bold tracking-tight text-heading sm:text-2xl">
                    Katalog Alat Populer
                </h2>
                <p class="mt-1 text-sm text-muted">
                    Alat laboratorium yang paling sering diajukan beserta ketersediaan unit hari ini.
                </p>
            </div>

            <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm self-start sm:self-auto">
                Lihat semua alat &rarr;
            </a>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($alatPopuler as $alat)
                <x-alat-card
                    :alat="$alat"
                    :sisa="$ketersediaanHariIni[$alat->id] ?? (int) $alat->stok"
                />
            @empty
                <div class="col-span-full surface-card border-dashed px-6 py-12 text-center">
                    <h3 class="font-semibold text-heading">Belum Ada Data Alat</h3>
                    <p class="mt-1 text-sm text-muted">Inventaris alat laboratorium belum tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- CARA PEMINJAMAN --}}
    <section id="prosedur" class="surface-card p-6 sm:p-8 space-y-6">
        <div>
            <div class="mb-1 text-xs font-bold uppercase tracking-[0.15em] text-accent">Alur Peminjaman</div>
            <h2 class="text-xl font-bold text-heading sm:text-2xl">Cara Peminjaman</h2>
            <p class="mt-1 text-sm text-muted">Ikuti 4 langkah berikut untuk meminjam alat laboratorium.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="surface-inset p-5 space-y-2.5">
                <span class="landing-step-number">1</span>
                <h3 class="font-bold text-heading">Cari Alat</h3>
                <p class="text-xs leading-relaxed text-muted">
                    Telusuri katalog alat berdasarkan nama, kode alat, atau kategori laboratorium yang dibutuhkan.
                </p>
            </div>

            <div class="surface-inset p-5 space-y-2.5">
                <span class="landing-step-number">2</span>
                <h3 class="font-bold text-heading">Daftar atau Masuk</h3>
                <p class="text-xs leading-relaxed text-muted">
                    Masuk menggunakan akun peminjam yang sudah terdaftar dengan NIM valid (8–20 digit angka).
                </p>
            </div>

            <div class="surface-inset p-5 space-y-2.5">
                <span class="landing-step-number">3</span>
                <h3 class="font-bold text-heading">Ajukan Peminjaman</h3>
                <p class="text-xs leading-relaxed text-muted">
                    Tentukan tanggal, durasi, jumlah unit, dan keperluan, lalu tunggu persetujuan petugas laboran.
                </p>
            </div>

            <div class="surface-inset p-5 space-y-2.5">
                <span class="landing-step-number">4</span>
                <h3 class="font-bold text-heading">Ambil &amp; Kembalikan</h3>
                <p class="text-xs leading-relaxed text-muted">
                    Ambil alat di laboratorium sesuai jadwal yang disetujui dan kembalikan tepat waktu.
                </p>
            </div>
        </div>
    </section>

    {{-- INFORMASI LAYANAN --}}
    <section id="tentang" class="surface-card p-6 sm:p-8 space-y-4">
        <div>
            <div class="mb-1 text-xs font-bold uppercase tracking-[0.15em] text-accent">Ketentuan Sistem</div>
            <h2 class="text-xl font-bold text-heading sm:text-2xl">Informasi Layanan</h2>
        </div>

        <div class="grid gap-4 md:grid-cols-2 text-sm text-muted">
            <ul class="space-y-2.5 list-disc list-inside">
                <li>Pengajuan peminjaman wajib dilakukan oleh pengguna yang telah masuk sebagai <strong class="text-heading">Peminjam</strong> dengan NIM valid.</li>
                <li>Tanggal pinjam tidak boleh berada di masa lampau.</li>
                <li>Durasi peminjaman berlaku mulai <strong class="text-heading">1 sampai 30 hari</strong>.</li>
            </ul>
            <ul class="space-y-2.5 list-disc list-inside">
                <li>Jumlah unit yang diajukan tidak boleh melebihi sisa stok tersedia pada rentang tanggal yang dipilih.</li>
                <li>Setiap pengajuan memerlukan verifikasi dan persetujuan petugas <strong class="text-heading">Laboran</strong>.</li>
                <li>Status transaksi meliputi: <span class="font-semibold text-heading">menunggu, disetujui, dipinjam, dikembalikan, ditolak</span>.</li>
            </ul>
        </div>
    </section>
@endsection
