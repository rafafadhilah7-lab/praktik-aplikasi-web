@extends('layouts.app')

@section('title', 'Pendaftaran Akun Mahasiswa')

@section('content')
<div class="mx-auto w-full max-w-xl px-4 py-8">
  <div class="rounded-lg border border-[#e5e7eb] bg-white p-8 shadow-sm">
    <div class="mb-6">
      <div class="flex items-center gap-2 mb-2">
        <span class="badge badge-info">Pendaftaran Mahasiswa</span>
        <span class="text-xs text-slate-400">&bull; Portal Peminjam</span>
      </div>
      <h1 class="text-xl font-bold text-[#202938]">Formulir Registrasi Peminjam</h1>
      <p class="text-xs text-[#64748b] mt-1">
        Daftarkan akun mahasiswa untuk mengajukan peminjaman alat laboratorium secara resmi.
      </p>
    </div>

    @if ($errors->any())
      <div class="mb-5 rounded-md border border-red-200 bg-red-50 p-3.5 text-xs text-red-700">
        <div class="font-semibold mb-1">Periksa kembali data pendaftaran:</div>
        <ul class="list-inside list-disc space-y-0.5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
      @csrf

      <div class="form-group">
        <label for="name" class="form-label">
          Nama Lengkap Mahasiswa <span class="text-red-500">*</span>
        </label>
        <input type="text"
               id="name"
               name="name"
               value="{{ old('name') }}"
               required
               maxlength="100"
               placeholder="Contoh: Rafa Fadhilah"
               class="form-input">
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="form-group">
          <label for="nim" class="form-label">
            NIM (Nomor Induk Mahasiswa) <span class="text-red-500">*</span>
          </label>
          <input type="text"
                 id="nim"
                 name="nim"
                 value="{{ old('nim') }}"
                 required
                 inputmode="numeric"
                 pattern="[0-9]{8,20}"
                 maxlength="20"
                 placeholder="8 - 20 digit angka"
                 class="form-input font-mono">
        </div>

        <div class="form-group">
          <label for="phone" class="form-label">
            Nomor WhatsApp / HP
          </label>
          <input type="text"
                 id="phone"
                 name="phone"
                 value="{{ old('phone') }}"
                 maxlength="20"
                 placeholder="08xxxxxxxxxx"
                 class="form-input">
        </div>
      </div>

      <div class="form-group">
        <label for="email" class="form-label">
          Alamat Email Aktif <span class="text-red-500">*</span>
        </label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               maxlength="150"
               placeholder="mahasiswa@institusi.ac.id"
               class="form-input">
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="form-group">
          <label for="password" class="form-label">
            Kata Sandi <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <input type="password"
                   id="password"
                   name="password"
                   required
                   minlength="8"
                   placeholder="Minimal 8 karakter"
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

        <div class="form-group">
          <label for="password_confirmation" class="form-label">
            Konfirmasi Sandi <span class="text-red-500">*</span>
          </label>
          <input type="password"
                 id="password_confirmation"
                 name="password_confirmation"
                 required
                 placeholder="Ulangi kata sandi"
                 class="form-input">
        </div>
      </div>

      <div class="pt-2">
        <button type="submit" class="btn btn-primary w-full">
          Daftarkan Akun Mahasiswa
        </button>
      </div>
    </form>

    <div class="mt-6 pt-5 border-t border-[#e5e7eb] flex items-center justify-between text-xs text-slate-500">
      <span>Sudah memiliki akun?</span>
      <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
        Masuk di sini
      </a>
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
