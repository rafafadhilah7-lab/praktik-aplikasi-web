@extends('layouts.app')
@section('title', 'Galeri Komponen - SIPINJAM-LAB')
@section('content')
<h1>Galeri Komponen</h1>
<p class="field-help">Halaman uji internal (Praktikum 3). Semua data di halaman ini adalah data contoh.</p>

<section class="mt-8">
  <h2>Button</h2>
  <div class="mt-4 flex flex-wrap items-center gap-3">
    <x-button>Ajukan Peminjaman</x-button>
    <x-button variant="secondary">Lihat Detail</x-button>
    <x-button variant="ghost">Batal</x-button>
    <x-button size="sm">Cari</x-button>
    <x-button disabled>Ajukan Peminjaman</x-button>
    <x-button :loading="true">Memproses...</x-button>
  </div>
</section>

<section class="mt-8">
  <h2>Form field</h2>
  <div class="mt-4 grid gap-4 md:grid-cols-2">
    <x-field name="kosong" label="Nama alat" placeholder="Contoh: Multimeter digital" help="Ketik sebagian nama alat." />
    <x-field name="isi" label="Keperluan" value="Praktikum Rangkaian Listrik" :required="true" />
    <x-field name="galat" label="Lama peminjaman" type="number" :required="true" error="Lama peminjaman wajib dipilih." />
    <x-field name="mati" label="NIM" value="2200012345" disabled />
  </div>
</section>

<section class="mt-8">
  <h2>Card alat</h2>
  <div class="mt-4 grid gap-4 md:grid-cols-3">
    <x-card title="Multimeter Digital" code="ALT-001">
      <x-badge status="tersedia" class="self-start" />
      <p class="card-meta">Stok: 5 unit - Kategori: Elektronika</p>
      <x-slot:footer><x-button size="sm">Pinjam</x-button></x-slot:footer>
    </x-card>
    <x-card title="Osiloskop 100 MHz" code="ALT-014" state="selected">
      <x-badge status="tersedia" class="self-start" />
      <p class="card-meta">Stok: 2 unit - Kategori: Elektronika</p>
      <x-slot:footer><x-button size="sm">Pinjam</x-button></x-slot:footer>
    </x-card>
    <x-card title="Power Supply DC" code="ALT-022" state="unavailable">
      <x-badge status="habis" class="self-start" />
      <p class="card-meta">Stok: 0 unit - Kategori: Elektronika</p>
      <x-slot:footer><x-button size="sm" disabled>Pinjam</x-button></x-slot:footer>
    </x-card>
  </div>
</section>

<section class="mt-8">
  <h2>Status badge</h2>
  <div class="mt-4 flex flex-wrap items-center gap-3">
    <x-badge status="tersedia" />
    <x-badge status="menunggu" />
    <x-badge status="disetujui" />
    <x-badge status="dipinjam" />
    <x-badge status="ditolak" />
    <x-badge status="terlambat" />
    <x-badge status="habis" />
  </div>
</section>
@endsection