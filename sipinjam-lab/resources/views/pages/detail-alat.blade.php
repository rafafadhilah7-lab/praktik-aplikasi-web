@extends('layouts.app')

@section('title', 'Spesifikasi Alat - ' . $alat->nama)

@section('content')
<div class="mx-auto max-w-4xl space-y-5">
  <!-- Navigasi Kembali -->
  <div class="flex items-center justify-between">
    <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm">
      &larr; Kembali ke Katalog
    </a>
    <span class="text-xs text-[#64748b]">Kode Inventaris: <strong class="font-mono text-[#202938]">{{ $alat->kode }}</strong></span>
  </div>

  <!-- Kartu Detail Spesifikasi Alat -->
  <div class="card overflow-hidden">
    <!-- Header Informasi Alat -->
    <div class="card-header bg-[#f8fafc]">
      <div>
        <div class="flex items-center gap-2 mb-1.5">
          <span class="code-badge">{{ $alat->kode }}</span>
          <x-badge :status="$alat->stok > 0 ? 'tersedia' : 'habis'" />
        </div>
        <h1 class="text-xl font-bold text-[#202938]">{{ $alat->nama }}</h1>
        <p class="text-xs text-[#64748b] mt-0.5">Bidang Kategori: <strong class="text-slate-800">{{ $alat->kategori }}</strong></p>
      </div>
    </div>

    <!-- Spesifikasi Grid -->
    <div class="p-6 space-y-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-4 rounded-md border border-[#e5e7eb] bg-[#f8fafc]">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Lokasi Penyimpanan Lab</span>
          <div class="font-bold text-sm text-[#202938] flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
            </svg>
            <span>{{ $alat->lokasi }}</span>
          </div>
        </div>

        <div class="p-4 rounded-md border border-[#e5e7eb] bg-[#f8fafc]">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Ketersediaan Unit Fisik</span>
          <div class="font-bold text-sm flex items-center gap-2 {{ $alat->stok > 0 ? 'text-emerald-700' : 'text-red-600' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
            </svg>
            <span>{{ $alat->stok }} Unit Tersedia</span>
          </div>
        </div>
      </div>

      <!-- Deskripsi Peralatan -->
      <div>
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Deskripsi & Fungsi Peralatan</h2>
        <div class="p-4 rounded-md border border-[#e5e7eb] bg-white text-xs text-[#202938] leading-relaxed">
          {{ $alat->deskripsi ?? 'Tidak ada catatan deskripsi teknis khusus untuk peralatan laboratorium ini.' }}
        </div>
      </div>
    </div>

    <!-- Action Footer -->
    <div class="card-footer flex items-center justify-between">
      <div class="text-xs text-[#64748b]">
        Pengajuan peminjaman memerlukan verifikasi dan persetujuan dari Petugas Laboran.
      </div>
      <div>
        @if ($alat->stok > 0)
          <a href="{{ route('alat.pinjam', $alat->id) }}" class="btn btn-primary">
            Ajukan Peminjaman &rarr;
          </a>
        @else
          <button type="button" disabled class="btn btn-secondary">
            Alat Sedang Tidak Tersedia
          </button>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection