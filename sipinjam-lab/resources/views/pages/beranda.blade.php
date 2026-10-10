
@extends('layouts.app')

@section('title', 'Cari Alat - SIPINJAM-LAB')

@section('content')
    <h1>Cari Alat Laboratorium</h1>

    <p class="mt-2">
        Cari alat yang ingin dipinjam, lalu ajukan peminjaman.
    </p>

    <p class="field-help">
        Data alat di halaman ini adalah data contoh (belum dari database).
    </p>

    <div class="mt-6">
        <x-search-form :daftar-kategori="$daftarKategori" />
    </div>

    <section class="mt-8">
        <h2>Alat tersedia</h2>

        <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($tersedia as $alat)
                <x-alat-card :alat="$alat" />
            @endforeach
        </div>

        <div class="mt-4">
            <x-button
                variant="secondary"
                :href="route('alat.hasil')"
            >
                Lihat semua alat
            </x-button>
        </div>
    </section>
@endsection
