@extends('layouts.app')

@section('title', 'Dashboard Petugas Laboran')

@section('content')
<div class="space-y-8">
  <!-- Banner Laboran -->
  <div class="rounded-3xl bg-gradient-to-r from-sky-700 via-indigo-600 to-pink-600 p-8 text-white shadow-xl shadow-indigo-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-semibold mb-3">
        <span class="w-2 h-2 rounded-full bg-sky-300"></span>
        Operasional Laboratorium
      </div>
      <h1 class="text-3xl font-extrabold tracking-tight text-white mb-2">Dashboard Petugas Laboran</h1>
      <p class="text-sky-100 text-sm max-w-xl">
        Periksa pengajuan mahasiswa, validasi ketersediaan alat, proses serah terima barang, dan kelola pengembalian unit secara real-time.
      </p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('peminjaman.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-indigo-700 font-bold text-sm shadow-md hover:bg-slate-50 transition-all hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Semua Data Transaksi
      </a>
    </div>
  </div>

  <!-- Metric Stats Cards -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="stat-card">
      <div class="stat-icon bg-amber-50 text-amber-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Menunggu Persetujuan</div>
        <div class="text-2xl font-extrabold text-amber-600">{{ $countMenunggu }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-purple-50 text-purple-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Siap Diserahkan</div>
        <div class="text-2xl font-extrabold text-purple-600">{{ $countDisetujui }}</div>
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
        <div class="text-2xl font-extrabold text-sky-600">{{ $countDipinjam }}</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon bg-rose-50 text-rose-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>
      <div>
        <div class="text-xs uppercase font-bold tracking-wider text-slate-500">Jatuh Tempo / Kembali</div>
        <div class="text-2xl font-extrabold text-rose-600">{{ $countHarusKembali }}</div>
      </div>
    </div>
  </div>

  <!-- BAGIAN 1: Pengajuan Menunggu Persetujuan (Aksi: Setujui / Tolak) -->
  <div class="card p-6 bg-white border-amber-200 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <span class="flex h-3 w-3 rounded-full bg-amber-500"></span>
        <h2 class="text-lg font-bold text-slate-900">Pengajuan Baru Menunggu Persetujuan</h2>
      </div>
      <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-bold text-xs">
        {{ $pengajuanMenunggu->count() }} Permohonan
      </span>
    </div>

    @if($pengajuanMenunggu->isEmpty())
      <div class="py-8 text-center text-slate-400 bg-amber-50/30 rounded-xl border border-dashed border-amber-200">
        Tidak ada pengajuan yang sedang menunggu verifikasi saat ini.
      </div>
    @else
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID / Waktu</th>
              <th>Peminjam & NIM</th>
              <th>Alat Diajukan</th>
              <th>Jadwal & Durasi</th>
              <th>Jumlah Unit</th>
              <th>Keperluan</th>
              <th class="text-right">Aksi Petugas</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pengajuanMenunggu as $p)
              <tr>
                <td>
                  <span class="font-mono font-bold text-xs text-pink-700">#{{ $p->id }}</span>
                  <div class="text-xs text-slate-400">{{ $p->created_at->format('d/m/Y H:i') }}</div>
                </td>
                <td>
                  <div class="font-semibold text-slate-900">{{ $p->nama_peminjam }}</div>
                  <div class="text-xs text-slate-500 font-mono">NIM: {{ $p->nim }}</div>
                </td>
                <td>
                  <div class="font-bold text-slate-900">{{ $p->alat->nama }}</div>
                  <div class="text-xs text-slate-500 font-mono">{{ $p->alat->kode }} (Stok Lab: {{ $p->alat->stok }})</div>
                </td>
                <td>
                  <div class="text-xs font-semibold text-slate-800">{{ $p->tanggal_pinjam->format('d M Y') }}</div>
                  <div class="text-xs text-slate-500">s.d. {{ $p->tanggal_selesai->format('d M Y') }} ({{ $p->durasi }} hari)</div>
                </td>
                <td>
                  <span class="font-extrabold text-pink-700 bg-pink-50 px-2 py-0.5 rounded-md text-xs border border-pink-100">
                    {{ $p->jumlah }} unit
                  </span>
                </td>
                <td>
                  <span class="text-xs text-slate-600 block max-w-xs truncate" title="{{ $p->keperluan }}">
                    {{ $p->keperluan }}
                  </span>
                </td>
                <td class="text-right">
                  <div class="flex items-center justify-end gap-2">
                    <!-- Form Setujui -->
                    <form action="{{ route('peminjaman.setujui', $p->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan peminjaman #{{ $p->id }}?');">
                      @csrf
                      <button type="submit" class="btn btn-success btn-sm" title="Setujui Pengajuan">
                        Setujui
                      </button>
                    </form>

                    <!-- Form Tolak (Buka Modal / Prompt) -->
                    <button type="button"
                            onclick="openRejectModal({{ $p->id }}, '{{ $p->nama_peminjam }}', '{{ $p->alat->nama }}')"
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

  <!-- BAGIAN 2: Pengajuan Disetujui (Aksi: Serahkan Alat / Dipinjam) -->
  <div class="card p-6 bg-white border-purple-200 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <span class="flex h-3 w-3 rounded-full bg-purple-500"></span>
        <h2 class="text-lg font-bold text-slate-900">Alat Siap Diambil / Diserahkan ke Mahasiswa</h2>
      </div>
      <span class="px-2.5 py-1 rounded-full bg-purple-100 text-purple-800 font-bold text-xs">
        {{ $siapDiambil->count() }} Siap Serah
      </span>
    </div>

    @if($siapDiambil->isEmpty())
      <div class="py-6 text-center text-slate-400 bg-purple-50/20 rounded-xl border border-dashed border-purple-100">
        Tidak ada pengajuan yang berstatus disetujui untuk serah terima saat ini.
      </div>
    @else
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID & Approval</th>
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
                  <span class="font-mono font-bold text-xs text-purple-700">#{{ $item->id }}</span>
                  <div class="text-[11px] text-slate-400">Disetujui: {{ $item->tanggal_persetujuan ? $item->tanggal_persetujuan->format('d/m/Y H:i') : '-' }}</div>
                </td>
                <td>
                  <div class="font-semibold text-slate-900">{{ $item->nama_peminjam }}</div>
                  <div class="text-xs text-slate-500 font-mono">NIM: {{ $item->nim }}</div>
                </td>
                <td>
                  <div class="font-bold text-slate-900">{{ $item->alat->nama }}</div>
                  <div class="text-xs text-slate-500">Lokasi: {{ $item->alat->lokasi }}</div>
                </td>
                <td>
                  <span class="font-bold text-purple-700">{{ $item->jumlah }} unit</span>
                </td>
                <td>
                  <div class="text-xs font-semibold">{{ $item->tanggal_pinjam->format('d M') }} - {{ $item->tanggal_selesai->format('d M Y') }}</div>
                  <div class="text-xs text-slate-500">{{ $item->durasi }} hari</div>
                </td>
                <td class="text-right">
                  <form action="{{ route('peminjaman.serahkan', $item->id) }}" method="POST" onsubmit="return confirm('Pastikan alat fisik telah diserahkan dalam keadaan baik kepada peminjam {{ $item->nama_peminjam }}. Lanjutkan?');">
                    @csrf
                    <button type="submit" class="btn btn-sm bg-gradient-to-r from-purple-600 to-indigo-600 text-white hover:from-purple-700 hover:to-indigo-700">
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

  <!-- BAGIAN 3: Alat Sedang Dipinjam & Jatuh Tempo (Aksi: Kembalikan Alat) -->
  <div class="card p-6 bg-white border-sky-200 shadow-sm">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <span class="flex h-3 w-3 rounded-full bg-sky-500"></span>
        <h2 class="text-lg font-bold text-slate-900">Alat Sedang Dipinjam Mahasiswa</h2>
      </div>
      <span class="px-2.5 py-1 rounded-full bg-sky-100 text-sky-800 font-bold text-xs">
        {{ $sedangDipinjam->count() }} Sedang Dipinjam
      </span>
    </div>

    @if($sedangDipinjam->isEmpty())
      <div class="py-6 text-center text-slate-400 bg-sky-50/20 rounded-xl border border-dashed border-sky-100">
        Tidak ada alat laboratorium yang sedang dipinjam saat ini.
      </div>
    @else
      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID & Tgl Ambil</th>
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
              <tr class="{{ $isDue ? 'bg-rose-50/30' : '' }}">
                <td>
                  <span class="font-mono font-bold text-xs text-sky-700">#{{ $item->id }}</span>
                  <div class="text-[11px] text-slate-400">Diambil: {{ $item->tanggal_pengambilan ? $item->tanggal_pengambilan->format('d/m/Y') : '-' }}</div>
                </td>
                <td>
                  <div class="font-semibold text-slate-900">{{ $item->nama_peminjam }}</div>
                  <div class="text-xs text-slate-500 font-mono">NIM: {{ $item->nim }}</div>
                </td>
                <td>
                  <div class="font-bold text-slate-900">{{ $item->alat->nama }}</div>
                  <div class="text-xs text-slate-500 font-mono">{{ $item->alat->kode }}</div>
                </td>
                <td>
                  <span class="font-bold text-slate-800">{{ $item->jumlah }} unit</span>
                </td>
                <td>
                  <div class="text-xs font-bold {{ $isDue ? 'text-rose-600' : 'text-slate-800' }}">
                    {{ $item->tanggal_selesai->format('d M Y') }}
                  </div>
                  @if($isDue)
                    <span class="inline-block text-[10px] uppercase font-extrabold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">
                      Segera / Terlambat
                    </span>
                  @else
                    <span class="text-xs text-slate-400">Masih berlangsung</span>
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
  <div class="card p-6 bg-white">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-base font-bold text-slate-900">Riwayat Transaksi Terakhir (Selesai / Ditolak)</h3>
        <p class="text-xs text-slate-500">Arsip transaksi yang baru saja diselesaikan</p>
      </div>
      <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm">
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
            <th>Durasi & Qty</th>
            <th>Status Akhir</th>
            <th>Catatan Petugas</th>
          </tr>
        </thead>
        <tbody>
          @forelse($riwayatTerbaru as $item)
            <tr>
              <td class="font-mono text-xs font-bold text-slate-600">#{{ $item->id }}</td>
              <td>
                <div class="font-semibold text-slate-900">{{ $item->nama_peminjam }}</div>
                <div class="text-xs text-slate-500">NIM: {{ $item->nim }}</div>
              </td>
              <td>{{ $item->alat->nama }}</td>
              <td>{{ $item->jumlah }} unit ({{ $item->durasi }} hari)</td>
              <td><x-badge :status="$item->status" /></td>
              <td class="text-xs text-slate-500 max-w-xs truncate">{{ $item->catatan_petugas ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-6 text-slate-400">Belum ada riwayat transaksi yang selesai.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Penolakan Pengajuan -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-rose-100">
    <div class="flex items-center gap-3 mb-4 text-rose-600">
      <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>
      <div>
        <h3 class="text-lg font-bold text-slate-900">Tolak Pengajuan Peminjaman</h3>
        <p class="text-xs text-slate-500" id="rejectSubtitle">Pengajuan #</p>
      </div>
    </div>

    <form id="rejectForm" method="POST" action="">
      @csrf
      <div class="mb-4">
        <label for="alasan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
          Alasan Penolakan <span class="text-rose-500">*</span>
        </label>
        <textarea id="alasan"
                  name="alasan"
                  rows="3"
                  required
                  placeholder="Contoh: Alat sedang dalam masa kalibrasi berkala / pemeliharaan."
                  class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-100 transition-all"></textarea>
        <p class="mt-1 text-[11px] text-slate-400">Pesan ini akan terlihat oleh peminjam di riwayat pengajuannya.</p>
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
