@props(['alat'])

@php
    $ada = (int) $alat['stok'] > 0;
@endphp

<div class="card flex flex-col justify-between hover:border-slate-300 transition-colors">
    <div class="p-4">
        <div class="flex items-center justify-between gap-2 mb-2">
            <span class="code-badge">{{ $alat['kode'] }}</span>
            <x-badge :status="$ada ? 'tersedia' : 'habis'" />
        </div>

        <h3 class="font-bold text-sm text-[#202938] mb-1 leading-snug">
            {{ $alat['nama'] }}
        </h3>

        <div class="space-y-1 text-xs text-[#64748b] mt-2">
            <div class="flex items-center justify-between">
                <span>Kategori:</span>
                <span class="font-medium text-slate-700">{{ $alat['kategori'] }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span>Stok Fisik:</span>
                <span class="font-bold {{ $ada ? 'text-emerald-700' : 'text-red-600' }}">
                    {{ $alat['stok'] }} Unit
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span>Lokasi:</span>
                <span class="text-slate-600 truncate max-w-[150px]">{{ $alat['lokasi'] }}</span>
            </div>
        </div>
    </div>

    <div class="card-footer flex items-center justify-between gap-2">
        <a href="{{ route('alat.detail', $alat['id']) }}" class="btn btn-secondary btn-sm flex-1">
            Detail Alat
        </a>

        @if ($ada)
            <a href="{{ route('alat.pinjam', $alat['id']) }}" class="btn btn-primary btn-sm flex-1">
                Pinjam
            </a>
        @else
            <button type="button" disabled class="btn btn-secondary btn-sm flex-1">
                Stok Habis
            </button>
        @endif
    </div>
</div>
