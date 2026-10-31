@extends('layouts.app')

@section('title', 'Tambah Alat Baru - Admin')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-6">
  <!-- Navigasi Kembali -->
  <a href="{{ route('admin.alat.index') }}"
     class="mb-6 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-pink-600 hover:text-pink-700">
    &larr; Kembali ke daftar alat
  </a>

  <div class="overflow-hidden rounded-3xl border border-pink-100 bg-white p-6 sm:p-8 shadow-xl shadow-pink-100/30">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-900">Tambah Alat Laboratorium Baru</h1>
      <p class="text-xs text-slate-500 mt-1">Masukkan spesifikasi dan jumlah stok awal untuk inventaris alat</p>
    </div>

    @if ($errors->any())
      <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
        <p class="font-semibold mb-1">Periksa isian berikut:</p>
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.alat.store') }}" method="POST" class="space-y-4">
      @csrf

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Kode Alat -->
        <div>
          <label for="kode" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Kode Alat <span class="text-rose-500">*</span>
          </label>
          <input type="text"
                 id="kode"
                 name="kode"
                 value="{{ old('kode') }}"
                 required
                 maxlength="20"
                 placeholder="Contoh: ALT-060"
                 class="field-input font-mono">
        </div>

        <!-- Kategori -->
        <div>
          <label for="kategori" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Kategori Alat <span class="text-rose-500">*</span>
          </label>
          <input type="text"
                 id="kategori"
                 name="kategori"
                 list="kategoriList"
                 value="{{ old('kategori') }}"
                 required
                 maxlength="50"
                 placeholder="Pilih atau ketik kategori baru"
                 class="field-input">
          <datalist id="kategoriList">
            @foreach($daftarKategori as $kat)
              <option value="{{ $kat }}"></option>
            @endforeach
          </datalist>
        </div>
      </div>

      <!-- Nama Alat -->
      <div>
        <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
          Nama Alat Laboratorium <span class="text-rose-500">*</span>
        </label>
        <input type="text"
               id="nama"
               name="nama"
               value="{{ old('nama') }}"
               required
               maxlength="100"
               placeholder="Contoh: Spektrofotometer UV-Vis"
               class="field-input">
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Stok -->
        <div>
          <label for="stok" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Jumlah Stok Awal <span class="text-rose-500">*</span>
          </label>
          <input type="number"
                 id="stok"
                 name="stok"
                 value="{{ old('stok', 1) }}"
                 required
                 min="0"
                 class="field-input">
        </div>

        <!-- Lokasi -->
        <div>
          <label for="lokasi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Lokasi Lab / Rak Penyimpanan <span class="text-rose-500">*</span>
          </label>
          <input type="text"
                 id="lokasi"
                 name="lokasi"
                 value="{{ old('lokasi') }}"
                 required
                 maxlength="150"
                 placeholder="Contoh: Lab Kimia Dasar - Lemari B"
                 class="field-input">
        </div>
      </div>

      <!-- Deskripsi -->
      <div>
        <label for="deskripsi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
          Deskripsi & Petunjuk Singkat
        </label>
        <textarea id="deskripsi"
                  name="deskripsi"
                  rows="3"
                  maxlength="1000"
                  placeholder="Informasi mengenai fungsi, keakuratan, atau kelengkapan alat..."
                  class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 transition-all">{{ old('deskripsi') }}</textarea>
      </div>

      <!-- Buttons -->
      <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
        <button type="submit" class="btn">
          Simpan Data Alat
        </button>
        <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
