@extends('layouts.app')

@section('title', 'Detail Pengajuan #' . $peminjaman->id)

@section('content')
<div class="mx-auto max-w-4xl px-4 py-6 space-y-6">
  <!-- Navigasi Kembali -->
  <div class="flex items-center justify-between">
    <a href="{{ url()->previous(route('peminjaman.index')) }}"
       class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-pink-600 hover:text-pink-700 transition-colors">
      &larr; Kembali
    </a>
    <span class="text-xs text-slate-400">Diajukan pada: {{ $peminjaman->created_at->format('d M Y, H:i') }}</span>
  </div>

  <!-- Kartu Detail Utama -->
  <div class="overflow-hidden rounded-3xl border border-pink-100 bg-white shadow-xl shadow-pink-100/30">
    <!-- Header Status -->
    <div class="bg-gradient-to-r from-pink-50 via-[#fdf7f9] to-rose-50 p-6 sm:p-8 border-b border-pink-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="font-mono text-xs font-extrabold uppercase tracking-wider text-pink-700 bg-pink-100/70 px-2.5 py-0.5 rounded-md">
            Transaksi #{{ $peminjaman->id }}
          </span>
          <x-badge :status="$peminjaman->status" />
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
          {{ $peminjaman->alat->nama }}
        </h1>
        <p class="text-xs text-slate-500 mt-1">Kode Inventaris: {{ $peminjaman->alat->kode }} &bull; Kategori: {{ $peminjaman->alat->kategori }}</p>
      </div>

      <!-- Quick Action Buttons for Laboran/Admin -->
      @if(Auth::check() && (Auth::user()->isLaboran() || Auth::user()->isAdmin()))
        <div class="flex flex-wrap items-center gap-2">
          @if($peminjaman->isMenunggu())
            <form action="{{ route('peminjaman.setujui', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Setujui permohonan ini?');">
              @csrf
              <button type="submit" class="btn btn-success btn-sm">Setujui</button>
            </form>
            <button type="button"
                    onclick="openRejectModal({{ $peminjaman->id }})"
                    class="btn btn-danger btn-sm">
              Tolak
            </button>
          @elseif($peminjaman->isDisetujui())
            <form action="{{ route('peminjaman.serahkan', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Serahkan alat fisik kepada peminjam?');">
              @csrf
              <button type="submit" class="btn btn-sm bg-purple-600 text-white hover:bg-purple-700">Serahkan Alat</button>
            </form>
          @elseif($peminjaman->isDipinjam())
            <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian alat ke laboratorium?');">
              @csrf
              <button type="submit" class="btn btn-success btn-sm">Kembalikan Alat</button>
            </form>
          @endif
        </div>
      @endif
    </div>

    <div class="p-6 sm:p-8 space-y-6">
      <!-- Grid Informasi 2 Kolom -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Kolom Data Peminjam -->
        <div class="rounded-2xl border border-slate-100 bg-[#faf8fa] p-5 space-y-3">
          <h2 class="text-xs font-bold uppercase tracking-wider text-pink-700 mb-2">Identitas Peminjam</h2>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">Nama Lengkap</span>
            <span class="font-bold text-slate-900">{{ $peminjaman->nama_peminjam }}</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">NIM</span>
            <span class="font-bold text-slate-900 font-mono">{{ $peminjaman->nim }}</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">Status Akun</span>
            <span class="text-emerald-700 font-semibold">{{ $peminjaman->user_id ? 'Terdaftar (Terverifikasi)' : 'Tamu' }}</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">Keperluan</span>
            <span class="text-slate-800 font-medium text-right max-w-xs">{{ $peminjaman->keperluan }}</span>
          </div>
        </div>

        <!-- Kolom Detail Peminjaman & Jadwal -->
        <div class="rounded-2xl border border-slate-100 bg-[#faf8fa] p-5 space-y-3">
          <h2 class="text-xs font-bold uppercase tracking-wider text-pink-700 mb-2">Jadwal & Spesifikasi</h2>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">Jumlah Unit</span>
            <span class="font-bold text-pink-700 text-sm">{{ $peminjaman->jumlah }} Unit</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">Tanggal Pinjam</span>
            <span class="font-bold text-slate-900">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">Tanggal Selesai</span>
            <span class="font-bold text-slate-900">{{ $peminjaman->tanggal_selesai->format('d F Y') }}</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">Durasi Peminjaman</span>
            <span class="font-bold text-slate-900">{{ $peminjaman->durasi }} Hari Kalender</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-slate-500">Lokasi Lab</span>
            <span class="font-medium text-slate-700">{{ $peminjaman->alat->lokasi }}</span>
          </div>
        </div>
      </div>

      <!-- Jejak Transaksi (Audit Trail / Timestamps) -->
      <div class="rounded-2xl border border-pink-100 bg-white p-5">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-4">Kronologi & Jejak Transaksi</h2>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
          <div class="p-3 rounded-xl bg-pink-50/50 border border-pink-100">
            <div class="text-[10px] font-bold text-slate-400 uppercase">1. Pengajuan</div>
            <div class="text-xs font-semibold text-slate-800 mt-1">{{ $peminjaman->created_at->format('d/m/Y H:i') }}</div>
            <div class="text-[10px] text-pink-600 font-medium">Tersimpan</div>
          </div>

          <div class="p-3 rounded-xl {{ $peminjaman->tanggal_persetujuan ? 'bg-purple-50/60 border border-purple-100' : 'bg-slate-50 border border-slate-100 opacity-60' }}">
            <div class="text-[10px] font-bold text-slate-400 uppercase">2. Persetujuan</div>
            <div class="text-xs font-semibold text-slate-800 mt-1">
              {{ $peminjaman->tanggal_persetujuan ? $peminjaman->tanggal_persetujuan->format('d/m/Y H:i') : '-' }}
            </div>
            <div class="text-[10px] text-purple-600 font-medium">{{ $peminjaman->tanggal_persetujuan ? 'Disetujui Petugas' : 'Belum Diproses' }}</div>
          </div>

          <div class="p-3 rounded-xl {{ $peminjaman->tanggal_pengambilan ? 'bg-sky-50/60 border border-sky-100' : 'bg-slate-50 border border-slate-100 opacity-60' }}">
            <div class="text-[10px] font-bold text-slate-400 uppercase">3. Pengambilan</div>
            <div class="text-xs font-semibold text-slate-800 mt-1">
              {{ $peminjaman->tanggal_pengambilan ? $peminjaman->tanggal_pengambilan->format('d/m/Y H:i') : '-' }}
            </div>
            <div class="text-[10px] text-sky-600 font-medium">{{ $peminjaman->tanggal_pengambilan ? 'Alat Diserahkan' : 'Belum Diambil' }}</div>
          </div>

          <div class="p-3 rounded-xl {{ $peminjaman->tanggal_pengembalian ? 'bg-emerald-50/60 border border-emerald-100' : 'bg-slate-50 border border-slate-100 opacity-60' }}">
            <div class="text-[10px] font-bold text-slate-400 uppercase">4. Pengembalian</div>
            <div class="text-xs font-semibold text-slate-800 mt-1">
              {{ $peminjaman->tanggal_pengembalian ? $peminjaman->tanggal_pengembalian->format('d/m/Y H:i') : '-' }}
            </div>
            <div class="text-[10px] text-emerald-600 font-medium">{{ $peminjaman->tanggal_pengembalian ? 'Alat Diterima Lab' : 'Belum Kembali' }}</div>
          </div>
        </div>

        @if($peminjaman->catatan_petugas)
          <div class="mt-4 p-3.5 rounded-xl border border-amber-200 bg-amber-50 text-xs text-amber-900">
            <span class="font-bold">Catatan Petugas Laboratorium:</span>
            <p class="mt-0.5 text-amber-800">{{ $peminjaman->catatan_petugas }}</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal Tolak jika diperlukan di detail -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-rose-100">
    <h3 class="text-lg font-bold text-slate-900 mb-2">Alasan Penolakan Pengajuan</h3>
    <form action="{{ route('peminjaman.tolak', $peminjaman->id) }}" method="POST">
      @csrf
      <textarea name="alasan" rows="3" required placeholder="Tuliskan alasan penolakan..." class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-100 mb-4"></textarea>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="btn btn-secondary btn-sm">Batal</button>
        <button type="submit" class="btn btn-danger btn-sm">Tolak Pengajuan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openRejectModal(id) {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }
</script>
@endpush
@endsection
