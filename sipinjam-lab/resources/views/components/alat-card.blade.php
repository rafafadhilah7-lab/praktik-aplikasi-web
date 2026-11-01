@props(['alat'])

@php
    $ada = (int) $alat['stok'] > 0;
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
        Stok: <strong class="{{ $ada ? 'text-pink-700 font-bold' : 'text-rose-600' }}">{{ $alat['stok'] }} unit</strong> &bull;
        Kategori: {{ $alat['kategori'] }}
    </p>

    <p class="card-meta">
        Lokasi: {{ $alat['lokasi'] }}
    </p>

    <x-slot:footer>
        <div class="flex items-center justify-between gap-2">
            <x-button size="sm" variant="secondary" :href="route('alat.detail', $alat['id'])">
                Detail
            </x-button>

            @if ($ada)
                <x-button size="sm" :href="route('alat.pinjam', $alat['id'])">
                    Pinjam &rarr;
                </x-button>
            @else
                <x-button size="sm" disabled>
                    Stok Habis
                </x-button>
            @endif
        </div>
    </x-slot:footer>
</x-card>
