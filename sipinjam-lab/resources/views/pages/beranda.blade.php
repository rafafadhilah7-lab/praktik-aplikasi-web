@extends('layouts.app')

@section('title', 'Katalog Peminjaman Alat Laboratorium - SIPINJAM-LAB')

@section('content')
<div class="space-y-8">
  <!-- Hero Section -->
  <div class="rounded-3xl bg-gradient-to-r from-pink-600 via-rose-500 to-pink-400 p-8 sm:p-10 text-white shadow-xl shadow-pink-200/50 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div class="max-w-2xl">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-semibold mb-3">
        <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
        Sistem Informasi Peminjaman Alat Laboratorium
      </div>
      <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white mb-3">
        Temukan Alat Praktikum & Eksperimen Anda
      </h1>
      <p class="text-pink-100 text-sm leading-relaxed">
        Layanan peminjaman alat laboratorium terpadu, cepat, dan transparan. Cek ketersediaan stok fisik dan ajukan peminjaman secara online dengan mudah.
      </p>
    </div>

    @guest
      <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-center flex-shrink-0">
        <div class="text-xs font-semibold text-pink-100 mb-2">Ingin mengajukan alat?</div>
        <a href="{{ route('register') }}" class="btn btn-sm bg-white text-pink-700 hover:bg-pink-50 font-bold w-full">
          Daftar Sebagai Mahasiswa
        </a>
      </div>
    @endguest
  </div>

  <!-- Search & Filter Component -->
  <div class="card p-6 bg-white border-pink-100 shadow-sm">
    <div class="mb-4">
      <h2 class="text-lg font-bold text-slate-900">Pencarian Alat Laboratorium</h2>
      <p class="text-xs text-slate-500">Filter berdasarkan kata kunci nama, kode alat, atau bidang kategori laboratorium</p>
    </div>
    <x-search-form :daftar-kategori="$daftarKategori" />
  </div>

  <!-- Alat Tersedia Section -->
  <section class="space-y-4">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-bold text-slate-900">Alat Laboratorium Tersedia</h2>
        <p class="text-xs text-slate-500">Pilihan unit alat yang siap dipinjam untuk keperluan praktikum</p>
      </div>
      <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm">
        Lihat Semua Alat &rarr;
      </a>
    </div>

    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
      @foreach ($tersedia as $alat)
        <x-alat-card :alat="$alat" />
      @endforeach
    </div>
  </section>
</div>
@endsection
