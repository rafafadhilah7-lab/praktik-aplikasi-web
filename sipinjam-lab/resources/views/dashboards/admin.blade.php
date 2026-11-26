@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="space-y-8">
  <!-- Header Banner -->
  <div class="hero-banner rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-sky-300/20 text-xs font-semibold text-sky-100 mb-3">
        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
        Sistem Manajemen Administrator
      </div>
      <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">Dashboard Administrator</h1>
      <p class="text-sky-100/90 text-sm max-w-xl">
        Kelola seluruh pengguna, inventaris alat laboratorium, serta pantau seluruh riwayat transaksi peminjaman secara terpadu.
      </p>
    </div>
    <div class="flex flex-wrap gap-3">
      <a href="{{ route('admin.alat.create') }}"
         class="btn shadow-md">
        <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Alat Baru
      </a>
      <a href="{{ route('admin.users.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 text-white font-semibold text-sm border border-white/25 hover:bg-white/25 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Kelola Pengguna
      </a>
    </div>
  </div>

  <!-- Metric Cards Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="stat-card">
      <div class="stat-icon">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Total Pengguna</div>
        <div class="text-2xl font-extrabold text-heading">{{ $totalUsers }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Total Alat</div>
        <div class="text-2xl font-extrabold text-heading">{{ $totalAlat }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-amber-500/15 text-amber-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Menunggu Verifikasi</div>
        <div class="text-2xl font-extrabold text-amber-500">{{ $pengajuanMenunggu }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-emerald-500/15 text-emerald-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Disetujui</div>
        <div class="text-2xl font-extrabold text-emerald-500">{{ $pengajuanDisetujui }}</div>
      </div>
    </div>
  </div>

  <!-- Statistik Peminjaman Ringkas -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="surface-card p-6 flex flex-col justify-between">
      <div>
        <h3 class="text-base font-bold text-heading mb-1">Status Peminjaman</h3>
        <p class="text-xs text-muted mb-4">Ringkasan seluruh siklus transaksi di lab</p>
        <div class="space-y-2.5">
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20">
            <span class="text-sm font-medium text-heading">Menunggu Persetujuan</span>
            <span class="font-bold text-amber-500">{{ $pengajuanMenunggu }}</span>
          </div>
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-purple-500/10 border border-purple-500/20">
            <span class="text-sm font-medium text-heading">Disetujui (Siap Diambil)</span>
            <span class="font-bold text-purple-500">{{ $pengajuanDisetujui }}</span>
          </div>
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-sky-500/10 border border-sky-500/20">
            <span class="text-sm font-medium text-heading">Sedang Dipinjam Mahasiswa</span>
            <span class="font-bold text-accent">{{ $alatDipinjam }}</span>
          </div>
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
            <span class="text-sm font-medium text-heading">Sudah Dikembalikan</span>
            <span class="font-bold text-emerald-500">{{ $alatDikembalikan }}</span>
          </div>
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-red-500/10 border border-red-500/20">
            <span class="text-sm font-medium text-heading">Pengajuan Ditolak</span>
            <span class="font-bold text-red-500">{{ $pengajuanDitolak }}</span>
          </div>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t divider-subtle">
        <a href="{{ route('peminjaman.index') }}" class="text-xs link-accent flex items-center justify-between">
          <span>Buka semua riwayat transaksi</span>
          <span>&rarr;</span>
        </a>
      </div>
    </div>

    <!-- Kategori Alat -->
    <div class="surface-card p-6">
      <h3 class="text-base font-bold text-heading mb-1">Sebaran Kategori Alat</h3>
      <p class="text-xs text-muted mb-4">Jumlah jenis alat dan total stok per kategori</p>
      <div class="space-y-3">
        @forelse ($kategoriStats as $kat)
          <div class="surface-inset p-3 flex items-center justify-between">
            <div>
              <div class="font-bold text-sm text-heading">{{ $kat->kategori }}</div>
              <div class="text-xs text-muted">{{ $kat->total }} jenis alat</div>
            </div>
            <div class="text-right">
              <span class="code-label">
                {{ $kat->total_stok }} unit
              </span>
            </div>
          </div>
        @empty
          <p class="text-xs text-muted">Belum ada kategori alat.</p>
        @endforelse
      </div>
    </div>

    <!-- Peringatan Stok Rendah -->
    <div class="surface-card p-6">
      <div class="flex items-center justify-between mb-1">
        <h3 class="text-base font-bold text-heading">Alat Stok Rendah (&le; 2)</h3>
        <span class="badge badge-danger">
          {{ $alatStokRendah->count() }} Alat
        </span>
      </div>
      <p class="text-xs text-muted mb-4">Perlu perhatian untuk pengadaan atau pembatasan</p>
      <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
        @forelse ($alatStokRendah as $alat)
          <div class="p-3 rounded-xl border {{ $alat->stok === 0 ? 'border-red-500/30 bg-red-500/10' : 'border-amber-500/30 bg-amber-500/10' }} flex items-center justify-between gap-2">
            <div class="min-w-0">
              <div class="font-semibold text-xs text-heading truncate">{{ $alat->nama }}</div>
              <div class="text-[11px] text-muted font-mono">{{ $alat->kode }} - {{ $alat->kategori }}</div>
            </div>
            <div class="text-right shrink-0">
              <span class="font-extrabold text-xs {{ $alat->stok === 0 ? 'text-red-500' : 'text-amber-500' }}">
                {{ $alat->stok }} Unit
              </span>
              <div class="mt-0.5">
                <a href="{{ route('admin.alat.edit', $alat->id) }}" class="text-[11px] link-accent">
                  Ubah Stok
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs text-center">
            Semua alat memiliki stok lebih dari 2 unit.
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Pengajuan Peminjaman Terbaru -->
  <div class="surface-card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
      <div>
        <h3 class="text-lg font-bold text-heading">Pengajuan Peminjaman Terbaru</h3>
        <p class="text-xs text-muted">Transaksi peminjaman terbaru yang masuk ke sistem</p>
      </div>
      <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm self-start sm:self-auto">
        Lihat Semua Transaksi
      </a>
    </div>

    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID / Tanggal</th>
            <th>Peminjam</th>
            <th>Alat Laboratorium</th>
            <th>Durasi &amp; Jumlah</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pengajuanTerbaru as $item)
            <tr>
              <td>
                <span class="code-label">#{{ $item->id }}</span>
                <div class="text-xs text-muted mt-1">{{ $item->created_at->format('d M Y, H:i') }}</div>
              </td>
              <td>
                <div class="font-semibold text-heading">{{ $item->nama_peminjam }}</div>
                <div class="text-xs text-muted font-mono">NIM: {{ $item->nim }}</div>
              </td>
              <td>
                <div class="font-medium text-heading">{{ $item->alat->nama ?? '-' }}</div>
                <div class="text-xs text-muted font-mono">{{ $item->alat->kode ?? '-' }}</div>
              </td>
              <td>
                <span class="font-semibold text-heading">{{ $item->jumlah }} unit</span>
                <div class="text-xs text-muted">{{ $item->durasi }} hari ({{ $item->tanggal_pinjam->format('d/m') }} - {{ $item->tanggal_selesai->format('d/m') }})</div>
              </td>
              <td>
                <x-badge :status="$item->status" />
              </td>
              <td>
                <a href="{{ route('peminjaman.detail', $item->id) }}" class="btn btn-secondary btn-sm">
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-6 text-muted">Belum ada pengajuan peminjaman.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
