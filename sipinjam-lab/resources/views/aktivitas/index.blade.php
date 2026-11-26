@extends('layouts.app')

@section('title', 'Riwayat Aktivitas Transaksi - SIPINJAM-LAB')

@section('content')
<div class="space-y-6">
  <div>
    <h1 class="text-2xl font-bold text-heading">Riwayat Aktivitas Peminjaman</h1>
    <p class="text-xs text-muted mt-1">Catatan aktivitas pengajuan, perubahan, pembatalan, persetujuan, penyerahan, dan pengembalian.</p>
  </div>

  @if (! $tabelTersedia)
    <div class="alert alert-info text-sm">
      Tabel <code>aktivitas_peminjaman</code> belum dimigrasi pada database lokal. Jalankan <code>php artisan migrate</code> untuk mengaktifkan pencatatan aktivitas.
    </div>
  @else
    <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Waktu</th>
            <th>Pelaku</th>
            <th>Aktivitas</th>
            <th>Transaksi</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($daftarAktivitas as $item)
            <tr>
              <td class="text-xs text-muted whitespace-nowrap">
                {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
              </td>
              <td>
                <div class="font-semibold text-heading text-xs">{{ $item->user?->name ?? 'Sistem' }}</div>
                <div class="text-[11px] text-muted">{{ $item->user?->role_label ?? '-' }}</div>
              </td>
              <td>
                <span class="code-label">{{ $item->aktivitas }}</span>
              </td>
              <td>
                @if ($item->peminjaman)
                  <a href="{{ route('peminjaman.detail', $item->peminjaman_id) }}" class="link-accent text-xs">
                    #{{ $item->peminjaman_id }} ({{ $item->peminjaman->alat?->nama ?? '-' }})
                  </a>
                @else
                  <span class="text-xs text-muted">-</span>
                @endif
              </td>
              <td class="text-xs text-muted max-w-md">{{ $item->keterangan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-10 text-muted">Belum ada catatan aktivitas transaksi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($daftarAktivitas->hasPages())
      <div class="mt-4">{{ $daftarAktivitas->links() }}</div>
    @endif
  @endif
</div>
@endsection
