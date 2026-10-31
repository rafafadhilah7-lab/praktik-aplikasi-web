@extends('layouts.app')

@section('title', 'Masuk ke Sistem')

@section('content')
<div class="mx-auto w-full max-w-4xl px-4 py-8">
  <div class="overflow-hidden rounded-lg border border-[#e5e7eb] bg-white shadow-sm flex flex-col md:flex-row">
    <!-- Kolom Kiri: Identitas Institusi / Sistem -->
    <div class="md:w-5/12 bg-[#172235] text-white p-8 flex flex-col justify-between">
      <div>
        <div class="flex items-center gap-2.5 mb-6">
          <div class="w-9 h-9 rounded-md bg-[#2563eb] flex items-center justify-center text-white flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
          </div>
          <div>
            <div class="font-bold text-base tracking-tight text-white">SIPINJAM-LAB</div>
            <div class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">Portal Laboratorium</div>
          </div>
        </div>

        <h2 class="text-lg font-semibold text-white mb-2">Sistem Informasi Peminjaman Alat</h2>
        <p class="text-xs text-slate-300 leading-relaxed mb-6">
          Aplikasi terintegrasi untuk pengelolaan inventaris, verifikasi pengajuan, dan sirkulasi alat praktikum laboratorium institusi pendidikan.
        </p>

        <div class="space-y-2.5 text-xs text-slate-300 border-t border-slate-700/60 pt-5">
          <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
            <span>Verifikasi stok fisik real-time</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
            <span>Persetujuan terstruktur oleh petugas lab</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
            <span>Jejak audit dan riwayat transaksi lengkap</span>
          </div>
        </div>
      </div>

      <div class="mt-8 pt-4 border-t border-slate-700/60 text-[11px] text-slate-400">
        Hak Cipta &copy; {{ date('Y') }} &bull; Laboratorium Akademik
      </div>
    </div>

    <!-- Kolom Kanan: Formulir Login -->
    <div class="md:w-7/12 p-8 sm:p-10 flex flex-col justify-center">
      <div class="mb-6">
        <h1 class="text-xl font-bold text-[#202938]">Masuk ke Akun</h1>
        <p class="text-xs text-[#64748b] mt-1">Gunakan alamat email dan kata sandi Anda yang terdaftar.</p>
      </div>

      <!-- Peringatan Batas Percobaan Login -->
      <div class="mb-5 rounded-md border border-amber-200 bg-amber-50/70 p-3 text-xs text-amber-900 flex items-start gap-2">
        <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
          <span class="font-semibold">Keamanan:</span> Maksimal 3 kali percobaan login gagal sebelum akun diblokir sementara selama 15 menit.
        </div>
      </div>

      @if ($errors->any())
        <div class="mb-5 rounded-md border border-red-200 bg-red-50 p-3.5 text-xs text-red-700">
          <div class="font-semibold mb-1">Gagal masuk ke sistem:</div>
          <ul class="list-inside list-disc space-y-0.5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
        @csrf

        <div class="form-group">
          <label for="email" class="form-label">
            Alamat Email <span class="text-red-500">*</span>
          </label>
          <input type="email"
                 id="email"
                 name="email"
                 value="{{ old('email') }}"
                 required
                 autofocus
                 placeholder="nama@institusi.ac.id"
                 class="form-input">
        </div>

        <div class="form-group">
          <div class="flex items-center justify-between">
            <label for="password" class="form-label">
              Password <span class="text-red-500">*</span>
            </label>
          </div>
          <div class="relative">
            <input type="password"
                   id="password"
                   name="password"
                   required
                   placeholder="Masukkan kata sandi"
                   class="form-input pr-10">
            <button type="button"
                    id="togglePassword"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                    aria-label="Tampilkan atau sembunyikan password">
              <svg id="eyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <svg id="eyeClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
              </svg>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-between text-xs pt-1">
          <label class="flex items-center gap-2 cursor-pointer text-slate-600">
            <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span>Ingat sesi masuk</span>
          </label>
        </div>

        <button type="submit" class="btn btn-primary w-full">
          Masuk ke SIPINJAM-LAB
        </button>
      </form>

      <div class="mt-6 pt-5 border-t border-[#e5e7eb] flex items-center justify-between text-xs text-slate-500">
        <span>Belum memiliki akun?</span>
        <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
          Registrasi Mahasiswa
        </a>
      </div>

      <!-- Kotak Akun Demo untuk Pengujian -->
      <div class="mt-5 rounded-md border border-slate-200 bg-slate-50 p-3 text-[11px] text-slate-600">
        <div class="font-bold text-slate-700 mb-1">Akun Pengujian (Demo):</div>
        <div class="grid grid-cols-1 gap-0.5 font-mono">
          <div><strong class="text-slate-800">Admin:</strong> admin@sipinjam.lab / password123</div>
          <div><strong class="text-slate-800">Laboran:</strong> laboran@sipinjam.lab / password123</div>
          <div><strong class="text-slate-800">Peminjam:</strong> peminjam@sipinjam.lab / password123</div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (toggleBtn && passwordInput) {
      toggleBtn.addEventListener('click', function () {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        eyeOpen.classList.toggle('hidden', isPassword);
        eyeClosed.classList.toggle('hidden', !isPassword);
      });
    }
  });
</script>
@endpush
@endsection
