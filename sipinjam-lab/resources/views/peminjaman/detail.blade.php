@extends('layouts.app')

@section('title', 'Detail Pengajuan #' . $peminjaman->id)

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
  <!-- Navigasi Kembali -->
  <div class="flex flex-wrap items-center justify-between gap-2">
    <a href="{{ url()->previous(Auth::check() && Auth::user()->isPeminjam() ? route('peminjam.riwayat') : route('peminjaman.index')) }}"
       class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider link-accent">
      &larr; Kembali
    </a>
    <span class="text-xs text-muted">Diajukan pada: {{ $peminjaman->created_at->format('d M Y, H:i') }}</span>
  </div>

  <!-- Kartu Detail Utama -->
  <div class="surface-card overflow-hidden">
    <!-- Header Status -->
    <div class="card-header-soft p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex flex-wrap items-center gap-2 mb-2">
          <span class="code-label">
            Transaksi #{{ $peminjaman->id }}
          </span>
          <x-badge :status="$peminjaman->status" />
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-heading">
          {{ $peminjaman->alat->nama }}
        </h1>
        <p class="text-xs text-muted mt-1">Kode Inventaris: {{ $peminjaman->alat->kode }} &bull; Kategori: {{ $peminjaman->alat->kategori }}</p>
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
              <button type="submit" class="btn btn-sm">Serahkan Alat</button>
            </form>
          @elseif($peminjaman->isDipinjam())
            <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian alat ke laboratorium?');">
              @csrf
              <button type="submit" class="btn btn-success btn-sm">Kembalikan Alat</button>
            </form>
          @endif
        </div>
      @elseif(Auth::check() && Auth::user()->isPeminjam() && $peminjaman->isMenunggu())
        @can('update', $peminjaman)
          <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('peminjam.riwayat.edit', $peminjaman->id) }}" class="btn btn-sm">
              Ubah Pengajuan
            </a>
            <form action="{{ route('peminjam.riwayat.batalkan', $peminjaman->id) }}"
                  method="POST"
                  onsubmit="return confirm('Batalkan pengajuan ini?');">
              @csrf
              @method('PATCH')
              <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
            </form>
          </div>
        @endcan
      @endif
    </div>

    <div class="p-6 sm:p-8 space-y-6">
      <!-- Grid Informasi 2 Kolom -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Kolom Data Peminjam -->
        <div class="surface-inset p-5 space-y-3">
          <h2 class="text-xs font-bold uppercase tracking-wider text-accent mb-2">Identitas Peminjam</h2>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted">Nama Lengkap</span>
            <span class="font-bold text-heading text-right">{{ $peminjaman->nama_peminjam }}</span>
          </div>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted">NIM</span>
            <span class="font-bold text-heading font-mono">{{ $peminjaman->nim }}</span>
          </div>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted">Status Akun</span>
            <span class="text-emerald-600 font-semibold">{{ $peminjaman->user_id ? 'Terdaftar (Terverifikasi)' : 'Tamu' }}</span>
          </div>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted shrink-0">Keperluan</span>
            <span class="text-heading font-medium text-right max-w-xs">{{ $peminjaman->keperluan }}</span>
          </div>
        </div>

        <!-- Kolom Detail Peminjaman & Jadwal -->
        <div class="surface-inset p-5 space-y-3">
          <h2 class="text-xs font-bold uppercase tracking-wider text-accent mb-2">Jadwal &amp; Spesifikasi</h2>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted">Jumlah Unit</span>
            <span class="font-bold text-accent text-sm">{{ $peminjaman->jumlah }} Unit</span>
          </div>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted">Tanggal Pinjam</span>
            <span class="font-bold text-heading">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</span>
          </div>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted">Tanggal Selesai</span>
            <span class="font-bold text-heading">{{ $peminjaman->tanggal_selesai->format('d F Y') }}</span>
          </div>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted">Durasi Peminjaman</span>
            <span class="font-bold text-heading">{{ $peminjaman->durasi }} Hari Kalender</span>
          </div>
          <div class="flex justify-between gap-3 text-xs">
            <span class="text-muted">Lokasi Lab</span>
            <span class="font-medium text-heading text-right">{{ $peminjaman->alat->lokasi }}</span>
          </div>
        </div>
      </div>

      <!-- Jejak Transaksi (Audit Trail / Timestamps) -->
      <div class="surface-inset p-5">
        <h2 class="text-xs font-bold uppercase tracking-wider text-heading mb-4">Kronologi &amp; Jejak Transaksi</h2>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
          <div class="p-3 rounded-xl bg-sky-500/10 border border-sky-500/25">
            <div class="text-[10px] font-bold text-muted uppercase">1. Pengajuan</div>
            <div class="text-xs font-semibold text-heading mt-1">{{ $peminjaman->created_at->format('d/m/Y H:i') }}</div>
            <div class="text-[10px] text-accent font-medium">Tersimpan</div>
          </div>

          <div class="p-3 rounded-xl {{ $peminjaman->tanggal_persetujuan ? 'bg-purple-500/10 border border-purple-500/25' : 'border divider-subtle opacity-60' }}">
            <div class="text-[10px] font-bold text-muted uppercase">2. Persetujuan</div>
            <div class="text-xs font-semibold text-heading mt-1">
              {{ $peminjaman->tanggal_persetujuan ? $peminjaman->tanggal_persetujuan->format('d/m/Y H:i') : '-' }}
            </div>
            <div class="text-[10px] text-purple-500 font-medium">{{ $peminjaman->tanggal_persetujuan ? 'Disetujui Petugas' : 'Belum Diproses' }}</div>
          </div>

          <div class="p-3 rounded-xl {{ $peminjaman->tanggal_pengambilan ? 'bg-sky-500/10 border border-sky-500/25' : 'border divider-subtle opacity-60' }}">
            <div class="text-[10px] font-bold text-muted uppercase">3. Pengambilan</div>
            <div class="text-xs font-semibold text-heading mt-1">
              {{ $peminjaman->tanggal_pengambilan ? $peminjaman->tanggal_pengambilan->format('d/m/Y H:i') : '-' }}
            </div>
            <div class="text-[10px] text-accent font-medium">{{ $peminjaman->tanggal_pengambilan ? 'Alat Diserahkan' : 'Belum Diambil' }}</div>
          </div>

          <div class="p-3 rounded-xl {{ $peminjaman->tanggal_pengembalian ? 'bg-emerald-500/10 border border-emerald-500/25' : 'border divider-subtle opacity-60' }}">
            <div class="text-[10px] font-bold text-muted uppercase">4. Pengembalian</div>
            <div class="text-xs font-semibold text-heading mt-1">
              {{ $peminjaman->tanggal_pengembalian ? $peminjaman->tanggal_pengembalian->format('d/m/Y H:i') : '-' }}
            </div>
            <div class="text-[10px] text-emerald-500 font-medium">{{ $peminjaman->tanggal_pengembalian ? 'Alat Diterima Lab' : 'Belum Kembali' }}</div>
          </div>
        </div>

        @if($peminjaman->catatan_petugas)
          <div class="mt-4 p-3.5 rounded-xl border border-amber-400/40 bg-amber-500/10 text-xs text-heading">
            <span class="font-bold">Catatan Petugas Laboratorium:</span>
            <p class="mt-0.5 text-muted">{{ $peminjaman->catatan_petugas }}</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-950/65 backdrop-blur-sm hidden items-center justify-center p-4">
  <div class="modal-panel max-w-md w-full p-6">
    <h3 class="text-lg font-bold text-heading mb-2">Alasan Penolakan Pengajuan</h3>
    <form action="{{ route('peminjaman.tolak', $peminjaman->id) }}" method="POST">
      @csrf
      <textarea name="alasan" rows="3" required placeholder="Tuliskan alasan penolakan..." class="field-input min-h-[92px] mb-4"></textarea>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeRejectModal()" class="btn btn-secondary btn-sm">Batal</button>
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

  function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
</script>
@endpush
@endsection
