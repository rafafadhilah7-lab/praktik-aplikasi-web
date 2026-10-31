@extends('layouts.app')

@section('title', 'Katalog Peminjaman Alat Laboratorium')

@section('content')
<div class="space-y-6">
  <!-- Portal Header Banner (Navy Enterprise) -->
  <div class="rounded-lg bg-[#172235] text-white p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 border border-[#233249]">
    <div class="max-w-2xl">
      <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded bg-blue-900/60 border border-blue-500/30 text-blue-300 text-[11px] font-semibold mb-2 uppercase tracking-wider">
        Portal Informasi Laboratorium Terpadu
      </div>
      <h1 class="text-xl sm:text-2xl font-bold text-white mb-2">
        Katalog Peralatan Laboratorium & Praktikum
      </h1>
      <p class="text-xs text-slate-300 leading-relaxed">
        Pemeriksaan ketersediaan unit fisik dan pengajuan peminjaman alat eksperimen secara resmi untuk mahasiswa dan dosen.
      </p>
    </div>

    @guest
      <div class="flex items-center gap-2 self-start md:self-auto flex-shrink-0">
        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
          Masuk
        </a>
        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
          Registrasi Mahasiswa
        </a>
      </div>
    @endguest
  </div>

  <!-- Search Filter Card -->
  <div class="card p-5">
    <div class="mb-3">
      <h2 class="text-sm font-semibold text-[#202938]">Pencarian Inventaris Alat</h2>
      <p class="text-xs text-[#64748b]">Cari berdasarkan nama alat, kode identifikasi, atau bidang laboratorium</p>
    </div>
    <x-search-form :daftar-kategori="$daftarKategori" />
  </div>

  <!-- Alat Tersedia Section -->
  <section class="space-y-4">
    <div class="flex items-center justify-between pb-1 border-b border-[#e5e7eb]">
      <div>
        <h2 class="text-base font-bold text-[#202938]">Alat Tersedia Siap Dipinjam</h2>
        <p class="text-xs text-[#64748b]">Unit peralatan yang memiliki ketersediaan fisik di laboratorium saat ini</p>
      </div>
      <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm">
        Lihat Seluruh Katalog &rarr;
      </a>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      @foreach ($tersedia as $alat)
        <x-alat-card :alat="$alat" />
      @endforeach
    </div>
  </section>
</div>
@endsection
