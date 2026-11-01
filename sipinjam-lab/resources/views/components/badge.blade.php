@props(['status'])

@php
  $map = [
    'tersedia'     => ['success', 'Tersedia'],
    'habis'        => ['danger', 'Stok Habis'],
    'menunggu'     => ['warning', 'Menunggu'],
    'disetujui'    => ['purple', 'Disetujui'],
    'dipinjam'     => ['info', 'Dipinjam'],
    'dikembalikan' => ['success', 'Dikembalikan'],
    'ditolak'      => ['danger', 'Ditolak'],
    'admin'        => ['purple', 'Admin'],
    'laboran'      => ['info', 'Laboran'],
    'peminjam'     => ['success', 'Peminjam'],
  ];

  $key = strtolower((string) $status);
  [$tone, $label] = $map[$key] ?? ['info', ucfirst($status)];
@endphp

<span {{ $attributes->merge(['class' => 'badge badge-'.$tone]) }}>
  <span class="inline-block h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
  {{ $label }}
</span>