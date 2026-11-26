@extends('layouts.app')

@section('title', 'Rekap & Laporan Transaksi - SIPINJAM-LAB')

@section('content')
<div class="space-y-6">
  <!-- Print-only Header -->
  <div class="print-only border-b border-slate-300 pb-4 mb-4">
    <div class="text-lg font-extrabold text-slate-900">SIPINJAM-LAB</div>
    <div class="text-base font-bold text-slate-800">Laporan Rekapitulasi Transaksi Peminjaman Alat Laboratorium</div>
    <div class="text-xs text-slate-600 mt-1">
      Periode: {{ $tanggalAwal !== '' ? $tanggalAwal : 'Awal' }} s.d. {{ $tanggalAkhir !== '' ? $tanggalAkhir : 'Sekarang' }}
      &bull; Dicetak pada: {{ now()->format('d M Y, H:i') }}
    </div>
  </div>

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 no-print">
    <div>
      <h1 class="text-2xl font-bold text-heading">Rekap &amp; Laporan Transaksi</h1>
      <p class="text-xs text-muted mt-1">Ringkasan statistik peminjaman alat laboratorium berdasarkan data nyata database</p>
    </div>

    <button type="button" id="printReportBtn" class="btn btn-sm self-start sm:self-auto">
      <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
      </svg>
      Cetak Laporan
    </button>
  </div>

  <!-- Filter Form -->
  <div class="surface-card p-5 no-print">
    @if ($errors->any())
      <div class="mb-4 alert alert-error text-xs">
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
      <div>
        <label for="tanggal_awal" class="field-label block mb-1">Tanggal Awal</label>
        <input type="date" id="tanggal_awal" name="tanggal_awal" value="{{ $tanggalAwal }}" class="field-input">
      </div>

      <div>
        <label for="tanggal_akhir" class="field-label block mb-1">Tanggal Akhir</label>
        <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="field-input">
      </div>

      <div>
        <label for="status" class="field-label block mb-1">Status</label>
        <select id="status" name="status" class="field-input">
          <option value="">Semua Status</option>
          @foreach($statusList as $st)
            <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label for="alat_id" class="field-label block mb-1">Alat</label>
        <select id="alat_id" name="alat_id" class="field-input">
          <option value="">Semua Alat</option>
          @foreach($daftarAlat as $a)
            <option value="{{ $a->id }}" {{ $alatId === $a->id ? 'selected' : '' }}>{{ $a->kode }} - {{ $a->nama }}</option>
          @endforeach
        </select>
      </div>

      <div class="flex gap-2">
        <button type="submit" class="btn btn-sm w-full">Terapkan</button>
        @if($tanggalAwal !== '' || $tanggalAkhir !== '' || $status !== '' || $alatId)
          <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        @endif
      </div>
    </form>
  </div>

  <!-- Ringkasan Statistik -->
  <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
    <div class="surface-card p-4">
      <div class="text-[11px] font-bold uppercase text-muted">Total</div>
      <div class="text-xl font-extrabold text-heading mt-1">{{ $ringkasan['total'] }}</div>
    </div>
    <div class="surface-card p-4">
      <div class="text-[11px] font-bold uppercase text-amber-500">Menunggu</div>
      <div class="text-xl font-extrabold text-amber-500 mt-1">{{ $ringkasan['menunggu'] }}</div>
    </div>
    <div class="surface-card p-4">
      <div class="text-[11px] font-bold uppercase text-purple-500">Disetujui</div>
      <div class="text-xl font-extrabold text-purple-500 mt-1">{{ $ringkasan['disetujui'] }}</div>
    </div>
    <div class="surface-card p-4">
      <div class="text-[11px] font-bold uppercase text-accent">Dipinjam</div>
      <div class="text-xl font-extrabold text-accent mt-1">{{ $ringkasan['dipinjam'] }}</div>
    </div>
    <div class="surface-card p-4">
      <div class="text-[11px] font-bold uppercase text-emerald-500">Selesai</div>
      <div class="text-xl font-extrabold text-emerald-500 mt-1">{{ $ringkasan['dikembalikan'] }}</div>
    </div>
    <div class="surface-card p-4">
      <div class="text-[11px] font-bold uppercase text-red-500">Ditolak</div>
      <div class="text-xl font-extrabold text-red-500 mt-1">{{ $ringkasan['ditolak'] }}</div>
    </div>
    <div class="surface-card p-4">
      <div class="text-[11px] font-bold uppercase text-muted">Dibatalkan</div>
      <div class="text-xl font-extrabold text-muted mt-1">{{ $ringkasan['dibatalkan'] }}</div>
    </div>
  </div>

  <!-- Alat Paling Sering Dipinjam -->
  <div class="surface-card p-5 space-y-3">
    <h2 class="text-base font-bold text-heading">Alat Paling Sering Dipinjam</h2>
    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Nama Alat</th>
            <th>Kategori</th>
            <th>Total Pengajuan</th>
            <th>Total Unit Dipinjam</th>
          </tr>
        </thead>
        <tbody>
          @forelse($alatTerpopuler as $pop)
            <tr>
              <td><span class="code-label">{{ $pop->alat?->kode ?? '-' }}</span></td>
              <td class="font-semibold text-heading">{{ $pop->alat?->nama ?? '-' }}</td>
              <td class="text-muted">{{ $pop->alat?->kategori ?? '-' }}</td>
              <td class="font-bold text-heading">{{ $pop->total_transaksi }}</td>
              <td class="font-bold text-accent">{{ $pop->total_unit }} unit</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-6 text-muted">Belum ada data peminjaman pada periode ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tabel Transaksi -->
  <div class="surface-card p-5 space-y-3">
    <h2 class="text-base font-bold text-heading">Daftar Transaksi ({{ $daftarTransaksi->count() }})</h2>
    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Peminjam &amp; NIM</th>
            <th>Alat</th>
            <th>Periode Pinjam</th>
            <th>Jumlah</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse($daftarTransaksi as $item)
            <tr>
              <td class="font-mono text-xs font-bold">#{{ $item->id }}</td>
              <td>
                <div class="font-semibold text-heading">{{ $item->nama_peminjam }}</div>
                <div class="text-xs text-muted font-mono">{{ $item->nim }}</div>
              </td>
              <td>
                <div class="font-medium text-heading">{{ $item->alat?->nama ?? '-' }}</div>
                <div class="text-xs text-muted font-mono">{{ $item->alat?->kode ?? '-' }}</div>
              </td>
              <td class="text-xs text-muted">
                {{ $item->tanggal_pinjam->format('d/m/Y') }} - {{ $item->tanggal_selesai->format('d/m/Y') }} ({{ $item->durasi }} hari)
              </td>
              <td class="font-bold text-heading">{{ $item->jumlah }} unit</td>
              <td><x-badge :status="$item->status" /></td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-8 text-muted">Tidak ada transaksi sesuai filter.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
