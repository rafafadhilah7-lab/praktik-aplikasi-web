 @extends('layouts.app')

@section('title', 'Detail Alat')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8">

    {{-- Tombol kembali --}}
    <a href="{{ route('alat.hasil') }}"
       class="mb-6 inline-block text-sm font-medium text-pink-600 hover:underline">
        &larr; Kembali ke daftar alat
    </a>

    {{-- Pesan error --}}
    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Kartu detail alat --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Informasi utama --}}
        <div class="bg-pink-50 p-6">
            <p class="text-sm font-semibold uppercase tracking-wide text-pink-700">
                {{ $alat['kode'] }}
            </p>

            <h1 class="mt-2 text-3xl font-bold text-gray-900">
                {{ $alat['nama'] }}
            </h1>

            <p class="mt-2 text-gray-600">
                Kategori: {{ $alat['kategori'] }}
            </p>
        </div>

        {{-- Lokasi dan stok --}}
        <div class="grid gap-5 p-6 sm:grid-cols-2">

            <div>
                <p class="text-sm text-gray-500">Lokasi</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $alat['lokasi'] }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Stok tersedia</p>
                <p class="mt-1 font-semibold {{ $alat['stok'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $alat['stok'] }} unit
                </p>
            </div>

        </div>

        {{-- Tombol peminjaman --}}
        <div class="border-t border-gray-100 p-6">

            @if ($alat['stok'] > 0)
                <a href="{{ route('alat.pinjam', $alat['id']) }}"
                   class="inline-flex items-center justify-center rounded-lg bg-pink-600 px-5 py-3 font-semibold text-white transition hover:bg-pink-700">
                    Ajukan Peminjaman
                </a>
            @else
                <button type="button"
                        disabled
                        class="cursor-not-allowed rounded-lg bg-gray-300 px-5 py-3 font-semibold text-gray-600">
                    Alat Tidak Tersedia
                </button>
            @endif

        </div>

    </div>

</div>
@endsection