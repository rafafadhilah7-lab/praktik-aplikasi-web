@extends('layouts.app')

@section('title', 'Profil Pengguna - SIPINJAM-LAB')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-6">
  <div class="overflow-hidden rounded-3xl border border-pink-100 bg-white p-6 sm:p-8 shadow-xl shadow-pink-100/30">
    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-pink-600 to-rose-400 text-white flex items-center justify-center font-extrabold text-2xl shadow-md shadow-pink-200">
        {{ strtoupper(substr($user->name, 0, 1)) }}
      </div>
      <div>
        <div class="flex items-center gap-2 mb-1">
          <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
          <x-badge :status="$user->role" />
        </div>
        <p class="text-xs text-slate-500">{{ $user->email }} &bull; Terdaftar sejak {{ $user->created_at->format('d M Y') }}</p>
      </div>
    </div>

    @if ($errors->any())
      <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
        <p class="font-semibold mb-1">Periksa kembali data Anda:</p>
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('profil.update') }}" method="POST" class="space-y-5">
      @csrf
      @method('PUT')

      <div class="space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-wider text-pink-700">Informasi Pribadi</h2>

        <!-- Nama Lengkap -->
        <div>
          <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Nama Lengkap <span class="text-rose-500">*</span>
          </label>
          <input type="text"
                 id="name"
                 name="name"
                 value="{{ old('name', $user->name) }}"
                 required
                 maxlength="100"
                 class="field-input">
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Alamat Email <span class="text-rose-500">*</span>
          </label>
          <input type="email"
                 id="email"
                 name="email"
                 value="{{ old('email', $user->email) }}"
                 required
                 maxlength="150"
                 class="field-input">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- NIM (Readonly if exists) -->
          <div>
            <label for="nim" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
              NIM Mahasiswa
            </label>
            <input type="text"
                   id="nim"
                   value="{{ $user->nim ?? 'Tidak Ada (Bukan Mahasiswa)' }}"
                   disabled
                   class="field-input font-mono bg-slate-50 text-slate-500 cursor-not-allowed">
            <p class="mt-1 text-[11px] text-slate-400">NIM diatur saat registrasi awal.</p>
          </div>

          <!-- No Telepon -->
          <div>
            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
              Nomor WhatsApp / HP
            </label>
            <input type="text"
                   id="phone"
                   name="phone"
                   value="{{ old('phone', $user->phone) }}"
                   placeholder="08xxxxxxxxxx"
                   class="field-input">
          </div>
        </div>
      </div>

      <!-- Ubah Password -->
      <div class="pt-4 border-t border-slate-100 space-y-4">
        <div>
          <h2 class="text-xs font-bold uppercase tracking-wider text-pink-700">Ganti Password (Opsional)</h2>
          <p class="text-xs text-slate-400 mt-0.5">Kosongkan kolom di bawah jika Anda tidak ingin mengubah password saat ini.</p>
        </div>

        <div>
          <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Password Saat Ini
          </label>
          <input type="password"
                 id="current_password"
                 name="current_password"
                 placeholder="Masukkan password saat ini untuk verifikasi"
                 class="field-input">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
              Password Baru
            </label>
            <input type="password"
                   id="new_password"
                   name="new_password"
                   minlength="8"
                   placeholder="Minimal 8 karakter"
                   class="field-input">
          </div>
          <div>
            <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
              Konfirmasi Password Baru
            </label>
            <input type="password"
                   id="new_password_confirmation"
                   name="new_password_confirmation"
                   placeholder="Ulangi password baru"
                   class="field-input">
          </div>
        </div>
      </div>

      <!-- Submit Button -->
      <div class="pt-4 border-t border-slate-100 flex justify-end">
        <button type="submit" class="btn">
          Simpan Perubahan Profil
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
