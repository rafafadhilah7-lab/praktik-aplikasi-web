@extends('layouts.app')

@section('title', 'Detail Alat - ' . $alat->nama)

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
  <!-- Tombol kembali -->
  <a href="{{ route('alat.hasil') }}"
     class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider link-accent">
    &larr; Kembali ke daftar alat
  </a>

  <!-- Kartu detail alat -->
  <div class="surface-card overflow-hidden">
    <!-- Informasi Utama Header -->
    <div class="card-header-soft p-6 sm:p-8">
      <div class="grid gap-6 md:grid-cols-[1fr_240px] md:items-center">
        <div>
          <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="code-label">{{ $alat->kode }}</span>
            <x-badge :status="$alat->stok > 0 ? 'tersedia' : 'habis'" />
          </div>

          <h1 class="text-2xl sm:text-4xl font-extrabold text-heading">
            {{ $alat->nama }}
          </h1>

          <p class="mt-2 text-sm text-muted">
            Bidang Kategori: <strong class="text-accent">{{ $alat->kategori }}</strong>
          </p>
        </div>

        <x-alat-illustration :nama="$alat->nama" :kategori="$alat->kategori" />
      </div>
    </div>

    <!-- Spesifikasi Lokasi dan Stok -->
    <div class="grid gap-5 p-6 sm:p-8 sm:grid-cols-2">
      <div class="surface-inset p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-muted">Lokasi Penyimpanan</p>
        <p class="mt-1.5 text-base font-bold text-heading flex items-center gap-2">
          <svg class="w-5 h-5 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          {{ $alat->lokasi }}
        </p>
      </div>

      <div class="surface-inset p-5">
        <p class="text-xs font-bold uppercase tracking-wider text-muted">Ketersediaan Unit Fisik</p>
        <p class="mt-1.5 text-base font-extrabold flex items-center gap-2 {{ $alat->stok > 0 ? 'text-emerald-600' : 'text-red-500' }}">
          <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
          </svg>
          {{ $alat->stok }} Unit Tersedia
        </p>
      </div>
    </div>

    <!-- Deskripsi Alat -->
    @if($alat->deskripsi)
      <div class="px-6 sm:px-8 pb-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-heading mb-2">Deskripsi &amp; Fungsi Alat</h2>
        <p class="text-sm text-muted leading-relaxed surface-inset p-4">
          {{ $alat->deskripsi }}
        </p>
      </div>
    @endif

    <!-- Tombol peminjaman -->
    <div class="border-t divider-subtle p-6 sm:p-8 surface-inset rounded-none border-x-0 border-b-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="text-xs text-muted">
        Peminjaman akan diverifikasi secara langsung oleh Petugas Laboran.
      </div>

      <div>
        @if ($alat->stok > 0)
          <a href="{{ route('alat.pinjam', $alat->id) }}" class="btn w-full sm:w-auto">
            Ajukan Peminjaman Alat Ini &rarr;
          </a>
        @else
          <button type="button" disabled class="btn w-full sm:w-auto">
            Alat Tidak Tersedia (Stok Habis)
          </button>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
