
@extends('layouts.app')

@section('title', 'Hasil Pencarian - SIPINJAM-LAB')

@section('content')
    <nav class="field-help">
        <a class="underline" href="{{ route('alat.beranda') }}">
            Beranda
        </a>
        / Hasil Pencarian
    </nav>

    <h1 class="mt-2">Hasil Pencarian</h1>

    <p class="field-help">
        Data alat di halaman ini adalah data contoh.
        Tanggal pinjam belum memengaruhi hasil karena jadwal
        peminjaman baru tersedia setelah ada database.
    </p>

    <div class="mt-6">
        <x-search-form
            :q="$q"
            :kategori="$kategori"
            :tanggal="$tanggal"
            :daftar-kategori="$daftarKategori"
        />
    </div>

    <p class="mt-6" aria-live="polite">
        Menampilkan {{ count($hasil) }} alat
        @if ($q !== '')
            untuk "{{ $q }}"
        @endif
        @if ($kategori !== '')
            kategori {{ $kategori }}
        @endif
        @if ($tanggal !== '')
            (tanggal pinjam {{ $tanggal }})
        @endif.
    </p>

    @if (count($hasil) > 0)
        <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($hasil as $alat)
                <x-alat-card :alat="$alat" />
            @endforeach
        </div>
    @else
        <div class="card mt-4">
            <div class="card-body">
                <h2>Alat tidak ditemukan</h2>

                <p>
                    Tidak ada alat yang cocok dengan pencarianmu.
                    Coba kata kunci yang lebih pendek atau pilih
                    "Semua kategori".
                </p>

                <div class="mt-4">
                    <x-button
                        variant="secondary"
                        :href="route('alat.hasil')"
                    >
                        Tampilkan semua alat
                    </x-button>
                </div>
            </div>
        </div>
    @endif
@endsection
