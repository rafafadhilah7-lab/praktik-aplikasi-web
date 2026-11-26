@extends('layouts.app')

@section('title', 'Edit Alat - ' . $alat->nama)

@section('content')
<div class="mx-auto max-w-2xl py-2">
  <!-- Navigasi Kembali -->
  <a href="{{ route('admin.alat.index') }}"
     class="mb-6 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider link-accent">
    &larr; Kembali ke daftar alat
  </a>

  <div class="surface-card p-6 sm:p-8">
    <div class="mb-6">
      <div class="flex items-center gap-2 mb-2">
        <span class="code-label">{{ $alat->kode }}</span>
        <x-badge :status="$alat->stok > 0 ? 'tersedia' : 'habis'" />
      </div>
      <h1 class="text-2xl font-bold text-heading">Edit Data Alat</h1>
      <p class="text-xs text-muted mt-1">Perbarui spesifikasi teknis, stok, atau lokasi penyimpanan alat</p>
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

    <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Kode Alat -->
        <div>
          <label for="kode" class="field-label block mb-1.5">
            Kode Alat <span class="field-required">*</span>
          </label>
          <input type="text"
                 id="kode"
                 name="kode"
                 value="{{ old('kode', $alat->kode) }}"
                 required
                 maxlength="20"
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
                 value="{{ old('kategori', $alat->kategori) }}"
                 required
                 maxlength="50"
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
               value="{{ old('nama', $alat->nama) }}"
               required
               maxlength="100"
               class="field-input">
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Stok -->
        <div>
          <label for="stok" class="field-label block mb-1.5">
            Jumlah Stok Fisik <span class="field-required">*</span>
          </label>
          <input type="number"
                 id="stok"
                 name="stok"
                 value="{{ old('stok', $alat->stok) }}"
                 required
                 min="0"
                 class="field-input">
          <p class="mt-1 text-[11px] text-muted">Pastikan stok tidak lebih kecil dari unit yang sedang dipinjam.</p>
        </div>

        <!-- Lokasi -->
        <div>
          <label for="lokasi" class="field-label block mb-1.5">
            Lokasi Lab / Rak Penyimpanan <span class="field-required">*</span>
          </label>
          <input type="text"
                 id="lokasi"
                 name="lokasi"
                 value="{{ old('lokasi', $alat->lokasi) }}"
                 required
                 maxlength="150"
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
                  class="field-input min-h-[96px]">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
      </div>

      <!-- Buttons -->
      <div class="flex flex-wrap items-center gap-3 pt-3 border-t divider-subtle">
        <button type="submit" class="btn">
          Perbarui Data Alat
        </button>
        <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
