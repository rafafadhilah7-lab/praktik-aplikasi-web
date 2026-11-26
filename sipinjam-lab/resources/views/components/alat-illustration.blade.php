@props([
    'nama' => 'Alat Laboratorium',
    'kategori' => '',
])

@php
    $n = strtolower((string) $nama);
    $k = strtolower((string) $kategori);

    $file = match (true) {
        str_contains($n, 'multimeter') => 'multimeter.svg',
        str_contains($n, 'osiloskop') => 'osiloskop.svg',
        str_contains($n, 'power supply') || str_contains($n, 'catu daya') => 'power-supply.svg',
        str_contains($n, 'mikroskop') || str_contains($k, 'biologi') => 'mikroskop.svg',
        str_contains($n, 'timbangan') || str_contains($n, 'neraca') => 'timbangan.svg',
        str_contains($n, 'arduino') || str_contains($n, 'mikrokontroler') => 'arduino.svg',
        default => 'default-lab.svg',
    };
@endphp

<div {{ $attributes->merge(['class' => 'alat-thumb']) }}>
    <img
        src="{{ asset('images/alat/' . $file) }}"
        alt="Ilustrasi {{ $nama }}"
        class="alat-thumb-img"
        loading="lazy"
        onerror="this.onerror=null;this.src='{{ asset('images/alat/default-lab.svg') }}';"
    >
</div>
