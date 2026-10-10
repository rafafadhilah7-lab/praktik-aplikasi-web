
@props(['alat'])

@php
    $ada = $alat['stok'] > 0;
@endphp

<x-card
    :title="$alat['nama']"
    :code="$alat['kode']"
    :state="$ada ? 'normal' : 'unavailable'"
>
    <x-badge
        :status="$ada ? 'tersedia' : 'habis'"
        class="self-start"
    />

    <p class="card-meta">
        Stok: {{ $alat['stok'] }} unit -
        Kategori: {{ $alat['kategori'] }}
    </p>

    <p class="card-meta">
        Lokasi: {{ $alat['lokasi'] }}
    </p>

    <x-slot:footer>
        @if ($ada)
            <x-button size="sm" :href="url('/alat/' . $alat['id'])">
                Lihat Detail
            </x-button>
        @else
            <x-button size="sm" disabled>
                Tidak tersedia
            </x-button>
        @endif
    </x-slot:footer>
</x-card>
