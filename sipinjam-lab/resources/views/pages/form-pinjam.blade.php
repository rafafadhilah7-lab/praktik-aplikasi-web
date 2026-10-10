 @extends('layouts.app')

@section('title', 'Form Peminjaman Alat')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8">

    {{-- Tombol kembali --}}
    <a href="{{ route('alat.detail', $alat['id']) }}"
       class="mb-6 inline-block text-sm font-medium text-pink-600 hover:underline">
        &larr; Kembali ke detail alat
    </a>

    {{-- Kartu form peminjaman --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Informasi alat --}}
        <div class="bg-pink-50 p-6">
            <p class="text-sm font-semibold uppercase tracking-wide text-pink-700">
                Form Peminjaman
            </p>

            <h1 class="mt-2 text-3xl font-bold text-gray-900">
                {{ $alat['nama'] }}
            </h1>

            <p class="mt-2 text-gray-600">
                Kode alat: {{ $alat['kode'] }}
            </p>

            <p class="mt-1 text-gray-600">
                Lokasi: {{ $alat['lokasi'] }}
            </p>

            <p class="mt-1 text-gray-600">
                Stok tersedia: {{ $alat['stok'] }} unit
            </p>
        </div>

        {{-- Form pengajuan --}}
        <form action="{{ route('alat.ajukan', $alat['id']) }}"
              method="POST"
              class="space-y-5 p-6">

            @csrf

            {{-- Nama peminjam --}}
            <div>
                <label for="nama_peminjam"
                       class="mb-1 block text-sm font-medium text-gray-700">
                    Nama Peminjam <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       id="nama_peminjam"
                       name="nama_peminjam"
                       value="{{ old('nama_peminjam') }}"
                       required
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">

                @error('nama_peminjam')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIM --}}
            <div>
                <label for="nim"
                       class="mb-1 block text-sm font-medium text-gray-700">
                    NIM <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       id="nim"
                       name="nim"
                       value="{{ old('nim') }}"
                       required
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">

                @error('nim')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal peminjaman --}}
            <div>
                <label for="tanggal_pinjam"
                       class="mb-1 block text-sm font-medium text-gray-700">
                    Tanggal Peminjaman <span class="text-red-500">*</span>
                </label>

                <input type="date"
                       id="tanggal_pinjam"
                       name="tanggal_pinjam"
                       value="{{ old('tanggal_pinjam') }}"
                       min="{{ date('Y-m-d') }}"
                       required
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">

                @error('tanggal_pinjam')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Durasi peminjaman --}}
            <div>
                <label for="durasi"
                       class="mb-1 block text-sm font-medium text-gray-700">
                    Durasi Peminjaman (hari) <span class="text-red-500">*</span>
                </label>

                <input type="number"
                       id="durasi"
                       name="durasi"
                       value="{{ old('durasi', 1) }}"
                       min="1"
                       max="30"
                       required
                       class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">

                <p class="mt-1 text-xs text-gray-500">
                    Durasi peminjaman antara 1 sampai 30 hari.
                </p>

                @error('durasi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Keperluan --}}
            <div>
                <label for="keperluan"
                       class="mb-1 block text-sm font-medium text-gray-700">
                    Keperluan Peminjaman <span class="text-red-500">*</span>
                </label>

                <textarea id="keperluan"
                          name="keperluan"
                          rows="4"
                          required
                          class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">{{ old('keperluan') }}</textarea>

                @error('keperluan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol aksi --}}
            <div class="flex flex-wrap items-center gap-3 border-t border-gray-100 pt-5">

                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-pink-600 px-5 py-3 font-semibold text-white transition hover:bg-pink-700">
                    Kirim Pengajuan
                </button>

                <a href="{{ route('alat.detail', $alat['id']) }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-3 font-semibold text-gray-700 transition hover:bg-gray-50">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>
@endsection