@extends('layouts.app')

@section('title', 'Portal Peminjaman Mahasiswa')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#e5e7eb]">
    <div>
      <div class="flex items-center gap-2 mb-1">
        <h1 class="text-xl font-bold text-[#202938]">Portal Peminjaman Mahasiswa</h1>
        <span class="badge badge-success text-[10px]">Mahasiswa Aktif</span>
      </div>
      <p class="text-xs text-[#64748b]">
        Selamat datang, <strong>{{ $user->name }}</strong> (NIM: {{ $user->nim ?? '-' }}). Pantau status persetujuan alat laboratorium Anda.
      </p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('alat.beranda') }}" class="btn btn-primary btn-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
        </svg>
        <span>Cari & Pinjam Alat</span>
      </a>
      <a href="{{ route('peminjam.riwayat') }}" class="btn btn-secondary btn-sm">
        <span>Riwayat Lengkap</span>
      </a>
    </div>
  </div>

  <!-- KPI Summary (Compact Academic Portal) -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="kpi-card">
      <div class="kpi-icon bg-slate-100 text-slate-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Total Pengajuan</div>
        <div class="kpi-value">{{ $totalPengajuanSaya }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-amber-50 text-amber-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Menunggu Persetujuan</div>
        <div class="kpi-value text-amber-600">{{ $pengajuanMenunggu }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-blue-50 text-blue-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Disetujui (Siap Ambil)</div>
        <div class="kpi-value text-blue-600">{{ $pengajuanDisetujui }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-sky-50 text-sky-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Sedang Dipinjam</div>
        <div class="kpi-value text-slate-800">{{ $alatSedangDipinjam }}</div>
      </div>
    </div>
  </div>

  <!-- Pengajuan Aktif yang Sedang Berjalan -->
  <div class="card overflow-hidden">
    <div class="card-header bg-[#f8fafc]">
      <div>
        <h3 class="card-title text-sm">Status Peminjaman Aktif</h3>
        <p class="text-xs text-[#64748b]">Daftar permohonan yang sedang dalam proses verifikasi atau sedang dipinjam</p>
      </div>
      <a href="{{ route('peminjam.riwayat') }}" class="btn btn-secondary btn-sm">
        Riwayat Pengajuan
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="enterprise-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Alat Laboratorium</th>
            <th>Jadwal Peminjaman</th>
            <th>Durasi</th>
            <th>Qty</th>
            <th>Status Peminjaman</th>
            <th class="text-right">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($peminjamanAktif as $item)
            <tr>
              <td class="font-mono text-xs font-semibold text-slate-700">#{{ $item->id }}</td>
              <td>
                <div class="font-medium text-[#202938]">{{ $item->alat->nama }}</div>
                <div class="text-[11px] text-[#64748b] font-mono">{{ $item->alat->kode }} &bull; {{ $item->alat->kategori }}</div>
              </td>
              <td class="text-xs">
                <div>{{ $item->tanggal_pinjam->format('d/m/Y') }} s.d. {{ $item->tanggal_selesai->format('d/m/Y') }}</div>
              </td>
              <td class="text-xs text-[#64748b]">{{ $item->durasi }} hari</td>
              <td class="font-semibold text-xs">{{ $item->jumlah }} Unit</td>
              <td>
                <x-badge :status="$item->status" />
              </td>
              <td class="text-right">
                <a href="{{ route('peminjaman.detail', $item->id) }}" class="btn btn-secondary btn-sm">
                  Lihat Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-6 text-slate-400 text-xs">
                Tidak ada peminjaman aktif saat ini. Silakan cari alat untuk memulai permohonan baru.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Katalog Alat Siap Dipinjam -->
  <div class="card">
    <div class="card-header">
      <div>
        <h3 class="card-title text-sm">Katalog Alat Laboratorium Tersedia</h3>
        <p class="text-xs text-[#64748b]">Daftar alat dengan unit stok fisik siap dipinjam</p>
      </div>
      <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm">
        Buka Katalog Lengkap &rarr;
      </a>
    </div>

    <div class="card-body">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($alatTersedia as $alat)
          <div class="rounded-md border border-[#e5e7eb] bg-white p-3.5 flex flex-col justify-between hover:border-blue-400 transition-colors">
            <div>
              <div class="flex items-center justify-between gap-1 mb-2">
                <span class="code-badge">{{ $alat->kode }}</span>
                <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                  {{ $alat->stok }} Unit
                </span>
              </div>
              <h4 class="font-semibold text-xs text-[#202938] line-clamp-1 mb-1">{{ $alat->nama }}</h4>
              <p class="text-[11px] text-[#64748b] line-clamp-2 mb-3">{{ $alat->deskripsi ?? 'Alat laboratorium praktikum akademik.' }}</p>
            </div>
            <div class="pt-2.5 border-t border-[#f1f5f9] flex items-center justify-between">
              <span class="text-[11px] text-slate-500">{{ $alat->kategori }}</span>
              <a href="{{ route('alat.pinjam', $alat->id) }}" class="btn btn-primary btn-sm">
                Pinjam
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
