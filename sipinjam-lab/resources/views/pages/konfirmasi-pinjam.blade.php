@extends('layouts.app')

@section('title', 'Tanda Bukti Pengajuan Peminjaman')

@section('content')
<div class="mx-auto max-w-3xl space-y-5">
  <div class="card overflow-hidden">
    <!-- Header Bukti Pengajuan -->
    <div class="card-header bg-[#f8fafc]">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="badge badge-success">Pengajuan Berhasil Disimpan</span>
          <span class="font-mono text-xs text-slate-500">No. Bukti: #{{ $peminjaman->id }}</span>
        </div>
        <h1 class="text-lg font-bold text-[#202938]">Tanda Terima Pengajuan Peminjaman Alat</h1>
        <p class="text-xs text-[#64748b]">Permohonan telah dicatat dalam basis data laboratorium institusi.</p>
      </div>
    </div>

    <div class="p-6 space-y-5">
      <!-- Tabel Ringkasan Rincian -->
      <div class="rounded-md border border-[#e5e7eb] bg-[#f8fafc] p-4 text-xs space-y-3">
        <h2 class="font-bold text-slate-700 uppercase tracking-wider text-[11px] pb-2 border-b border-[#e5e7eb]">
          Rincian Peralatan & Peminjam
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <span class="text-slate-500 block mb-0.5">Nama Peralatan:</span>
            <span class="font-bold text-slate-900 text-sm">{{ $alat->nama }}</span>
            <span class="text-slate-500 block font-mono">{{ $alat->kode }} &bull; {{ $alat->kategori }}</span>
          </div>

          <div>
            <span class="text-slate-500 block mb-0.5">Identitas Mahasiswa:</span>
            <span class="font-bold text-slate-900 text-sm">{{ $peminjaman->nama_peminjam }}</span>
            <span class="text-slate-500 block font-mono">NIM: {{ $peminjaman->nim }}</span>
          </div>

          <div>
            <span class="text-slate-500 block mb-0.5">Jadwal Penggunaan:</span>
            <span class="font-bold text-slate-900">
              {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }} s.d. {{ $peminjaman->tanggal_selesai->format('d/m/Y') }}
            </span>
            <span class="text-slate-500 block">Durasi: {{ $peminjaman->durasi }} hari kalender</span>
          </div>

          <div>
            <span class="text-slate-500 block mb-0.5">Jumlah Unit & Lokasi:</span>
            <span class="font-bold text-blue-700 text-sm">{{ $peminjaman->jumlah }} Unit</span>
            <span class="text-slate-500 block">Lokasi: {{ $alat->lokasi }}</span>
          </div>
        </div>

        <div class="pt-2 border-t border-[#e5e7eb]">
          <span class="text-slate-500 block mb-0.5">Keperluan Penggunaan:</span>
          <p class="font-medium text-slate-800">{{ $peminjaman->keperluan }}</p>
        </div>
      </div>

      <!-- Info Box Status -->
      <div class="rounded-md border border-amber-200 bg-amber-50 p-3.5 text-xs text-amber-900 flex items-start gap-2.5">
        <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
          <strong class="font-semibold block">Status Saat Ini: Menunggu Persetujuan Laboran</strong>
          Alokasi unit telah dicatat di sistem. Silakan bawa identitas mahasiswa dan bukti pengajuan ini ke laboratorium saat jadwal peminjaman tiba untuk pengambilan fisik alat.
        </div>
      </div>
    </div>

    <!-- Navigasi Footer -->
    <div class="card-footer flex flex-wrap items-center justify-between gap-2">
      <div class="flex items-center gap-2">
        @auth
          @if(Auth::user()->isPeminjam())
            <a href="{{ route('peminjam.dashboard') }}" class="btn btn-primary btn-sm">
              Kembali ke Dashboard
            </a>
            <a href="{{ route('peminjam.riwayat') }}" class="btn btn-secondary btn-sm">
              Riwayat Peminjaman Saya
            </a>
          @else
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
              Kembali ke Dashboard
            </a>
          @endif
        @else
          <a href="{{ route('alat.beranda') }}" class="btn btn-primary btn-sm">
            Kembali ke Katalog
          </a>
          <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
            Masuk ke Akun
          </a>
        @endauth
      </div>

      <button type="button" onclick="window.print()" class="btn btn-secondary btn-sm">
        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
        </svg>
        <span>Cetak Tanda Terima</span>
      </button>
    </div>
  </div>
</div>
@endsection