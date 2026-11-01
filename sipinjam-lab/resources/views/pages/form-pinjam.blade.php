@extends('layouts.app')

@section('title', 'Form Peminjaman Alat - ' . $alat->nama)

@section('content')
<div class="mx-auto max-w-3xl px-4 py-6 space-y-6">
  <!-- Tombol kembali -->
  <a href="{{ route('alat.detail', $alat->id) }}"
     class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-pink-600 hover:text-pink-700">
    &larr; Kembali ke detail alat
  </a>

  <!-- Kartu form peminjaman -->
  <div class="overflow-hidden rounded-3xl border border-pink-100 bg-white shadow-xl shadow-pink-100/30">
    <!-- Header Informasi Alat -->
    <div class="bg-gradient-to-r from-pink-50 via-[#fdf7f9] to-rose-50 p-6 sm:p-8 border-b border-pink-100">
      <div class="flex items-center gap-2 mb-2">
        <span class="code-label">{{ $alat->kode }}</span>
        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
          Stok Fisik: {{ $alat->stok }} Unit
        </span>
      </div>

      <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
        Form Pengajuan Peminjaman: {{ $alat->nama }}
      </h1>

      <p class="mt-2 text-xs text-slate-500">
        Lokasi Lab: <strong>{{ $alat->lokasi }}</strong> &bull; Kategori: <strong>{{ $alat->kategori }}</strong>
      </p>
    </div>

    <!-- Alert Validasi Kesalahan -->
    @if ($errors->any())
      <div class="mx-6 sm:mx-8 mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
        <p class="font-bold mb-1">Periksa kembali isian formulir:</p>
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Form Pengajuan -->
    <form action="{{ route('alat.ajukan', $alat->id) }}" method="POST" class="p-6 sm:p-8 space-y-5">
      @csrf

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Nama Peminjam -->
        <div>
          <label for="nama_peminjam" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Nama Peminjam <span class="text-rose-500">*</span>
          </label>
          <input type="text"
                 id="nama_peminjam"
                 name="nama_peminjam"
                 value="{{ old('nama_peminjam', Auth::user()->name ?? '') }}"
                 maxlength="100"
                 required
                 placeholder="Nama lengkap mahasiswa"
                 class="field-input">
        </div>

        <!-- NIM -->
        <div>
          <label for="nim" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            NIM Mahasiswa <span class="text-rose-500">*</span>
          </label>
          <input type="text"
                 id="nim"
                 name="nim"
                 value="{{ old('nim', Auth::user()->nim ?? '') }}"
                 inputmode="numeric"
                 pattern="[0-9]{8,20}"
                 maxlength="20"
                 required
                 placeholder="8 - 20 digit angka"
                 class="field-input font-mono">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Tanggal Pinjam -->
        <div>
          <label for="tanggal_pinjam" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Tanggal Pinjam <span class="text-rose-500">*</span>
          </label>
          <input type="date"
                 id="tanggal_pinjam"
                 name="tanggal_pinjam"
                 value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                 min="{{ date('Y-m-d') }}"
                 required
                 class="field-input">
        </div>

        <!-- Durasi Pinjam -->
        <div>
          <label for="durasi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Durasi (Hari) <span class="text-rose-500">*</span>
          </label>
          <input type="number"
                 id="durasi"
                 name="durasi"
                 value="{{ old('durasi', 1) }}"
                 min="1"
                 max="30"
                 required
                 class="field-input">
          <p class="mt-1 text-[11px] text-slate-400">Maks. 30 hari.</p>
        </div>

        <!-- Jumlah Unit -->
        <div>
          <label for="jumlah" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Jumlah Unit <span class="text-rose-500">*</span>
          </label>
          <input type="number"
                 id="jumlah"
                 name="jumlah"
                 value="{{ old('jumlah', 1) }}"
                 min="1"
                 max="{{ $alat->stok }}"
                 required
                 class="field-input font-bold text-pink-700">
          <p class="mt-1 text-[11px] text-slate-400">Tersedia: {{ $alat->stok }} unit.</p>
        </div>
      </div>

      <!-- Keperluan Peminjaman -->
      <div>
        <label for="keperluan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
          Keperluan Praktikum / Riset <span class="text-rose-500">*</span>
        </label>
        <textarea id="keperluan"
                  name="keperluan"
                  rows="3"
                  maxlength="500"
                  required
                  placeholder="Jelaskan mata kuliah praktikum, modul eksperimen, atau riset yang dilakukan..."
                  class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 transition-all">{{ old('keperluan') }}</textarea>
      </div>

      <!-- Tombol Aksi -->
      <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
        <button type="submit" class="btn">
          Kirim Pengajuan Peminjaman
        </button>
        <a href="{{ route('alat.detail', $alat->id) }}" class="btn btn-secondary">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection