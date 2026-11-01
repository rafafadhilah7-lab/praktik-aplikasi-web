@extends('layouts.app')

@section('title', 'Detail Alat - ' . $alat->nama)

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6 space-y-6">
  <!-- Tombol kembali -->
  <a href="{{ route('alat.hasil') }}"
     class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-pink-600 hover:text-pink-700">
    &larr; Kembali ke daftar alat
  </a>

  <!-- Kartu detail alat -->
  <div class="overflow-hidden rounded-3xl border border-pink-100 bg-white shadow-xl shadow-pink-100/30">
    <!-- Informasi Utama Header -->
    <div class="bg-gradient-to-r from-pink-50 via-[#fdf7f9] to-rose-50 p-6 sm:p-8 border-b border-pink-100">
      <div class="flex items-center gap-2 mb-2">
        <span class="code-label">{{ $alat->kode }}</span>
        <x-badge :status="$alat->stok > 0 ? 'tersedia' : 'habis'" />
      </div>

      <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900">
        {{ $alat->nama }}
      </h1>

      <p class="mt-2 text-sm text-slate-600">
        Bidang Kategori: <strong class="text-pink-700">{{ $alat->kategori }}</strong>
      </p>
    </div>

    <!-- Spesifikasi Lokasi dan Stok -->
    <div class="grid gap-6 p-6 sm:p-8 sm:grid-cols-2">
      <div class="p-5 rounded-2xl bg-[#fdfafb] border border-pink-100/80">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Lokasi Penyimpanan</p>
        <p class="mt-1.5 text-base font-bold text-slate-900 flex items-center gap-2">
          <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          {{ $alat->lokasi }}
        </p>
      </div>

      <div class="p-5 rounded-2xl bg-[#fdfafb] border border-pink-100/80">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Ketersediaan Unit Fisik</p>
        <p class="mt-1.5 text-base font-extrabold flex items-center gap-2 {{ $alat->stok > 0 ? 'text-emerald-700' : 'text-rose-600' }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          {{ $alat->stok }} Unit Tersedia
        </p>
      </div>
    </div>

    <!-- Deskripsi Alat -->
    @if($alat->deskripsi)
      <div class="px-6 sm:px-8 pb-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Deskripsi & Fungsi Alat</h2>
        <p class="text-sm text-slate-600 leading-relaxed bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
          {{ $alat->deskripsi }}
        </p>
      </div>
    @endif

    <!-- Tombol peminjaman -->
    <div class="border-t border-slate-100 p-6 sm:p-8 bg-slate-50/40 flex items-center justify-between">
      <div class="text-xs text-slate-500">
        Peminjaman akan diverifikasi secara langsung oleh Petugas Laboran.
      </div>

      <div>
        @if ($alat->stok > 0)
          <a href="{{ route('alat.pinjam', $alat->id) }}" class="btn">
            Ajukan Peminjaman Alat Ini &rarr;
          </a>
        @else
          <button type="button" disabled class="btn">
            Alat Tidak Tersedia (Stok Habis)
          </button>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection