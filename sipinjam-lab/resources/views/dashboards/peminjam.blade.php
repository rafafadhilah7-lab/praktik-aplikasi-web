@extends('layouts.app')

@section('title', 'Dashboard Peminjam')

@section('content')
<div class="space-y-8">
  <!-- Banner Mahasiswa / Peminjam -->
  <div class="rounded-3xl bg-gradient-to-r from-pink-600 via-rose-500 to-pink-400 p-8 text-white shadow-xl shadow-pink-200/50 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-semibold mb-3">
        <span class="w-2 h-2 rounded-full bg-white"></span>
        Halo, {{ $user->name }}
      </div>
      <h1 class="text-3xl font-extrabold tracking-tight text-white mb-2">Portal Peminjaman Laboratorium</h1>
      <p class="text-pink-100 text-sm max-w-xl">
        Pantau status permohonan alat, jadwal pengembalian, serta telusuri inventaris alat laboratorium yang siap dipinjam.
      </p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('alat.beranda') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-pink-700 font-bold text-sm shadow-md hover:bg-pink-50 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        Cari & Pinjam Alat
      </a>
    </div>
  </div>

  <!-- Metric Cards -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="stat-card">
      <div class="stat-icon bg-pink-50 text-pink-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Total Pengajuan Saya</div>
        <div class="text-2xl font-extrabold text-slate-900">{{ $totalPengajuanSaya }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-amber-50 text-amber-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Menunggu Persetujuan</div>
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
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Disetujui (Siap Ambil)</div>
        <div class="text-2xl font-extrabold text-purple-600">{{ $pengajuanDisetujui }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-sky-50 text-sky-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Sedang Dipinjam</div>
        <div class="text-2xl font-extrabold text-sky-600">{{ $alatSedangDipinjam }}</div>
      </div>
    </div>
  </div>

  <!-- Peminjaman Aktif yang Berjalan -->
  <div class="card p-6 bg-white border-pink-100 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-lg font-bold text-slate-900">Pengajuan & Peminjaman Aktif</h2>
        <p class="text-xs text-slate-500">Pantau proses persetujuan dan jadwal peminjaman alat Anda</p>
      </div>
      <a href="{{ route('peminjam.riwayat') }}" class="btn btn-secondary btn-sm">
        Lihat Semua Riwayat
      </a>
    </div>

    @if($peminjamanAktif->isEmpty())
      <div class="py-10 text-center bg-pink-50/20 rounded-2xl border border-dashed border-pink-200">
        <div class="w-12 h-12 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center mx-auto mb-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </div>
        <div class="font-bold text-slate-800 text-sm">Tidak ada peminjaman aktif</div>
        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
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
          <div class="p-4 rounded-2xl border border-slate-200 bg-white hover:border-pink-300 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-700 flex items-center justify-center font-bold text-sm font-mono border border-pink-100 flex-shrink-0">
                #{{ $item->id }}
              </div>
              <div>
                <div class="flex items-center gap-2 mb-1">
                  <h3 class="font-bold text-base text-slate-900">{{ $item->alat->nama }}</h3>
                  <x-badge :status="$item->status" />
                </div>
                <div class="text-xs text-slate-500 flex flex-wrap gap-x-4 gap-y-1">
                  <span>Kode: <strong class="text-slate-700">{{ $item->alat->kode }}</strong></span>
                  <span>Jumlah: <strong class="text-pink-700">{{ $item->jumlah }} unit</strong></span>
                  <span>Jadwal: <strong class="text-slate-700">{{ $item->tanggal_pinjam->format('d M') }} - {{ $item->tanggal_selesai->format('d M Y') }}</strong> ({{ $item->durasi }} hari)</span>
                </div>
                @if($item->catatan_petugas)
                  <div class="mt-2 text-xs bg-amber-50 text-amber-800 p-2 rounded-lg border border-amber-200">
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

  <!-- Katalog Alat Siap Dipinjam (Katalog Singkat) -->
  <div class="card p-6 bg-white">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h2 class="text-lg font-bold text-slate-900">Katalog Alat Tersedia</h2>
        <p class="text-xs text-slate-500">Pilihan alat laboratorium yang memiliki stok tersedia untuk dipinjam</p>
      </div>
      <a href="{{ route('alat.hasil') }}" class="btn btn-secondary btn-sm">
        Lihat Seluruh Katalog &rarr;
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      @foreach($alatTersedia as $alat)
        <div class="rounded-2xl border border-pink-100 bg-[#fdfafb] p-4 flex flex-col justify-between hover:border-pink-300 hover:shadow-md transition-all">
          <div>
            <div class="flex items-center justify-between text-xs mb-2">
              <span class="code-label">{{ $alat->kode }}</span>
              <span class="font-bold text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                {{ $alat->stok }} Tersedia
              </span>
            </div>
            <h3 class="font-bold text-sm text-slate-900 mb-1 line-clamp-1">{{ $alat->nama }}</h3>
            <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $alat->deskripsi ?? 'Alat laboratorium praktikum.' }}</p>
          </div>
          <div class="pt-3 border-t border-pink-100 flex items-center justify-between">
            <span class="text-[11px] text-slate-400 font-medium">{{ $alat->kategori }}</span>
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
