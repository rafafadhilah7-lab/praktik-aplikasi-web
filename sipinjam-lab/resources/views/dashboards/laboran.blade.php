@extends('layouts.app')

@section('title', 'Dashboard Petugas Laboran')

@section('content')
<div class="space-y-8">
  <!-- Banner Laboran -->
  <div class="hero-banner rounded-3xl p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-sky-300/20 text-xs font-semibold text-sky-100 mb-3">
        <span class="w-2 h-2 rounded-full bg-sky-300"></span>
        Operasional Laboratorium
      </div>
      <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-2">Dashboard Petugas Laboran</h1>
      <p class="text-sky-100/90 text-sm max-w-xl">
        Periksa pengajuan mahasiswa, validasi ketersediaan alat, proses serah terima barang, dan kelola pengembalian unit secara real-time.
      </p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('peminjaman.index') }}"
         class="btn shadow-md">
        <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Semua Data Transaksi
      </a>
    </div>
  </div>

  <!-- Metric Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="stat-card">
      <div class="stat-icon bg-amber-500/15 text-amber-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Menunggu Persetujuan</div>
        <div class="text-2xl font-extrabold text-amber-500">{{ $countMenunggu }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-purple-500/15 text-purple-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Siap Diserahkan</div>
        <div class="text-2xl font-extrabold text-purple-500">{{ $countDisetujui }}</div>
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
        <div class="text-2xl font-extrabold text-accent">{{ $countDipinjam }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-red-500/15 text-red-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-muted">Jatuh Tempo / Kembali</div>
        <div class="text-2xl font-extrabold text-red-500">{{ $countHarusKembali }}</div>
      </div>
    </div>
  </div>

  <!-- BAGIAN 1: Pengajuan Menunggu Persetujuan -->
  <div class="surface-card p-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
      <div class="flex items-center gap-3">
        <span class="flex h-3 w-3 rounded-full bg-amber-500"></span>
        <h2 class="text-lg font-bold text-heading">Pengajuan Baru Menunggu Persetujuan</h2>
      </div>
      <span class="badge badge-warning">
        {{ $pengajuanMenunggu->count() }} Permohonan
      </span>
    </div>

    @if($pengajuanMenunggu->isEmpty())
      <div class="py-8 text-center text-muted surface-inset border-dashed">
        Tidak ada pengajuan yang sedang menunggu verifikasi saat ini.
      </div>
    @else
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID / Waktu</th>
              <th>Peminjam &amp; NIM</th>
              <th>Alat Diajukan</th>
              <th>Jadwal &amp; Durasi</th>
              <th>Jumlah Unit</th>
              <th>Keperluan</th>
              <th class="text-right">Aksi Petugas</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pengajuanMenunggu as $p)
              <tr>
                <td>
                  <span class="code-label">#{{ $p->id }}</span>
                  <div class="text-xs text-muted mt-1">{{ $p->created_at->format('d/m/Y H:i') }}</div>
                </td>
                <td>
                  <div class="font-semibold text-heading">{{ $p->nama_peminjam }}</div>
                  <div class="text-xs text-muted font-mono">NIM: {{ $p->nim }}</div>
                </td>
                <td>
                  <div class="font-bold text-heading">{{ $p->alat->nama }}</div>
                  <div class="text-xs text-muted font-mono">{{ $p->alat->kode }} (Stok Lab: {{ $p->alat->stok }})</div>
                </td>
                <td>
                  <div class="text-xs font-semibold text-heading">{{ $p->tanggal_pinjam->format('d M Y') }}</div>
                  <div class="text-xs text-muted">s.d. {{ $p->tanggal_selesai->format('d M Y') }} ({{ $p->durasi }} hari)</div>
                </td>
                <td>
                  <span class="code-label">{{ $p->jumlah }} unit</span>
                </td>
                <td>
                  <span class="text-xs text-muted block max-w-xs truncate" title="{{ $p->keperluan }}">
                    {{ $p->keperluan }}
                  </span>
                </td>
                <td class="text-right">
                  <div class="flex items-center justify-end gap-2">
                    <form action="{{ route('peminjaman.setujui', $p->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan peminjaman #{{ $p->id }}?');">
                      @csrf
                      <button type="submit" class="btn btn-success btn-sm" title="Setujui Pengajuan">
                        Setujui
                      </button>
                    </form>

                    <button type="button"
                            onclick="openRejectModal({{ $p->id }}, {{ \Illuminate\Support\Js::from($p->nama_peminjam) }}, {{ \Illuminate\Support\Js::from($p->alat->nama) }})"
                            class="btn btn-danger btn-sm" title="Tolak Pengajuan">
                      Tolak
                    </button>

                    <a href="{{ route('peminjaman.detail', $p->id) }}" class="btn btn-secondary btn-sm">
                      Detail
                    </a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <!-- BAGIAN 2: Pengajuan Disetujui -->
  <div class="surface-card p-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
      <div class="flex items-center gap-3">
        <span class="flex h-3 w-3 rounded-full bg-purple-500"></span>
        <h2 class="text-lg font-bold text-heading">Alat Siap Diambil / Diserahkan ke Mahasiswa</h2>
      </div>
      <span class="badge badge-purple">
        {{ $siapDiambil->count() }} Siap Serah
      </span>
    </div>

    @if($siapDiambil->isEmpty())
      <div class="py-6 text-center text-muted surface-inset border-dashed">
        Tidak ada pengajuan yang berstatus disetujui untuk serah terima saat ini.
      </div>
    @else
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID &amp; Approval</th>
              <th>Peminjam</th>
              <th>Alat Laboratorium</th>
              <th>Jumlah Unit</th>
              <th>Rentang Waktu</th>
              <th class="text-right">Aksi Serah Terima</th>
            </tr>
          </thead>
          <tbody>
            @foreach($siapDiambil as $item)
              <tr>
                <td>
                  <span class="code-label">#{{ $item->id }}</span>
                  <div class="text-[11px] text-muted mt-1">Disetujui: {{ $item->tanggal_persetujuan ? $item->tanggal_persetujuan->format('d/m/Y H:i') : '-' }}</div>
                </td>
                <td>
                  <div class="font-semibold text-heading">{{ $item->nama_peminjam }}</div>
                  <div class="text-xs text-muted font-mono">NIM: {{ $item->nim }}</div>
                </td>
                <td>
                  <div class="font-bold text-heading">{{ $item->alat->nama }}</div>
                  <div class="text-xs text-muted">Lokasi: {{ $item->alat->lokasi }}</div>
                </td>
                <td>
                  <span class="font-bold text-accent">{{ $item->jumlah }} unit</span>
                </td>
                <td>
                  <div class="text-xs font-semibold text-heading">{{ $item->tanggal_pinjam->format('d M') }} - {{ $item->tanggal_selesai->format('d M Y') }}</div>
                  <div class="text-xs text-muted">{{ $item->durasi }} hari</div>
                </td>
                <td class="text-right">
                  <form action="{{ route('peminjaman.serahkan', $item->id) }}" method="POST" onsubmit="return confirm('Serahkan alat kepada peminjam?');">
                    @csrf
                    <button type="submit" class="btn btn-sm">
                      Serahkan Alat (Mulai Pinjam)
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <!-- BAGIAN 3: Alat Sedang Dipinjam & Jatuh Tempo -->
  <div class="surface-card p-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
      <div class="flex items-center gap-3">
        <span class="flex h-3 w-3 rounded-full bg-sky-500"></span>
        <h2 class="text-lg font-bold text-heading">Alat Sedang Dipinjam Mahasiswa</h2>
      </div>
      <span class="badge badge-info">
        {{ $sedangDipinjam->count() }} Sedang Dipinjam
      </span>
    </div>

    @if($sedangDipinjam->isEmpty())
      <div class="py-6 text-center text-muted surface-inset border-dashed">
        Tidak ada alat laboratorium yang sedang dipinjam saat ini.
      </div>
    @else
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID &amp; Tgl Ambil</th>
              <th>Peminjam</th>
              <th>Alat</th>
              <th>Jumlah Unit</th>
              <th>Batas Pengembalian</th>
              <th class="text-right">Proses Kembali</th>
            </tr>
          </thead>
          <tbody>
            @foreach($sedangDipinjam as $item)
              @php
                $isDue = $item->tanggal_selesai->isPast() || $item->tanggal_selesai->isToday();
              @endphp
              <tr class="{{ $isDue ? 'bg-red-500/5' : '' }}">
                <td>
                  <span class="code-label">#{{ $item->id }}</span>
                  <div class="text-[11px] text-muted mt-1">Diambil: {{ $item->tanggal_pengambilan ? $item->tanggal_pengambilan->format('d/m/Y') : '-' }}</div>
                </td>
                <td>
                  <div class="font-semibold text-heading">{{ $item->nama_peminjam }}</div>
                  <div class="text-xs text-muted font-mono">NIM: {{ $item->nim }}</div>
                </td>
                <td>
                  <div class="font-bold text-heading">{{ $item->alat->nama }}</div>
                  <div class="text-xs text-muted font-mono">{{ $item->alat->kode }}</div>
                </td>
                <td>
                  <span class="font-bold text-heading">{{ $item->jumlah }} unit</span>
                </td>
                <td>
                  <div class="text-xs font-bold {{ $isDue ? 'text-red-500' : 'text-heading' }}">
                    {{ $item->tanggal_selesai->format('d M Y') }}
                  </div>
                  @if($isDue)
                    <span class="badge badge-danger mt-1 text-[10px]">
                      Segera / Terlambat
                    </span>
                  @else
                    <span class="text-xs text-muted">Masih berlangsung</span>
                  @endif
                </td>
                <td class="text-right">
                  <form action="{{ route('peminjaman.kembalikan', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa alat telah diterima kembali di lab dan stok siap dibuka?');">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                      Alat Dikembalikan
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <!-- BAGIAN 4: Riwayat Peminjaman Selesai Terbaru -->
  <div class="surface-card p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
      <div>
        <h3 class="text-base font-bold text-heading">Riwayat Transaksi Terakhir (Selesai / Ditolak)</h3>
        <p class="text-xs text-muted">Arsip transaksi yang baru saja diselesaikan</p>
      </div>
      <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm self-start sm:self-auto">
        Buka Arsip Lengkap
      </a>
    </div>

    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Peminjam</th>
            <th>Alat</th>
            <th>Durasi &amp; Qty</th>
            <th>Status Akhir</th>
            <th>Catatan Petugas</th>
          </tr>
        </thead>
        <tbody>
          @forelse($riwayatTerbaru as $item)
            <tr>
              <td class="font-mono text-xs font-bold text-muted">#{{ $item->id }}</td>
              <td>
                <div class="font-semibold text-heading">{{ $item->nama_peminjam }}</div>
                <div class="text-xs text-muted">NIM: {{ $item->nim }}</div>
              </td>
              <td class="text-heading font-medium">{{ $item->alat->nama }}</td>
              <td class="text-muted">{{ $item->jumlah }} unit ({{ $item->durasi }} hari)</td>
              <td><x-badge :status="$item->status" /></td>
              <td class="text-xs text-muted max-w-xs truncate">{{ $item->catatan_petugas ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-6 text-muted">Belum ada riwayat transaksi yang selesai.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Penolakan Pengajuan -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-950/65 backdrop-blur-sm hidden items-center justify-center p-4">
  <div class="modal-panel max-w-md w-full p-6">
    <div class="flex items-center gap-3 mb-4 text-red-500">
      <div class="w-10 h-10 rounded-xl bg-red-500/15 flex items-center justify-center shrink-0">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>
      <div>
        <h3 class="text-lg font-bold text-heading">Tolak Pengajuan Peminjaman</h3>
        <p class="text-xs text-muted" id="rejectSubtitle">Pengajuan #</p>
      </div>
    </div>

    <form id="rejectForm" method="POST" action="">
      @csrf
      <div class="mb-4">
        <label for="alasan" class="field-label block mb-1.5">
          Alasan Penolakan <span class="field-required">*</span>
        </label>
        <textarea id="alasan"
                  name="alasan"
                  rows="3"
                  required
                  placeholder="Contoh: Alat sedang dalam masa kalibrasi berkala / pemeliharaan."
                  class="field-input min-h-[92px]"></textarea>
        <p class="mt-1 text-[11px] text-muted">Pesan ini akan terlihat oleh peminjam di riwayat pengajuannya.</p>
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeRejectModal()" class="btn btn-secondary btn-sm">
          Batal
        </button>
        <button type="submit" class="btn btn-danger btn-sm">
          Konfirmasi Penolakan
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openRejectModal(id, peminjam, alat) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const subtitle = document.getElementById('rejectSubtitle');

    subtitle.textContent = '#' + id + ' (' + peminjam + ' - ' + alat + ')';
    form.action = '/peminjaman/' + id + '/tolak';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('alasan').focus();
  }

  function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
</script>
@endpush
@endsection
