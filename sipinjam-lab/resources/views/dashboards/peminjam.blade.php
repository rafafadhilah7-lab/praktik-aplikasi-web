@extends('layouts.app')

@section('title', 'Dashboard Peminjam')

@section('content')
<div class="space-y-8">
  <!-- Banner Mahasiswa / Peminjam -->
  <div class="hero-banner rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-sky-300/20 text-xs font-semibold text-sky-100 mb-3">
        <span class="w-2 h-2 rounded-full bg-sky-300"></span>
        Halo, {{ $user->name }}
      </div>
      <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">Portal Peminjaman Laboratorium</h1>
      <p class="text-sky-100/90 text-sm max-w-xl">
        Pantau status permohonan alat, jadwal pengembalian, serta telusuri inventaris alat laboratorium yang siap dipinjam.
      </p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('alat.beranda') }}"
         class="btn shadow-md">
        <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        Cari &amp; Pinjam Alat
      </a>
    </div>
  </div>

  <!-- Metric Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="stat-card">
      <div class="stat-icon">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Total Pengajuan Saya</div>
        <div class="text-2xl font-extrabold text-heading">{{ $totalPengajuanSaya }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-amber-500/15 text-amber-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Menunggu Persetujuan</div>
        <div class="text-2xl font-extrabold text-amber-500">{{ $pengajuanMenunggu }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-purple-500/15 text-purple-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Disetujui (Siap Ambil)</div>
        <div class="text-2xl font-extrabold text-purple-500">{{ $pengajuanDisetujui }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Sedang Dipinjam</div>
        <div class="text-2xl font-extrabold text-accent">{{ $alatSedangDipinjam }}</div>
      </div>
    </div>
  </div>

  <!-- Peminjaman Aktif yang Berjalan -->
  <div class="surface-card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
      <div>
        <h2 class="text-lg font-bold text-heading">Pengajuan &amp; Peminjaman Aktif</h2>
        <p class="text-xs text-muted">Pantau proses persetujuan dan jadwal peminjaman alat Anda</p>
      </div>
      <a href="{{ route('peminjam.riwayat') }}" class="btn btn-secondary btn-sm self-start sm:self-auto">
        Lihat Semua Riwayat
      </a>
    </div>

    @if($peminjamanAktif->isEmpty())
      <div class="py-10 px-4 text-center surface-inset border-dashed">
        <div class="w-12 h-12 rounded-full bg-sky-500/15 text-accent flex items-center justify-center mx-auto mb-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <div class="font-bold text-heading text-sm">Tidak ada peminjaman aktif</div>
        <p class="text-xs text-muted mt-1 max-w-sm mx-auto">
          Anda tidak memiliki peminjaman yang sedang menunggu, disetujui, atau dipinjam saat ini.
        </p>
        <div class="mt-4">
          <a href="{{ route('alat.beranda') }}" class="btn btn-sm">
            Mulai Ajukan Peminjaman
          </a>
        </div>
      </div>
    @else
      <div class="space-y-4">
        @foreach($peminjamanAktif as $item)
          <div class="surface-inset p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
              <div class="code-label px-3 py-2 shrink-0">
                #{{ $item->id }}
              </div>
              <div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                  <h3 class="font-bold text-base text-heading">{{ $item->alat->nama }}</h3>
                  <x-badge :status="$item->status" />
                </div>
                <div class="text-xs text-muted flex flex-wrap gap-x-4 gap-y-1">
                  <span>Kode: <strong class="text-heading">{{ $item->alat->kode }}</strong></span>
                  <span>Jumlah: <strong class="text-accent">{{ $item->jumlah }} unit</strong></span>
                  <span>Jadwal: <strong class="text-heading">{{ $item->tanggal_pinjam->format('d M') }} - {{ $item->tanggal_selesai->format('d M Y') }}</strong> ({{ $item->durasi }} hari)</span>
                </div>
                @if($item->catatan_petugas)
                  <div class="mt-2 text-xs bg-amber-500/10 text-heading p-2 rounded-lg border border-amber-500/25">
                    <strong>Catatan Petugas:</strong> {{ $item->catatan_petugas }}
                  </div>
                @endif
              </div>
            </div>

            <div class="flex items-center gap-2 self-end md:self-center">
              <a href="{{ route('peminjaman.detail', $item->id) }}" class="btn btn-secondary btn-sm">
                Lihat Bukti Pengajuan
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <!-- Katalog Alat Siap Dipinjam -->
  <div class="surface-card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
      <div>
        <h2 class="text-lg font-bold text-heading">Katalog Alat Tersedia</h2>
        <p class="text-xs text-muted">Pilihan alat laboratorium yang memiliki stok tersedia untuk dipinjam</p>
      </div>
      <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm self-start sm:self-auto">
        Lihat Seluruh Katalog &rarr;
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      @foreach($alatTersedia as $alat)
        <div class="card flex flex-col justify-between">
          <div class="card-body">
            <x-alat-illustration :nama="$alat->nama" :kategori="$alat->kategori" class="mb-2" />
            <div class="flex items-center justify-between gap-2 text-xs">
              <span class="code-label">{{ $alat->kode }}</span>
              <span class="badge badge-success">
                {{ $alat->stok }} Tersedia
              </span>
            </div>
            <h3 class="font-bold text-sm text-heading line-clamp-1 mt-1">{{ $alat->nama }}</h3>
            <p class="text-xs text-muted line-clamp-2">{{ $alat->deskripsi ?? 'Alat laboratorium praktikum.' }}</p>
          </div>
          <div class="card-footer flex items-center justify-between">
            <span class="text-[11px] text-muted font-medium">{{ $alat->kategori }}</span>
            <a href="{{ route('alat.pinjam', $alat->id) }}" class="btn btn-sm text-xs font-semibold">
              Pinjam
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
