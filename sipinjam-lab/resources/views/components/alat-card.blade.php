@props(['alat', 'sisa' => null])

@php
    $pakaiSisa = $sisa !== null;
    $jumlahTersedia = $pakaiSisa ? (int) $sisa : (int) $alat['stok'];
    $ada = $jumlahTersedia > 0;
@endphp

<x-card
    :title="$alat['nama']"
    :code="$alat['kode']"
    :state="$ada ? 'normal' : 'unavailable'"
>
    <x-alat-illustration
        :nama="$alat['nama']"
        :kategori="$alat['kategori']"
        class="mb-2"
    />

    <div class="flex items-center justify-between gap-2">
        @if ($pakaiSisa)
            @if ($ada)
                <span class="badge badge-success">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                    Tersedia ({{ $jumlahTersedia }} unit)
                </span>
            @else
                <span class="badge badge-warning">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                    Sedang Dipinjam
                </span>
            @endif
        @else
            <x-badge
                :status="$ada ? 'tersedia' : 'habis'"
                class="self-start"
            />
        @endif
        <span class="text-xs font-semibold text-muted">{{ $alat['kategori'] }}</span>
    </div>

    <p class="card-meta">
        Stok:
        <strong class="font-bold {{ $ada ? 'text-emerald-600' : 'text-red-500' }}">
            {{ $jumlahTersedia }} unit
        </strong>
    </p>

    <p class="card-meta">
        Lokasi: {{ $alat['lokasi'] }}
    </p>

    <x-slot:footer>
        <div class="flex items-center justify-between gap-2">
            <x-button size="sm" variant="secondary" :href="route('alat.detail', $alat['id'])">
                Lihat Detail
            </x-button>

            @if ($ada)
                <x-button size="sm" :href="route('alat.pinjam', $alat['id'])">
                    Pinjam &rarr;
                </x-button>
            @else
                <x-button size="sm" disabled>
                    {{ $pakaiSisa ? 'Sedang Dipinjam' : 'Stok Habis' }}
                </x-button>
            @endif
        </div>
    </x-slot:footer>
</x-card>
