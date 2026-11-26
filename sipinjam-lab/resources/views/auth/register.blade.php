@extends('layouts.app')

@section('title', 'Daftar Akun Peminjam - SIPINJAM-LAB')

@section('content')
<div class="mx-auto max-w-lg py-4">
  <div class="surface-card p-6 sm:p-8">
    <div class="text-center mb-6">
      <div class="mx-auto h-12 w-12 rounded-2xl bg-gradient-to-tr from-[#172554] to-[#0EA5E9] flex items-center justify-center text-white shadow-md mb-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-heading">Pendaftaran Peminjam</h1>
      <p class="text-sm text-muted mt-1">Daftarkan akun mahasiswa Anda untuk mengajukan peminjaman alat laboratorium</p>
    </div>

    <div class="mb-5 alert alert-info text-xs flex items-start gap-2.5">
      <svg class="w-4 h-4 text-accent shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <div>
        Akun yang didaftarkan secara mandiri akan otomatis memiliki hak akses sebagai <strong>Peminjam (Mahasiswa)</strong>.
      </div>
    </div>

    @if ($errors->any())
      <div class="mb-5 alert alert-error text-sm">
        <p class="font-semibold mb-1">Periksa isian berikut:</p>
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
      @csrf

      <!-- Nama Lengkap -->
      <div>
        <label for="name" class="field-label block mb-1.5">
          Nama Lengkap <span class="field-required">*</span>
        </label>
        <input type="text"
               id="name"
               name="name"
               value="{{ old('name') }}"
               required
               maxlength="100"
               placeholder="Contoh: Rafa Fadhilah"
               class="field-input">
      </div>

      <!-- NIM -->
      <div>
        <label for="nim" class="field-label block mb-1.5">
          Nomor Induk Mahasiswa (NIM) <span class="field-required">*</span>
        </label>
        <input type="text"
               id="nim"
               name="nim"
               value="{{ old('nim') }}"
               required
               inputmode="numeric"
               pattern="[0-9]{8,20}"
               maxlength="20"
               placeholder="Contoh: 24051130041"
               class="field-input font-mono">
        <p class="mt-1 text-xs text-muted">NIM berupa 8 sampai 20 digit angka.</p>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="field-label block mb-1.5">
          Alamat Email Aktif <span class="field-required">*</span>
        </label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               maxlength="150"
               placeholder="nama@email.com"
               class="field-input">
      </div>

      <!-- Nomor Telepon (Opsional) -->
      <div>
        <label for="phone" class="field-label block mb-1.5">
          Nomor WhatsApp / HP (Opsional)
        </label>
        <input type="text"
               id="phone"
               name="phone"
               value="{{ old('phone') }}"
               maxlength="20"
               placeholder="08xxxxxxxxxx"
               class="field-input">
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="field-label block mb-1.5">
          Password <span class="field-required">*</span>
        </label>
        <div class="relative">
          <input type="password"
                 id="password"
                 name="password"
                 required
                 minlength="8"
                 placeholder="Minimal 8 karakter"
                 class="field-input pr-11">
          <button type="button"
                  id="togglePassword"
                  class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-muted hover:text-accent transition-colors focus:outline-none"
                  aria-label="Tampilkan atau sembunyikan password">
            <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Konfirmasi Password -->
      <div>
        <label for="password_confirmation" class="field-label block mb-1.5">
          Konfirmasi Password <span class="field-required">*</span>
        </label>
        <input type="password"
               id="password_confirmation"
               name="password_confirmation"
               required
               placeholder="Ulangi password di atas"
               class="field-input">
      </div>

      <button type="submit" class="btn w-full mt-3 py-3">
        Daftar Akun Baru
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-muted border-t divider-subtle pt-5">
      Sudah memiliki akun?
      <a href="{{ route('login') }}" class="link-accent">
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
