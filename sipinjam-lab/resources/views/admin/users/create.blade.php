@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru - Admin')

@section('content')
<div class="mx-auto max-w-xl py-2">
  <!-- Navigasi Kembali -->
  <a href="{{ route('admin.users.index') }}"
     class="mb-6 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider link-accent">
    &larr; Kembali ke daftar pengguna
  </a>

  <div class="surface-card p-6 sm:p-8">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-heading">Tambah Akun Pengguna</h1>
      <p class="text-xs text-muted mt-1">Buat akun baru untuk Administrator, Petugas Laboran, atau Peminjam</p>
    </div>

    @if ($errors->any())
      <div class="mb-6 alert alert-error text-sm">
        <p class="font-semibold mb-1">Periksa isian berikut:</p>
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
      @csrf

      <!-- Role / Hak Akses -->
      <div>
        <label for="role" class="field-label block mb-1.5">
          Role / Hak Akses <span class="field-required">*</span>
        </label>
        <select id="role" name="role" required class="field-input font-semibold text-accent">
          <option value="peminjam" {{ old('role') === 'peminjam' ? 'selected' : '' }}>Peminjam (Mahasiswa)</option>
          <option value="laboran" {{ old('role') === 'laboran' ? 'selected' : '' }}>Petugas Laboran</option>
          <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
        </select>
      </div>

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
               placeholder="Contoh: Budi Santoso"
               class="field-input">
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="field-label block mb-1.5">
          Alamat Email <span class="field-required">*</span>
        </label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               maxlength="150"
               placeholder="budi@example.com"
               class="field-input">
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- NIM -->
        <div>
          <label for="nim" class="field-label block mb-1.5">
            NIM (Untuk Peminjam)
          </label>
          <input type="text"
                 id="nim"
                 name="nim"
                 value="{{ old('nim') }}"
                 pattern="[0-9]{8,20}"
                 placeholder="Contoh: 24051130099"
                 class="field-input font-mono">
        </div>

        <!-- No HP -->
        <div>
          <label for="phone" class="field-label block mb-1.5">
            Nomor HP / WhatsApp
          </label>
          <input type="text"
                 id="phone"
                 name="phone"
                 value="{{ old('phone') }}"
                 placeholder="08xxxxxxxxxx"
                 class="field-input">
        </div>
      </div>

      <!-- Password Awal -->
      <div>
        <label for="password" class="field-label block mb-1.5">
          Password Akun <span class="field-required">*</span>
        </label>
        <input type="password"
               id="password"
               name="password"
               required
               minlength="8"
               placeholder="Minimal 8 karakter"
               class="field-input">
      </div>

      <!-- Buttons -->
      <div class="flex flex-wrap items-center gap-3 pt-3 border-t divider-subtle">
        <button type="submit" class="btn">
          Buat Akun Pengguna
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
