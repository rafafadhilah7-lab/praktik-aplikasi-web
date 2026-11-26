@extends('layouts.app')

@section('title', 'Tambah Alat Baru - Admin')

@section('content')
<div class="mx-auto max-w-2xl py-2">
  <!-- Navigasi Kembali -->
  <a href="{{ route('admin.alat.index') }}"
     class="mb-6 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider link-accent">
    &larr; Kembali ke daftar alat
  </a>

  <div class="surface-card p-6 sm:p-8">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-heading">Tambah Alat Laboratorium Baru</h1>
      <p class="text-xs text-muted mt-1">Masukkan spesifikasi dan jumlah stok awal untuk inventaris alat</p>
    </div>

    @if ($errors->any())
      <div class="mb-6 alert alert-error text-sm">
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
          <label for="kode" class="field-label block mb-1.5">
            Kode Alat <span class="field-required">*</span>
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
          <label for="kategori" class="field-label block mb-1.5">
            Kategori Alat <span class="field-required">*</span>
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
        <label for="nama" class="field-label block mb-1.5">
          Nama Alat Laboratorium <span class="field-required">*</span>
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
          <label for="stok" class="field-label block mb-1.5">
            Jumlah Stok Awal <span class="field-required">*</span>
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
          <label for="lokasi" class="field-label block mb-1.5">
            Lokasi Lab / Rak Penyimpanan <span class="field-required">*</span>
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
        <label for="deskripsi" class="field-label block mb-1.5">
          Deskripsi &amp; Petunjuk Singkat
        </label>
        <textarea id="deskripsi"
                  name="deskripsi"
                  rows="3"
                  maxlength="1000"
                  placeholder="Informasi mengenai fungsi, keakuratan, atau kelengkapan alat..."
                  class="field-input min-h-[96px]">{{ old('deskripsi') }}</textarea>
      </div>

      <!-- Buttons -->
      <div class="flex flex-wrap items-center gap-3 pt-3 border-t divider-subtle">
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
