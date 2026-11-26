@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-heading">Riwayat Peminjaman Saya</h1>
      <p class="text-xs text-muted mt-1">Daftar seluruh riwayat pengajuan dan peminjaman alat laboratorium Anda</p>
    </div>
    <a href="{{ route('alat.beranda') }}" class="btn btn-sm self-start sm:self-auto">
      + Ajukan Peminjaman Baru
    </a>
  </div>

  <!-- Filter Status Tabs -->
  <div class="flex flex-wrap gap-2 border-b divider-subtle pb-3">
    <a href="{{ route('peminjam.riwayat') }}"
       class="filter-tab {{ $status === '' ? 'filter-tab-active' : '' }}">
      Semua Status
    </a>
    @foreach(['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'dipinjam' => 'Dipinjam', 'dikembalikan' => 'Dikembalikan', 'ditolak' => 'Ditolak', 'dibatalkan' => 'Dibatalkan'] as $stKey => $stLabel)
      <a href="{{ route('peminjam.riwayat', ['status' => $stKey]) }}"
         class="filter-tab {{ $status === $stKey ? 'filter-tab-active' : '' }}">
        {{ $stLabel }}
      </a>
    @endforeach
  </div>

  <!-- Tabel Riwayat -->
  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>ID / Tgl Pengajuan</th>
          <th>Nama Alat</th>
          <th>Jadwal Peminjaman</th>
          <th>Jumlah</th>
          <th>Status</th>
          <th>Catatan Petugas</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($daftarRiwayat as $item)
          <tr>
            <td>
              <span class="code-label">#{{ $item->id }}</span>
              <div class="text-xs text-muted mt-1">{{ $item->created_at->format('d M Y') }}</div>
            </td>
            <td>
              <div class="font-bold text-heading">{{ $item->alat->nama }}</div>
              <div class="text-xs text-muted font-mono">{{ $item->alat->kode }} ({{ $item->alat->kategori }})</div>
            </td>
            <td>
              <div class="text-xs font-semibold text-heading">{{ $item->tanggal_pinjam->format('d M Y') }}</div>
              <div class="text-xs text-muted">s.d. {{ $item->tanggal_selesai->format('d M Y') }} ({{ $item->durasi }} hari)</div>
            </td>
            <td>
              <span class="font-bold text-heading">{{ $item->jumlah }} unit</span>
            </td>
            <td>
              <x-badge :status="$item->status" />
            </td>
            <td class="max-w-xs">
              @if($item->catatan_petugas)
                <span class="text-xs text-muted block line-clamp-2" title="{{ $item->catatan_petugas }}">
                  {{ $item->catatan_petugas }}
                </span>
              @else
                <span class="text-xs text-muted">-</span>
              @endif
            </td>
            <td>
              <div class="flex flex-wrap items-center gap-1.5">
                @if($item->isMenunggu())
                  <a href="{{ route('peminjam.riwayat.edit', $item->id) }}" class="btn btn-sm px-2.5 text-xs">
                    Ubah
                  </a>
                  <form action="{{ route('peminjam.riwayat.batalkan', $item->id) }}"
                        method="POST"
                        onsubmit="return confirm('Batalkan pengajuan ini?');"
                        class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-sm px-2.5 text-xs">
                      Batalkan
                    </button>
                  </form>
                @endif
                <a href="{{ route('peminjaman.detail', $item->id) }}" class="btn btn-secondary btn-sm px-2.5 text-xs">
                  Detail
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-10 text-muted">
              Tidak ada riwayat peminjaman yang sesuai dengan filter.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  @if($daftarRiwayat->hasPages())
    <div class="mt-4">
      {{ $daftarRiwayat->links() }}
    </div>
  @endif
</div>
@endsection
