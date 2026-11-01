@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="space-y-8">
  <!-- Header Banner -->
  <div class="rounded-3xl bg-gradient-to-r from-pink-700 via-rose-600 to-pink-500 p-8 text-white shadow-xl shadow-pink-200/50 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-semibold mb-3">
        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
        Sistem Manajemen Administrator
      </div>
      <h1 class="text-3xl font-extrabold tracking-tight text-white mb-2">Dashboard Administrator</h1>
      <p class="text-pink-100 text-sm max-w-xl">
        Kelola seluruh pengguna, inventaris alat laboratorium, serta pantau seluruh riwayat transaksi peminjaman secara terpadu.
      </p>
    </div>
    <div class="flex flex-wrap gap-3">
      <a href="{{ route('admin.alat.create') }}"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-pink-700 font-bold text-sm shadow-md hover:bg-pink-50 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Alat Baru
      </a>
      <a href="{{ route('admin.users.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-pink-800/60 text-white font-semibold text-sm border border-pink-400/30 hover:bg-pink-800/90 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Kelola Pengguna
      </a>
    </div>
  </div>

  <!-- Metric Cards Grid -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="stat-card">
      <div class="stat-icon bg-pink-50 text-pink-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Total Pengguna</div>
        <div class="text-2xl font-extrabold text-slate-900">{{ $totalUsers }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-rose-50 text-rose-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Total Alat</div>
        <div class="text-2xl font-extrabold text-slate-900">{{ $totalAlat }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-amber-50 text-amber-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Menunggu Verifikasi</div>
        <div class="text-2xl font-extrabold text-amber-600">{{ $pengajuanMenunggu }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-purple-50 text-purple-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Disetujui</div>
        <div class="text-2xl font-extrabold text-purple-600">{{ $pengajuanDisetujui }}</div>
      </div>
    </div>
  </div>

  <!-- Statistik Peminjaman Ringkas -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card p-6 bg-white flex flex-col justify-between">
      <div>
        <h3 class="text-base font-bold text-slate-900 mb-1">Status Peminjaman</h3>
        <p class="text-xs text-slate-500 mb-4">Ringkasan seluruh siklus transaksi di lab</p>
        <div class="space-y-3">
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-amber-50/70 border border-amber-100">
            <span class="text-sm font-medium text-amber-800">Menunggu Persetujuan</span>
            <span class="font-bold text-amber-900">{{ $pengajuanMenunggu }}</span>
          </div>
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-purple-50/70 border border-purple-100">
            <span class="text-sm font-medium text-purple-800">Disetujui (Siap Diambil)</span>
            <span class="font-bold text-purple-900">{{ $pengajuanDisetujui }}</span>
          </div>
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-sky-50/70 border border-sky-100">
            <span class="text-sm font-medium text-sky-800">Sedang Dipinjam Mahasiswa</span>
            <span class="font-bold text-sky-900">{{ $alatDipinjam }}</span>
          </div>
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-100">
            <span class="text-sm font-medium text-emerald-800">Sudah Dikembalikan</span>
            <span class="font-bold text-emerald-900">{{ $alatDikembalikan }}</span>
          </div>
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-rose-50/70 border border-rose-100">
            <span class="text-sm font-medium text-rose-800">Pengajuan Ditolak</span>
            <span class="font-bold text-rose-900">{{ $pengajuanDitolak }}</span>
          </div>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-slate-100">
        <a href="{{ route('peminjaman.index') }}" class="text-xs font-semibold text-pink-600 hover:text-pink-700 flex items-center justify-between">
          <span>Buka semua riwayat transaksi</span>
          <span>&rarr;</span>
        </a>
      </div>
    </div>

    <!-- Kategori Alat -->
    <div class="card p-6 bg-white">
      <h3 class="text-base font-bold text-slate-900 mb-1">Sebaran Kategori Alat</h3>
      <p class="text-xs text-slate-500 mb-4">Jumlah jenis alat dan total stok per kategori</p>
      <div class="space-y-3">
        @forelse ($kategoriStats as $kat)
          <div class="p-3 rounded-xl border border-pink-50 bg-[#fdfafb] flex items-center justify-between">
            <div>
              <div class="font-bold text-sm text-slate-800">{{ $kat->kategori }}</div>
              <div class="text-xs text-slate-500">{{ $kat->total }} jenis alat</div>
            </div>
            <div class="text-right">
              <span class="px-2.5 py-1 rounded-lg bg-pink-100 text-pink-800 font-bold text-xs">
                {{ $kat->total_stok }} unit
              </span>
            </div>
          </div>
        @empty
          <p class="text-xs text-slate-400">Belum ada kategori alat.</p>
        @endforelse
      </div>
    </div>

    <!-- Peringatan Stok Rendah -->
    <div class="card p-6 bg-white">
      <div class="flex items-center justify-between mb-1">
        <h3 class="text-base font-bold text-slate-900">Alat Stok Rendah (&le; 2)</h3>
        <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 font-bold text-xs">
          {{ $alatStokRendah->count() }} Alat
        </span>
      </div>
      <p class="text-xs text-slate-500 mb-4">Perlu perhatian untuk pengadaan atau pembatasan</p>
      <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
        @forelse ($alatStokRendah as $alat)
          <div class="p-3 rounded-xl border {{ $alat->stok === 0 ? 'border-rose-200 bg-rose-50/50' : 'border-amber-200 bg-amber-50/50' }} flex items-center justify-between">
            <div>
              <div class="font-semibold text-xs text-slate-900">{{ $alat->nama }}</div>
              <div class="text-[11px] text-slate-500 font-mono">{{ $alat->kode }} - {{ $alat->kategori }}</div>
            </div>
            <div class="text-right">
              <span class="font-extrabold text-xs {{ $alat->stok === 0 ? 'text-rose-600' : 'text-amber-700' }}">
                {{ $alat->stok }} Unit
              </span>
              <div class="mt-0.5">
                <a href="{{ route('admin.alat.edit', $alat->id) }}" class="text-[10px] text-pink-600 hover:underline">
                  Ubah Stok
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs text-center">
            Semua alat memiliki stok lebih dari 2 unit.
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Pengajuan Peminjaman Terbaru -->
  <div class="card p-6 bg-white">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-bold text-slate-900">Pengajuan Peminjaman Terbaru</h3>
        <p class="text-xs text-slate-500">Transaksi peminjaman terbaru yang masuk ke sistem</p>
      </div>
      <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm">
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
            <th>Durasi & Jumlah</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($pengajuanTerbaru as $item)
            <tr>
              <td>
                <span class="font-mono font-bold text-xs text-pink-700">#{{ $item->id }}</span>
                <div class="text-xs text-slate-400">{{ $item->created_at->format('d M Y, H:i') }}</div>
              </td>
              <td>
                <div class="font-semibold text-slate-900">{{ $item->nama_peminjam }}</div>
                <div class="text-xs text-slate-500">NIM: {{ $item->nim }}</div>
              </td>
              <td>
                <div class="font-medium text-slate-900">{{ $item->alat->nama ?? '-' }}</div>
                <div class="text-xs text-slate-400 font-mono">{{ $item->alat->kode ?? '-' }}</div>
              </td>
              <td>
                <span class="font-semibold text-slate-800">{{ $item->jumlah }} unit</span>
                <div class="text-xs text-slate-500">{{ $item->durasi }} hari ({{ $item->tanggal_pinjam->format('d/m') }} - {{ $item->tanggal_selesai->format('d/m') }})</div>
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
              <td colspan="6" class="text-center py-6 text-slate-400">Belum ada pengajuan peminjaman.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
