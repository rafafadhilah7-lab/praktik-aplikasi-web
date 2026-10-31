@props(['status'])

@php
  $map = [
    'tersedia'     => ['success', 'Tersedia'],
    'habis'        => ['danger', 'Stok Habis'],
    'menunggu'     => ['warning', 'Menunggu Persetujuan'],
    'disetujui'    => ['info', 'Disetujui'],
    'dipinjam'     => ['info', 'Sedang Dipinjam'],
    'dikembalikan' => ['success', 'Dikembalikan'],
    'ditolak'      => ['danger', 'Ditolak'],
    'admin'        => ['neutral', 'Administrator'],
    'laboran'      => ['info', 'Petugas Laboran'],
    'peminjam'     => ['success', 'Peminjam'],
  ];

  $key = strtolower((string) $status);
  [$tone, $label] = $map[$key] ?? ['neutral', ucfirst($status)];
@endphp

<span {{ $attributes->merge(['class' => 'badge badge-'.$tone]) }}>
  <span class="inline-block h-1.5 w-1.5 rounded-full bg-current opacity-80"></span>
  {{ $label }}
</span>