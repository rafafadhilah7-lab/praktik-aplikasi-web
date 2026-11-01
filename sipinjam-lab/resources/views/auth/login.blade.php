@extends('layouts.app')

@section('title', 'Masuk ke SIPINJAM-LAB')

@section('content')
<div class="mx-auto max-w-md px-4 py-8">
  <div class="overflow-hidden rounded-2xl border border-pink-100 bg-white p-8 shadow-xl shadow-pink-100/40">
    <div class="text-center mb-6">
      <div class="mx-auto h-12 w-12 rounded-2xl bg-gradient-to-tr from-pink-600 to-rose-400 flex items-center justify-center text-white shadow-md shadow-pink-200 mb-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Masuk ke Sistem</h1>
      <p class="text-sm text-slate-500 mt-1">Gunakan akun SIPINJAM-LAB Anda untuk melanjutkan</p>
    </div>

    <!-- Peringatan Batas Percobaan Login -->
    <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50/80 p-3.5 text-xs text-amber-800 flex items-start gap-2.5">
      <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <div>
        <span class="font-semibold">Keamanan Akun:</span> Maksimal 3 kali percobaan login gagal. Setelah itu akun diblokir sementara selama 15 menit.
      </div>
    </div>

    @if ($errors->any())
      <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
        <p class="font-semibold mb-1">Gagal Masuk:</p>
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
          Email Akun <span class="text-rose-500">*</span>
        </label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               placeholder="nama@email.com"
               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 transition-all">
      </div>

      <div>
        <div class="flex items-center justify-between mb-1.5">
          <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
            Password <span class="text-rose-500">*</span>
          </label>
        </div>
        <div class="relative">
          <input type="password"
                 id="password"
                 name="password"
                 required
                 placeholder="Masukkan password Anda"
                 class="w-full rounded-xl border border-slate-200 px-4 py-3 pr-11 text-sm focus:border-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-100 transition-all">
          <button type="button"
                  id="togglePassword"
                  class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-pink-600 transition-colors focus:outline-none"
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

      <div class="flex items-center justify-between text-xs pt-1">
        <label class="flex items-center gap-2 cursor-pointer text-slate-600">
          <input type="checkbox" name="remember" class="rounded border-slate-300 text-pink-600 focus:ring-pink-500">
          <span>Ingat saya</span>
        </label>
      </div>

      <button type="submit"
              class="w-full mt-2 inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-pink-600 to-rose-500 px-5 py-3 font-semibold text-white shadow-md shadow-pink-200 transition-all hover:from-pink-700 hover:to-rose-600 hover:-translate-y-0.5">
        Masuk Sekarang
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500 border-t border-slate-100 pt-5">
      Belum memiliki akun?
      <a href="{{ route('register') }}" class="font-semibold text-pink-600 hover:text-pink-700 hover:underline">
        Daftar sebagai peminjam
      </a>
    </div>

    <!-- Informasi Akun Uji Coba -->
    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50/70 p-4 text-xs text-slate-600">
      <div class="font-bold text-slate-700 mb-2">Akun Uji Coba (Demo):</div>
      <div class="space-y-1.5 font-mono">
        <div><strong class="text-pink-700">Admin:</strong> admin@sipinjam.lab | password123</div>
        <div><strong class="text-sky-700">Laboran:</strong> laboran@sipinjam.lab | password123</div>
        <div><strong class="text-emerald-700">Peminjam:</strong> peminjam@sipinjam.lab | password123</div>
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
