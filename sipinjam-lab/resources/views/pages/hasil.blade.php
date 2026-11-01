@extends('layouts.app')

@section('title', 'Hasil Pencarian Alat - SIPINJAM-LAB')

@section('content')
<div class="space-y-6">
  <!-- Breadcrumb -->
  <nav class="text-xs text-slate-500 flex items-center gap-2">
    <a href="{{ route('alat.beranda') }}" class="text-pink-600 hover:underline">Beranda</a>
    <span>&rsaquo;</span>
    <span class="text-slate-800 font-semibold">Hasil Pencarian</span>
  </nav>

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
    <div>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Katalog Alat Laboratorium</h1>
      <p class="text-xs text-slate-500 mt-1">
        Menampilkan {{ $hasil->total() }} alat laboratorium yang terdaftar di database
        @if ($q !== '') untuk kata kunci <strong class="text-pink-700">"{{ $q }}"</strong> @endif
        @if ($kategori !== '') dalam kategori <strong class="text-pink-700">{{ $kategori }}</strong> @endif
      </p>
    </div>
  </div>

  <!-- Search Form -->
  <div class="card p-6 bg-white border-pink-100 shadow-sm">
    <x-search-form
      :q="$q"
      :kategori="$kategori"
      :tanggal="$tanggal"
      :daftar-kategori="$daftarKategori"
    />
  </div>

  <!-- Grid Hasil -->
  @if ($hasil->count() > 0)
    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
      @foreach ($hasil as $alat)
        <x-alat-card :alat="$alat" />
      @endforeach
    </div>

    @if ($hasil->hasPages())
      <div class="mt-6">
        {{ $hasil->links() }}
      </div>
    @endif
  @else
    <div class="card p-8 bg-white text-center border-dashed border-2 border-pink-200">
      <div class="w-12 h-12 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center mx-auto mb-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <h2 class="text-lg font-bold text-slate-900 mb-1">Alat Tidak Ditemukan</h2>
      <p class="text-xs text-slate-500 max-w-md mx-auto mb-4">
        Tidak ada alat yang cocok dengan kata kunci atau filter yang Anda pilih. Coba gunakan kata kunci umum atau pilih "Semua Kategori".
      </p>
      <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm inline-flex">
        Tampilkan Seluruh Alat
      </a>
    </div>
  @endif
</div>
@endsection
