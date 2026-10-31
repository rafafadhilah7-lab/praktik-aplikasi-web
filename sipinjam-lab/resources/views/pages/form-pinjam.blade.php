@extends('layouts.app')

@section('title', 'Form Pengajuan Peminjaman - ' . $alat->nama)

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
  <!-- Navigasi Kembali -->
  <a href="{{ route('alat.detail', $alat->id) }}" class="btn btn-secondary btn-sm">
    &larr; Kembali ke Detail Alat
  </a>

  <!-- Kartu Formulir Peminjaman -->
  <div class="card overflow-hidden">
    <div class="card-header bg-[#f8fafc]">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="code-badge">{{ $alat->kode }}</span>
          <span class="badge badge-success text-[10px]">Tersedia: {{ $alat->stok }} Unit</span>
        </div>
        <h1 class="text-lg font-bold text-[#202938]">Formulir Permohonan Peminjaman Alat</h1>
        <p class="text-xs text-[#64748b]">Alat: <strong>{{ $alat->nama }}</strong> &bull; Lokasi: {{ $alat->lokasi }}</p>
      </div>
    </div>

    @if ($errors->any())
      <div class="mx-6 mt-5 rounded-md border border-red-200 bg-red-50 p-3.5 text-xs text-red-700">
        <div class="font-semibold mb-1">Periksa kembali data pengajuan:</div>
        <ul class="list-inside list-disc space-y-0.5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('alat.ajukan', $alat->id) }}" method="POST" class="p-6 space-y-4">
      @csrf

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="form-group">
          <label for="nama_peminjam" class="form-label">
            Nama Lengkap Mahasiswa <span class="text-red-500">*</span>
          </label>
          <input type="text"
                 id="nama_peminjam"
                 name="nama_peminjam"
                 value="{{ old('nama_peminjam', Auth::user()->name ?? '') }}"
                 maxlength="100"
                 required
                 placeholder="Nama lengkap peminjam"
                 class="form-input">
        </div>

        <div class="form-group">
          <label for="nim" class="form-label">
            NIM Mahasiswa <span class="text-red-500">*</span>
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
                 class="form-input font-mono">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="form-group">
          <label for="tanggal_pinjam" class="form-label">
            Tanggal Mulai Pinjam <span class="text-red-500">*</span>
          </label>
          <input type="date"
                 id="tanggal_pinjam"
                 name="tanggal_pinjam"
                 value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                 min="{{ date('Y-m-d') }}"
                 required
                 class="form-input">
        </div>

        <div class="form-group">
          <label for="durasi" class="form-label">
            Durasi (Hari) <span class="text-red-500">*</span>
          </label>
          <input type="number"
                 id="durasi"
                 name="durasi"
                 value="{{ old('durasi', 1) }}"
                 min="1"
                 max="30"
                 required
                 class="form-input">
          <p class="text-[11px] text-slate-400 mt-1">Maksimal 30 hari.</p>
        </div>

        <div class="form-group">
          <label for="jumlah" class="form-label">
            Jumlah Unit <span class="text-red-500">*</span>
          </label>
          <input type="number"
                 id="jumlah"
                 name="jumlah"
                 value="{{ old('jumlah', 1) }}"
                 min="1"
                 max="{{ $alat->stok }}"
                 required
                 class="form-input font-bold text-blue-700">
          <p class="text-[11px] text-slate-400 mt-1">Stok saat ini: {{ $alat->stok }} unit.</p>
        </div>
      </div>

      <div class="form-group">
        <label for="keperluan" class="form-label">
          Keperluan Praktikum / Eksperimen <span class="text-red-500">*</span>
        </label>
        <textarea id="keperluan"
                  name="keperluan"
                  rows="3"
                  maxlength="500"
                  required
                  placeholder="Sebutkan mata kuliah, modul praktikum, atau keperluan eksperimen terkait..."
                  class="form-input h-auto py-2">{{ old('keperluan') }}</textarea>
      </div>

      <div class="pt-4 border-t border-[#e5e7eb] flex items-center justify-end gap-2">
        <a href="{{ route('alat.detail', $alat->id) }}" class="btn btn-secondary">
          Batal
        </a>
        <button type="submit" class="btn btn-primary">
          Kirim Pengajuan Peminjaman
        </button>
      </div>
    </form>
  </div>
</div>
@endsection