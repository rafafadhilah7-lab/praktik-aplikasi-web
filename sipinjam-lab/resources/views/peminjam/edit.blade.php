@extends('layouts.app')

@section('title', 'Ubah Pengajuan #' . $peminjaman->id)

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
  <a href="{{ route('peminjaman.detail', $peminjaman->id) }}"
     class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider link-accent">
    &larr; Kembali ke detail pengajuan
  </a>

  <div class="surface-card overflow-hidden">
    <div class="card-header-soft p-6 sm:p-8">
      <div class="flex flex-wrap items-center gap-2 mb-2">
        <span class="code-label">Pengajuan #{{ $peminjaman->id }}</span>
        <x-badge :status="$peminjaman->status" />
      </div>

      <h1 class="text-2xl sm:text-3xl font-extrabold text-heading">
        Ubah Pengajuan: {{ $alat->nama }}
      </h1>

      <p class="mt-2 text-xs text-muted">
        Kode: <strong class="text-heading">{{ $alat->kode }}</strong> &bull; Stok Lab: <strong class="text-heading">{{ $alat->stok }} Unit</strong>
      </p>
    </div>

    @if ($errors->any())
      <div class="mx-6 sm:mx-8 mt-6 alert alert-error mb-0 text-sm">
        <p class="font-bold mb-1">Periksa kembali isian formulir:</p>
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('peminjam.riwayat.update', $peminjaman->id) }}" method="POST" class="p-6 sm:p-8 space-y-5">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="nama_peminjam" class="field-label block mb-1.5">Nama Peminjam</label>
          <input type="text"
                 id="nama_peminjam"
                 value="{{ $user->name }}"
                 readonly
                 tabindex="-1"
                 class="field-input">
        </div>

        <div>
          <label for="nim" class="field-label block mb-1.5">NIM Mahasiswa</label>
          <input type="text"
                 id="nim"
                 value="{{ $user->nim }}"
                 readonly
                 tabindex="-1"
                 class="field-input font-mono">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label for="tanggal_pinjam" class="field-label block mb-1.5">
            Tanggal Pinjam <span class="field-required">*</span>
          </label>
          <input type="date"
                 id="tanggal_pinjam"
                 name="tanggal_pinjam"
                 value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d')) }}"
                 min="{{ date('Y-m-d') }}"
                 required
                 class="field-input">
        </div>

        <div>
          <label for="durasi" class="field-label block mb-1.5">
            Durasi (Hari) <span class="field-required">*</span>
          </label>
          <input type="number"
                 id="durasi"
                 name="durasi"
                 value="{{ old('durasi', $peminjaman->durasi) }}"
                 min="1"
                 max="30"
                 required
                 class="field-input">
          <p class="mt-1 text-[11px] text-muted">Maks. 30 hari.</p>
        </div>

        <div>
          <label for="jumlah" class="field-label block mb-1.5">
            Jumlah Unit <span class="field-required">*</span>
          </label>
          <input type="number"
                 id="jumlah"
                 name="jumlah"
                 value="{{ old('jumlah', $peminjaman->jumlah) }}"
                 min="1"
                 max="{{ $alat->stok }}"
                 required
                 class="field-input font-bold text-accent">
          <p class="mt-1 text-[11px] text-muted">Stok lab: {{ $alat->stok }} unit.</p>
        </div>
      </div>

      <div>
        <label for="keperluan" class="field-label block mb-1.5">
          Keperluan Praktikum / Riset <span class="field-required">*</span>
        </label>
        <textarea id="keperluan"
                  name="keperluan"
                  rows="3"
                  maxlength="500"
                  required
                  class="field-input min-h-[96px]">{{ old('keperluan', $peminjaman->keperluan) }}</textarea>
      </div>

      <div class="flex flex-wrap items-center gap-3 pt-4 border-t divider-subtle">
        <button type="submit" class="btn">
          Simpan Perubahan
        </button>
        <a href="{{ route('peminjaman.detail', $peminjaman->id) }}" class="btn btn-secondary">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
