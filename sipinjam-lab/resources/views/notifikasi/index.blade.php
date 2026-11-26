@extends('layouts.app')

@section('title', 'Notifikasi - SIPINJAM-LAB')

@section('content')
<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-heading">Notifikasi</h1>
      <p class="text-xs text-muted mt-1">
        @if ($tabelTersedia)
          Anda memiliki <strong class="text-accent">{{ $jumlahBelumDibaca }}</strong> notifikasi belum dibaca.
        @else
          Tabel notifikasi belum dimigrasi pada database lokal.
        @endif
      </p>
    </div>

    @if ($tabelTersedia && $jumlahBelumDibaca > 0)
      <form action="{{ route('notifikasi.bacaSemua') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-secondary btn-sm">
          Tandai Semua Sudah Dibaca
        </button>
      </form>
    @endif
  </div>

  @if (! $tabelTersedia)
    <div class="alert alert-info text-sm">
      Jalankan <code>php artisan migrate</code> pada terminal lokal untuk mengaktifkan penyimpanan notifikasi database.
    </div>
  @elseif ($daftarNotifikasi->isEmpty())
    <div class="surface-card p-10 text-center border-dashed">
      <h2 class="text-base font-bold text-heading">Belum Ada Notifikasi</h2>
      <p class="text-xs text-muted mt-1">Pemberitahuan terkait peminjaman alat akan muncul di sini.</p>
    </div>
  @else
    <div class="space-y-3">
      @foreach ($daftarNotifikasi as $notif)
        @php
          $data = $notif->data ?? [];
          $belumDibaca = $notif->read_at === null;
        @endphp
        <div class="surface-card p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $belumDibaca ? 'border-l-4 border-l-[#0EA5E9]' : 'opacity-80' }}">
          <div class="space-y-1">
            <div class="flex flex-wrap items-center gap-2">
              <span class="font-bold text-sm text-heading">{{ $data['judul'] ?? 'Notifikasi' }}</span>
              @if (! empty($data['status']))
                <x-badge :status="$data['status']" />
              @endif
              @if ($belumDibaca)
                <span class="badge badge-info text-[10px]">Baru</span>
              @endif
            </div>
            <p class="text-xs text-muted">{{ $data['pesan'] ?? '-' }}</p>
            <div class="text-[11px] text-muted">{{ $notif->created_at->format('d M Y, H:i') }}</div>
          </div>

          <div class="flex flex-wrap items-center gap-2 shrink-0">
            @if (! empty($data['url']))
              <a href="{{ $data['url'] }}" class="btn btn-secondary btn-sm">Lihat</a>
            @endif
            @if ($belumDibaca)
              <form action="{{ route('notifikasi.baca', $notif->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm">Tandai Dibaca</button>
              </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    @if ($daftarNotifikasi->hasPages())
      <div class="mt-4">{{ $daftarNotifikasi->links() }}</div>
    @endif
  @endif
</div>
@endsection
