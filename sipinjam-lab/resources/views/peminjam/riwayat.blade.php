@extends('layouts.app')

@section('title', 'Riwayat Peminjaman Saya')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Riwayat Peminjaman Saya</h1>
      <p class="text-xs text-slate-500 mt-1">Daftar seluruh riwayat pengajuan dan peminjaman alat laboratorium Anda</p>
    </div>
    <a href="{{ route('alat.beranda') }}" class="btn btn-sm self-start sm:self-auto">
      + Ajukan Peminjaman Baru
    </a>
  </div>

  <!-- Filter Status Tabs -->
  <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-3">
    <a href="{{ route('peminjam.riwayat') }}"
       class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === '' ? 'bg-pink-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-pink-50 hover:text-pink-600 border border-slate-200' }}">
      Semua Status
    </a>
    @foreach(['menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'dipinjam' => 'Dipinjam', 'dikembalikan' => 'Dikembalikan', 'ditolak' => 'Ditolak'] as $stKey => $stLabel)
      <a href="{{ route('peminjam.riwayat', ['status' => $stKey]) }}"
         class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $status === $stKey ? 'bg-pink-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-pink-50 hover:text-pink-600 border border-slate-200' }}">
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
              <span class="font-mono font-bold text-xs text-pink-700">#{{ $item->id }}</span>
              <div class="text-xs text-slate-400">{{ $item->created_at->format('d M Y') }}</div>
            </td>
            <td>
              <div class="font-bold text-slate-900">{{ $item->alat->nama }}</div>
              <div class="text-xs text-slate-500 font-mono">{{ $item->alat->kode }} ({{ $item->alat->kategori }})</div>
            </td>
            <td>
              <div class="text-xs font-semibold text-slate-800">{{ $item->tanggal_pinjam->format('d M Y') }}</div>
              <div class="text-xs text-slate-500">s.d. {{ $item->tanggal_selesai->format('d M Y') }} ({{ $item->durasi }} hari)</div>
            </td>
            <td>
              <span class="font-bold text-slate-900">{{ $item->jumlah }} unit</span>
            </td>
            <td>
              <x-badge :status="$item->status" />
            </td>
            <td class="max-w-xs">
              @if($item->catatan_petugas)
                <span class="text-xs text-slate-600 block line-clamp-2" title="{{ $item->catatan_petugas }}">
                  {{ $item->catatan_petugas }}
                </span>
              @else
                <span class="text-xs text-slate-400">-</span>
              @endif
            </td>
            <td>
              <a href="{{ route('peminjaman.detail', $item->id) }}" class="btn btn-secondary btn-sm">
                Detail
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-10 text-slate-400">
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
