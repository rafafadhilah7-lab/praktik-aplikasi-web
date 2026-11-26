@extends('layouts.app')

@section('title', 'Konfirmasi Pengajuan Peminjaman')

@section('content')
<div class="mx-auto max-w-3xl py-2">
  <div class="surface-card overflow-hidden">
    <!-- Header Konfirmasi Sukses -->
    <div class="card-header-soft p-8 text-center">
      <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-600 text-3xl text-white shadow-lg mb-4">
        &#10003;
      </div>

      <div class="mb-2">
        <span class="code-label">Nomor Pengajuan #{{ $peminjaman->id }}</span>
      </div>

      <h1 class="text-2xl sm:text-3xl font-extrabold text-heading">
        Pengajuan Berhasil Disimpan!
      </h1>

      <p class="mt-2 text-sm text-muted max-w-md mx-auto">
        Data pengajuan peminjaman alat laboratorium Anda telah tersimpan ke dalam database dan menunggu persetujuan petugas laboran.
      </p>
    </div>

    <div class="p-6 sm:p-8 space-y-6">
      <!-- Ringkasan Rincian Pengajuan -->
      <div class="surface-inset p-6 space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-wider text-accent">Ringkasan Peminjaman</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div>
            <span class="text-muted block mb-0.5">Alat Laboratorium</span>
            <span class="font-bold text-heading text-sm">{{ $alat->nama }}</span>
            <span class="text-muted block font-mono">{{ $alat->kode }} ({{ $alat->kategori }})</span>
          </div>

          <div>
            <span class="text-muted block mb-0.5">Identitas Peminjam</span>
            <span class="font-bold text-heading text-sm">{{ $peminjaman->nama_peminjam }}</span>
            <span class="text-muted block font-mono">NIM: {{ $peminjaman->nim }}</span>
          </div>

          <div>
            <span class="text-muted block mb-0.5">Jadwal Penggunaan</span>
            <span class="font-bold text-heading">
              {{ $peminjaman->tanggal_pinjam->format('d M Y') }} s.d. {{ $peminjaman->tanggal_selesai->format('d M Y') }}
            </span>
            <span class="text-muted block">Durasi: {{ $peminjaman->durasi }} hari kalender</span>
          </div>

          <div>
            <span class="text-muted block mb-0.5">Jumlah Unit Dipinjam</span>
            <span class="font-bold text-accent text-sm">{{ $peminjaman->jumlah }} Unit</span>
            <span class="text-muted block">Lokasi: {{ $alat->lokasi }}</span>
          </div>
        </div>

        <div class="pt-3 border-t divider-subtle text-xs">
          <span class="text-muted block mb-0.5">Keperluan Peminjaman</span>
          <p class="text-heading font-medium">{{ $peminjaman->keperluan }}</p>
        </div>
      </div>

      <!-- Status Peringatan -->
      <div class="rounded-2xl border border-amber-400/40 bg-amber-500/10 p-4 text-xs text-heading flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
          <strong class="block font-semibold">Status: Menunggu Persetujuan Petugas Laboran</strong>
          <span class="text-muted">Alokasi unit telah dicadangkan untuk Anda. Silakan pantau status persetujuan di dashboard atau hubungi petugas laboratorium saat jadwal peminjaman tiba.</span>
        </div>
      </div>

      <!-- Navigasi Aksi -->
      <div class="flex flex-wrap items-center gap-3 pt-2">
        @auth
          @if(Auth::user()->isPeminjam())
            <a href="{{ route('peminjam.dashboard') }}" class="btn">
              Ke Dashboard Peminjam
            </a>
            <a href="{{ route('peminjam.riwayat') }}" class="btn btn-secondary">
              Lihat Riwayat Saya
            </a>
          @else
            <a href="{{ route('dashboard') }}" class="btn">
              Ke Dashboard Saya
            </a>
          @endif
        @else
          <a href="{{ route('alat.beranda') }}" class="btn">
            Kembali ke Beranda
          </a>
          <a href="{{ route('login') }}" class="btn btn-secondary">
            Masuk untuk Pantau Status
          </a>
        @endauth

        <a href="{{ route('alat.detail', $alat->id) }}" class="btn btn-ghost text-xs">
          Detail Alat Ini
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
