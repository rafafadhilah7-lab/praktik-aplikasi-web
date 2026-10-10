@props(['status'])
@php
  $map = [
    'tersedia'  => ['success', 'Tersedia'],
    'menunggu'  => ['warning', 'Menunggu verifikasi'],
    'disetujui' => ['success', 'Disetujui'],
    'dipinjam'  => ['info', 'Dipinjam'],
    'ditolak'   => ['danger', 'Ditolak'],
    'terlambat' => ['danger', 'Terlambat'],
    'habis'     => ['danger', 'Stok habis'],
  ];
  [$tone, $label] = $map[$status] ?? ['info', ucfirst($status)];
@endphp
<span {{ $attributes->merge(['class' => 'badge badge-'.$tone]) }}>{{ $label }}</span>