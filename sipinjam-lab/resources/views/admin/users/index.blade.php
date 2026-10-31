@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Admin')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Manajemen Pengguna & Role</h1>
      <p class="text-xs text-slate-500 mt-1">Kelola data seluruh akun terdaftar, hak akses, dan status pengguna</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sm">
      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
      </svg>
      Tambah Pengguna Baru
    </a>
  </div>

  <!-- Role Counters -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
    <div class="p-3.5 rounded-2xl bg-white border border-purple-100 shadow-sm flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Administrator</div>
        <div class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $countAdmin }} Akun</div>
      </div>
      <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
        A
      </div>
    </div>

    <div class="p-3.5 rounded-2xl bg-white border border-sky-100 shadow-sm flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold uppercase tracking-wider text-sky-600">Petugas Laboran</div>
        <div class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $countLaboran }} Akun</div>
      </div>
      <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold">
        L
      </div>
    </div>

    <div class="p-3.5 rounded-2xl bg-white border border-pink-100 shadow-sm flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold uppercase tracking-wider text-pink-600">Peminjam (Mahasiswa)</div>
        <div class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $countPeminjam }} Akun</div>
      </div>
      <div class="w-9 h-9 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center font-bold">
        P
      </div>
    </div>
  </div>

  <!-- Search & Role Filter -->
  <div class="card p-4 bg-white border-pink-100 shadow-sm">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div class="sm:col-span-2">
        <input type="text"
               name="q"
               value="{{ $q }}"
               placeholder="Cari berdasarkan nama, email, atau NIM..."
               class="field-input">
      </div>
      <div class="flex gap-2">
        <select name="role" class="field-input">
          <option value="">Semua Role</option>
          <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="laboran" {{ $role === 'laboran' ? 'selected' : '' }}>Laboran</option>
          <option value="peminjam" {{ $role === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
        </select>
        <button type="submit" class="btn btn-sm">Filter</button>
        @if($q !== '' || $role !== '')
          <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        @endif
      </div>
    </form>
  </div>

  <!-- Table -->
  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>Nama Pengguna</th>
          <th>Email</th>
          <th>Role / Hak Akses</th>
          <th>NIM</th>
          <th>No. HP</th>
          <th>Tgl Daftar</th>
          <th class="text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($daftarUser as $u)
          <tr>
            <td>
              <div class="font-bold text-slate-900 flex items-center gap-2">
                {{ $u->name }}
                @if($u->id === Auth::id())
                  <span class="text-[10px] bg-pink-100 text-pink-800 font-extrabold px-1.5 py-0.5 rounded">Anda</span>
                @endif
              </div>
            </td>
            <td>
              <span class="text-xs font-mono text-slate-600">{{ $u->email }}</span>
            </td>
            <td>
              <x-badge :status="$u->role" />
            </td>
            <td>
              <span class="text-xs font-mono">{{ $u->nim ?? '-' }}</span>
            </td>
            <td>
              <span class="text-xs text-slate-600">{{ $u->phone ?? '-' }}</span>
            </td>
            <td>
              <span class="text-xs text-slate-400">{{ $u->created_at->format('d/m/Y') }}</span>
            </td>
            <td class="text-right">
              <div class="flex items-center justify-end gap-1.5">
                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-secondary btn-sm px-2 text-xs">
                  Edit
                </a>
                @if($u->id !== Auth::id())
                  <form action="{{ route('admin.users.destroy', $u->id) }}"
                        method="POST"
                        onsubmit="return confirm('Hapus akun {{ $u->name }}?');"
                        class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-2 text-xs">
                      Hapus
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-10 text-slate-400">
              Tidak ada data pengguna yang sesuai.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($daftarUser->hasPages())
    <div class="mt-4">
      {{ $daftarUser->links() }}
    </div>
  @endif
</div>
@endsection
