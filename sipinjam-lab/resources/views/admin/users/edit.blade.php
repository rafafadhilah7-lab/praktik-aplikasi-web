@extends('layouts.app')

@section('title', 'Edit Pengguna - ' . $user->name)

@section('content')
<div class="mx-auto max-w-xl px-4 py-6">
  <!-- Navigasi Kembali -->
  <a href="{{ route('admin.users.index') }}"
     class="mb-6 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-pink-600 hover:text-pink-700">
    &larr; Kembali ke daftar pengguna
  </a>

  <div class="overflow-hidden rounded-3xl border border-pink-100 bg-white p-6 sm:p-8 shadow-xl shadow-pink-100/30">
    <div class="mb-6">
      <div class="flex items-center gap-2 mb-1">
        <x-badge :status="$user->role" />
        <span class="text-xs text-slate-400">ID: #{{ $user->id }}</span>
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Edit Data Pengguna</h1>
      <p class="text-xs text-slate-500 mt-1">Ubah hak akses role, data identitas, atau reset password pengguna</p>
    </div>

    @if ($errors->any())
      <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
        <p class="font-semibold mb-1">Periksa isian berikut:</p>
        <ul class="list-inside list-disc space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <!-- Role / Hak Akses -->
      <div>
        <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
          Role / Hak Akses <span class="text-rose-500">*</span>
        </label>
        <select id="role" name="role" required class="field-input font-semibold text-pink-700">
          <option value="peminjam" {{ old('role', $user->role) === 'peminjam' ? 'selected' : '' }}>Peminjam (Mahasiswa)</option>
          <option value="laboran" {{ old('role', $user->role) === 'laboran' ? 'selected' : '' }}>Petugas Laboran</option>
          <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
        </select>
      </div>

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
        <!-- NIM -->
        <div>
          <label for="nim" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            NIM (Untuk Peminjam)
          </label>
          <input type="text"
                 id="nim"
                 name="nim"
                 value="{{ old('nim', $user->nim) }}"
                 pattern="[0-9]{8,20}"
                 class="field-input font-mono">
        </div>

        <!-- No HP -->
        <div>
          <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
            Nomor HP / WhatsApp
          </label>
          <input type="text"
                 id="phone"
                 name="phone"
                 value="{{ old('phone', $user->phone) }}"
                 class="field-input">
        </div>
      </div>

      <!-- Reset Password (Opsional) -->
      <div>
        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
          Password Baru (Kosongkan jika tidak ingin mengubah)
        </label>
        <input type="password"
               id="password"
               name="password"
               minlength="8"
               placeholder="Isi hanya jika ingin mereset password"
               class="field-input">
      </div>

      <!-- Buttons -->
      <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
        <button type="submit" class="btn">
          Perbarui Data Pengguna
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
