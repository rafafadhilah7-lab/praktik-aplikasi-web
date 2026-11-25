
@extends('layouts.app')

@section('title', 'Katalog Peminjaman Alat Laboratorium - SIPINJAM-LAB')

@section('content')
<div class="space-y-8">

    {{-- HERO SECTION --}}
    <section class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-[#102A43] via-[#173F66] to-[#147DAD] px-7 py-9 text-white shadow-lg sm:px-10 sm:py-11">

        {{-- Dekorasi latar --}}
        <div class="pointer-events-none absolute -right-16 -top-24 -z-10 h-72 w-72 rounded-full border-[35px] border-white/5"></div>
        <div class="pointer-events-none absolute -bottom-28 right-1/4 -z-10 h-64 w-64 rounded-full bg-sky-400/10 blur-2xl"></div>

        <div class="relative flex flex-col justify-between gap-8 md:flex-row md:items-center">

            <div class="max-w-2xl">

                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-sky-200/20 bg-white/10 px-3.5 py-2 text-xs font-semibold tracking-wide text-sky-100 backdrop-blur-sm">
                    <span class="h-2 w-2 rounded-full bg-sky-300"></span>
                    SISTEM INFORMASI PEMINJAMAN LABORATORIUM
                </div>

                <h1 class="mb-4 text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl lg:text-[2.75rem]">
                    Temukan Alat Praktikum
                    <span class="text-sky-300">& Eksperimen Anda</span>
                </h1>

                <p class="max-w-xl text-sm leading-7 text-slate-200 sm:text-base">
                    Layanan peminjaman alat laboratorium terpadu, cepat, dan transparan.
                    Cek ketersediaan stok fisik dan ajukan peminjaman secara online dengan mudah.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-4 text-xs font-medium text-sky-100">
                    <span class="inline-flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/10 text-sky-300">✓</span>
                        Informasi stok
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/10 text-sky-300">✓</span>
                        Pengajuan online
                    </span>
                </div>

            </div>

            @guest
                <div class="w-full max-w-xs shrink-0 rounded-2xl border border-white/15 bg-white/10 p-5 shadow-lg backdrop-blur-md">
                    <div class="mb-2 text-sm font-semibold text-white">
                        Butuh alat untuk praktikum?
                    </div>

                    <p class="mb-4 text-xs leading-5 text-slate-200">
                        Daftar akun untuk mulai mengajukan peminjaman alat laboratorium.
                    </p>

                    <a href="{{ route('register') }}"
                       class="inline-flex w-full items-center justify-center rounded-xl bg-white px-4 py-3 text-sm font-bold text-[#123453] shadow-sm transition hover:bg-sky-50">
                        Daftar Sekarang
                        <span class="ml-2">→</span>
                    </a>
                </div>
            @endguest

        </div>
    </section>


    {{-- SEARCH SECTION --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

        <div class="mb-5 flex items-start gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="21"
                     height="21"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </div>

            <div>
                <h2 class="text-lg font-bold text-slate-800">
                    Pencarian Alat Laboratorium
                </h2>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Temukan alat berdasarkan nama, kode alat, atau kategori laboratorium.
                </p>
            </div>
        </div>

        <x-search-form :daftar-kategori="$daftarKategori" />

    </section>


    {{-- AVAILABLE EQUIPMENT --}}
    <section class="space-y-5">

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">

            <div>
                <div class="mb-1 text-xs font-bold uppercase tracking-[0.15em] text-sky-700">
                    Katalog Laboratorium
                </div>

                <h2 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">
                    Alat Laboratorium Tersedia
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Pilihan unit alat yang siap dipinjam untuk keperluan praktikum.
                </p>
            </div>

            <a href="{{ route('alat.hasil') }}"
               class="inline-flex items-center justify-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-800 sm:self-auto">
                Lihat Semua Alat
                <span>→</span>
            </a>

        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($tersedia as $alat)
                <x-alat-card :alat="$alat" />
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="23"
                             height="23"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M10 2v7.31"></path>
                            <path d="M14 9.3V1.99"></path>
                            <path d="M8.5 2h7"></path>
                            <path d="M14 9.3a6 6 0 1 1-4 0"></path>
                            <path d="M5.52 16h12.96"></path>
                        </svg>
                    </div>

                    <h3 class="font-semibold text-slate-800">
                        Belum Ada Alat Tersedia
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Saat ini belum ada alat yang dapat ditampilkan.
                    </p>
                </div>
            @endforelse
        </div>

    </section>

</div>
@endsection
