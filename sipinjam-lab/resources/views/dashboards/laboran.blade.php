@extends('layouts.app')

@section('title', 'Dashboard Petugas Laboran')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#e5e7eb]">
    <div>
      <h1 class="text-xl font-bold text-[#202938]">Dashboard Operasional Laboratorium</h1>
      <p class="text-xs text-[#64748b] mt-0.5">Fokus pada antrean verifikasi pengajuan, serah terima alat fisik, dan pemrosesan pengembalian.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
        </svg>
        <span>Seluruh Riwayat Transaksi</span>
      </a>
    </div>
  </div>

  <!-- Operational KPI Metrics (Compact) -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="kpi-card">
      <div class="kpi-icon bg-amber-50 text-amber-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Menunggu Persetujuan</div>
        <div class="kpi-value text-amber-600">{{ $countMenunggu }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-blue-50 text-blue-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Siap Diserahkan</div>
        <div class="kpi-value text-blue-600">{{ $countDisetujui }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-sky-50 text-sky-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Sedang Dipinjam</div>
        <div class="kpi-value text-slate-800">{{ $countDipinjam }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-red-50 text-red-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Jatuh Tempo Segera</div>
        <div class="kpi-value text-red-600">{{ $countHarusKembali }}</div>
      </div>
    </div>
  </div>

  <!-- OPERASIONAL 1: Antrean Pengajuan Menunggu Persetujuan -->
  <div class="card overflow-hidden">
    <div class="card-header bg-[#f8fafc]">
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
        <h3 class="card-title text-sm">Antrean Pengajuan Menunggu Persetujuan</h3>
        <span class="badge badge-warning text-[10px]">{{ $pengajuanMenunggu->count() }} Permohonan</span>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="enterprise-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Peminjam & NIM</th>
            <th>Alat Laboratorium</th>
            <th>Jadwal Peminjaman</th>
            <th>Qty</th>
            <th>Keperluan</th>
            <th class="text-right">Aksi Operasional</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pengajuanMenunggu as $p)
            <tr>
              <td class="font-mono text-xs font-semibold text-slate-700">#{{ $p->id }}</td>
              <td>
                <div class="font-medium text-[#202938]">{{ $p->nama_peminjam }}</div>
                <div class="text-[11px] text-[#64748b] font-mono">NIM: {{ $p->nim }}</div>
              </td>
              <td>
                <div class="font-medium text-[#202938]">{{ $p->alat->nama }}</div>
                <div class="text-[11px] text-[#64748b] font-mono">{{ $p->alat->kode }} (Stok: {{ $p->alat->stok }})</div>
              </td>
              <td class="text-xs">
                <div class="font-medium">{{ $p->tanggal_pinjam->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</div>
                <div class="text-[11px] text-[#64748b]">{{ $p->durasi }} hari kalender</div>
              </td>
              <td>
                <span class="font-bold text-xs bg-slate-100 text-slate-800 px-2 py-0.5 rounded">
                  {{ $p->jumlah }} Unit
                </span>
              </td>
              <td class="max-w-[200px]">
                <span class="text-xs text-slate-600 truncate block" title="{{ $p->keperluan }}">{{ $p->keperluan }}</span>
              </td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-1.5">
                  <form action="{{ route('peminjaman.setujui', $p->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan peminjaman #{{ $p->id }}?');" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                      Setujui
                    </button>
                  </form>
                  <button type="button"
                          onclick="openRejectModal({{ $p->id }}, '{{ $p->nama_peminjam }}', '{{ $p->alat->nama }}')"
                          class="btn btn-danger btn-sm">
                    Tolak
                  </button>
                  <a href="{{ route('peminjaman.detail', $p->id) }}" class="btn btn-secondary btn-sm">
                    Detail
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-6 text-slate-400 text-xs">
                Tidak ada permohonan yang sedang menunggu verifikasi.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- OPERASIONAL 2: Alat Siap Diambil / Diserahkan -->
  <div class="card overflow-hidden">
    <div class="card-header bg-[#f8fafc]">
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
        <h3 class="card-title text-sm">Alat Siap Diserahkan ke Mahasiswa</h3>
        <span class="badge badge-info text-[10px]">{{ $siapDiambil->count() }} Siap Serah</span>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="enterprise-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Peminjam</th>
            <th>Alat & Lokasi</th>
            <th>Jumlah</th>
            <th>Jadwal Peminjaman</th>
            <th class="text-right">Aksi Serah Terima</th>
          </tr>
        </thead>
        <tbody>
          @forelse($siapDiambil as $item)
            <tr>
              <td class="font-mono text-xs font-semibold text-slate-700">#{{ $item->id }}</td>
              <td>
                <div class="font-medium text-[#202938]">{{ $item->nama_peminjam }}</div>
                <div class="text-[11px] text-[#64748b] font-mono">{{ $item->nim }}</div>
              </td>
              <td>
                <div class="font-medium text-[#202938]">{{ $item->alat->nama }}</div>
                <div class="text-[11px] text-[#64748b]">Lokasi: {{ $item->alat->lokasi }}</div>
              </td>
              <td class="font-semibold text-xs">{{ $item->jumlah }} Unit</td>
              <td class="text-xs">
                <div>{{ $item->tanggal_pinjam->format('d/m/Y') }} s.d. {{ $item->tanggal_selesai->format('d/m/Y') }}</div>
              </td>
              <td class="text-right">
                <form action="{{ route('peminjaman.serahkan', $item->id) }}" method="POST" onsubmit="return confirm('Serahkan unit alat fisik kepada mahasiswa {{ $item->nama_peminjam }}? Status akan berubah menjadi Dipinjam.');" class="inline">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-sm">
                    Serahkan Alat
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-5 text-slate-400 text-xs">
                Tidak ada permohonan yang menunggu pengambilan saat ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- OPERASIONAL 3: Alat Sedang Dipinjam & Pengembalian -->
  <div class="card overflow-hidden">
    <div class="card-header bg-[#f8fafc]">
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-slate-600"></span>
        <h3 class="card-title text-sm">Alat Sedang Dipinjam & Batas Waktu Pengembalian</h3>
        <span class="badge badge-neutral text-[10px]">{{ $sedangDipinjam->count() }} Unit Aktif</span>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="enterprise-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Peminjam</th>
            <th>Alat</th>
            <th>Jumlah</th>
            <th>Batas Pengembalian</th>
            <th>Status Jatuh Tempo</th>
            <th class="text-right">Aksi Terima Kembali</th>
          </tr>
        </thead>
        <tbody>
          @forelse($sedangDipinjam as $item)
            @php
              $isDue = $item->tanggal_selesai->isPast() || $item->tanggal_selesai->isToday();
            @endphp
            <tr class="{{ $isDue ? 'bg-amber-50/40' : '' }}">
              <td class="font-mono text-xs font-semibold text-slate-700">#{{ $item->id }}</td>
              <td>
                <div class="font-medium text-[#202938]">{{ $item->nama_peminjam }}</div>
                <div class="text-[11px] text-[#64748b] font-mono">{{ $item->nim }}</div>
              </td>
              <td>
                <div class="font-medium text-[#202938]">{{ $item->alat->nama }}</div>
                <div class="text-[11px] text-[#64748b] font-mono">{{ $item->alat->kode }}</div>
              </td>
              <td class="font-semibold text-xs">{{ $item->jumlah }} Unit</td>
              <td class="text-xs font-semibold {{ $isDue ? 'text-red-700' : 'text-slate-800' }}">
                {{ $item->tanggal_selesai->format('d/m/Y') }}
              </td>
              <td>
                @if($isDue)
                  <span class="badge badge-danger text-[10px]">Hari Ini / Terlambat</span>
                @else
                  <span class="badge badge-neutral text-[10px]">Sedang Berjalan</span>
                @endif
              </td>
              <td class="text-right">
                <form action="{{ route('peminjaman.kembalikan', $item->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa alat telah diterima kembali di laboratorium?');" class="inline">
                  @csrf
                  <button type="submit" class="btn btn-success btn-sm">
                    Alat Dikembalikan
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-5 text-slate-400 text-xs">
                Tidak ada alat laboratorium yang sedang dipinjam saat ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Penolakan Interaktif -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-900/50 hidden items-center justify-center p-4">
  <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl border border-[#e5e7eb]">
    <div class="mb-4">
      <h3 class="text-base font-bold text-[#202938]">Alasan Penolakan Pengajuan</h3>
      <p class="text-xs text-[#64748b]" id="rejectSubtitle">Pengajuan #</p>
    </div>

    <form id="rejectForm" method="POST" action="">
      @csrf
      <div class="form-group mb-4">
        <label for="alasan" class="form-label">
          Keterangan Alasan Penolakan <span class="text-red-500">*</span>
        </label>
        <textarea id="alasan"
                  name="alasan"
                  rows="3"
                  required
                  placeholder="Contoh: Alat sedang dalam pemeliharaan berkala / kuota jadwal bentrok."
                  class="form-input"></textarea>
      </div>

      <div class="flex justify-end gap-2 pt-2 border-t border-[#e5e7eb]">
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
