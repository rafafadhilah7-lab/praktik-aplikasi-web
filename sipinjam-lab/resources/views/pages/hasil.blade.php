@extends('layouts.app')

@section('title', 'Hasil Pencarian Alat Laboratorium')

@section('content')
<div class="space-y-6">
  <!-- Breadcrumb -->
  <nav class="breadcrumb-nav">
    <a href="{{ route('alat.beranda') }}">Katalog Alat</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Hasil Pencarian</span>
  </nav>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-[#e5e7eb]">
    <div>
      <h1 class="text-xl font-bold text-[#202938]">Hasil Pencarian Inventaris Alat</h1>
      <p class="text-xs text-[#64748b] mt-0.5">
        Ditemukan <strong>{{ $hasil->total() }}</strong> unit alat
        @if ($q !== '') untuk pencarian <span class="font-semibold text-blue-600">"{{ $q }}"</span> @endif
        @if ($kategori !== '') pada kategori <span class="font-semibold text-blue-600">{{ $kategori }}</span> @endif
      </p>
    </div>
    <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm">
      Reset Filter
    </a>
  </div>

  <!-- Search Card -->
  <div class="card p-5">
    <x-search-form
      :q="$q"
      :kategori="$kategori"
      :tanggal="$tanggal"
      :daftar-kategori="$daftarKategori"
    />
  </div>

  <!-- Grid Hasil -->
  @if ($hasil->count() > 0)
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      @foreach ($hasil as $alat)
        <x-alat-card :alat="$alat" />
      @endforeach
    </div>

    @if ($hasil->hasPages())
      <div class="mt-4">
        {{ $hasil->links() }}
      </div>
    @endif
  @else
    <div class="card p-8 text-center bg-white border border-[#e5e7eb]">
      <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center mx-auto mb-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
        </svg>
      </div>
      <h2 class="text-sm font-bold text-[#202938] mb-1">Peralatan Tidak Ditemukan</h2>
      <p class="text-xs text-[#64748b] max-w-sm mx-auto mb-4">
        Tidak ada data alat yang sesuai dengan parameter pencarian Anda. Silakan coba kata kunci lain atau pilih Semua Kategori.
      </p>
      <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm">
        Tampilkan Seluruh Alat
      </a>
    </div>
  @endif
</div>
@endsection
