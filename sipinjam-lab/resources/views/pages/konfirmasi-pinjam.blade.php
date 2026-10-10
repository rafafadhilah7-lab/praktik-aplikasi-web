 @extends('layouts.app')

@section('title', 'Konfirmasi Peminjaman')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8">

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Header konfirmasi --}}
        <div class="bg-pink-50 p-8 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-3xl text-green-600">
                &#10003;
            </div>

            <h1 class="mt-4 text-2xl font-bold text-gray-900">
                Pengajuan Berhasil Disiapkan!
            </h1>

            <p class="mt-2 text-gray-600">
                Data pengajuan peminjaman alat berhasil divalidasi.
            </p>
        </div>

        {{-- Ringkasan pengajuan --}}
        <div class="space-y-5 p-6">

            <h2 class="text-lg font-bold text-gray-900">
                Ringkasan Pengajuan
            </h2>

            <div class="rounded-xl border border-gray-200 p-5">
                <p class="text-sm text-gray-500">Nama Alat</p>
                <p class="font-semibold text-gray-900">
                    {{ $alat['nama'] }}
                </p>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">Kode Alat</p>
                        <p class="font-medium text-gray-900">
                            {{ $alat['kode'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p class="font-medium text-gray-900">
                            {{ $alat['kategori'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Nama Peminjam</p>
                        <p class="font-medium text-gray-900">
                            {{ $pengajuan['nama_peminjam'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">NIM</p>
                        <p class="font-medium text-gray-900">
                            {{ $pengajuan['nim'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Peminjaman</p>
                        <p class="font-medium text-gray-900">
                            {{ $pengajuan['tanggal_pinjam'] }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Durasi</p>
                        <p class="font-medium text-gray-900">
                            {{ $pengajuan['durasi'] }} hari
                        </p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-sm text-gray-500">Keperluan</p>
                    <p class="mt-1 whitespace-pre-line text-gray-900">{{ $pengajuan['keperluan'] }}</p>
                </div>
            </div>

            {{-- Informasi status --}}
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                <p class="font-semibold text-amber-800">
                    Status: Simulasi pengajuan
                </p>
                <p class="mt-1 text-sm text-amber-700">
                    Pengajuan ini hanya ditampilkan sebagai simulasi praktikum.
                    Data belum disimpan ke database dan belum menjadi peminjaman resmi.
                </p>
            </div>

            {{-- Navigasi --}}
            <div class="flex flex-wrap gap-3 pt-2">
                <a href="{{ route('alat.beranda') }}"
                   class="rounded-lg bg-pink-600 px-5 py-3 font-semibold text-white transition hover:bg-pink-700">
                    Kembali ke Beranda
                </a>

                <a href="{{ route('alat.detail', $alat['id']) }}"
                   class="rounded-lg border border-gray-300 px-5 py-3 font-semibold text-gray-700 transition hover:bg-gray-50">
                    Kembali ke Detail Alat
                </a>
            </div>

        </div>
    </div>
</div>
@endsection