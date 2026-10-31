@extends('layouts.app')

@section('title', 'Manajemen Inventaris Alat - Admin')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Manajemen Alat Laboratorium</h1>
      <p class="text-xs text-slate-500 mt-1">Kelola data alat, stok fisik, lokasi penyimpanan, dan status ketersediaan</p>
    </div>
    <a href="{{ route('admin.alat.create') }}" class="btn btn-sm">
      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      Tambah Alat Baru
    </a>
  </div>

  <!-- Metric Badges -->
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
    <div class="p-3.5 rounded-2xl bg-white border border-pink-100 shadow-sm">
      <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Jenis Alat</div>
      <div class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $totalAlat }} Alat</div>
    </div>
    <div class="p-3.5 rounded-2xl bg-white border border-pink-100 shadow-sm">
      <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Stok Fisik</div>
      <div class="text-xl font-extrabold text-pink-700 mt-0.5">{{ $totalStok }} Unit</div>
    </div>
    <div class="p-3.5 rounded-2xl bg-white border border-pink-100 shadow-sm">
      <div class="text-[11px] font-bold uppercase tracking-wider text-amber-500">Stok Rendah (&le;2)</div>
      <div class="text-xl font-extrabold text-amber-600 mt-0.5">{{ $stokRendah }} Alat</div>
    </div>
    <div class="p-3.5 rounded-2xl bg-white border border-pink-100 shadow-sm">
      <div class="text-[11px] font-bold uppercase tracking-wider text-rose-500">Stok Habis (0)</div>
      <div class="text-xl font-extrabold text-rose-600 mt-0.5">{{ $stokHabis }} Alat</div>
    </div>
  </div>

  <!-- Search & Filter Card -->
  <div class="card p-4 bg-white border-pink-100 shadow-sm">
    <form method="GET" action="{{ route('admin.alat.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <div class="sm:col-span-2">
        <input type="text"
               name="q"
               value="{{ $q }}"
               placeholder="Cari berdasarkan nama, kode alat, lokasi, atau deskripsi..."
               class="field-input">
      </div>
      <div class="flex gap-2">
        <select name="kategori" class="field-input">
          <option value="">Semua Kategori</option>
          @foreach($daftarKategori as $kat)
            <option value="{{ $kat }}" {{ $kategori === $kat ? 'selected' : '' }}>
              {{ $kat }}
            </option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-sm">Cari</button>
        @if($q !== '' || $kategori !== '')
          <a href="{{ route('admin.alat.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        @endif
      </div>
    </form>
  </div>

  <!-- Table -->
  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>Kode</th>
          <th>Nama Alat</th>
          <th>Kategori</th>
          <th>Stok Unit</th>
          <th>Lokasi Rak/Lab</th>
          <th>Status</th>
          <th class="text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($daftarAlat as $alat)
          <tr>
            <td>
              <span class="code-label">{{ $alat->kode }}</span>
            </td>
            <td>
              <div class="font-bold text-slate-900">{{ $alat->nama }}</div>
              <div class="text-xs text-slate-500 line-clamp-1 max-w-xs">{{ $alat->deskripsi ?? 'Tidak ada deskripsi.' }}</div>
            </td>
            <td>
              <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                {{ $alat->kategori }}
              </span>
            </td>
            <td>
              <span class="font-bold text-sm {{ $alat->stok > 0 ? 'text-slate-900' : 'text-rose-600' }}">
                {{ $alat->stok }} Unit
              </span>
            </td>
            <td>
              <span class="text-xs text-slate-600">{{ $alat->lokasi }}</span>
            </td>
            <td>
              <x-badge :status="$alat->stok > 0 ? 'tersedia' : 'habis'" />
            </td>
            <td class="text-right">
              <div class="flex items-center justify-end gap-1.5">
                <a href="{{ route('admin.alat.edit', $alat->id) }}" class="btn btn-secondary btn-sm px-2 text-xs" title="Edit Alat">
                  Edit
                </a>
                <form action="{{ route('admin.alat.destroy', $alat->id) }}"
                      method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat {{ $alat->nama }} ({{ $alat->kode }}) dari sistem? Transaksi aktif akan diverifikasi sebelum penghapusan.');"
                      class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm px-2 text-xs" title="Hapus Alat">
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-10 text-slate-400">
              Tidak ada data alat yang ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($daftarAlat->hasPages())
    <div class="mt-4">
      {{ $daftarAlat->links() }}
    </div>
  @endif
</div>
@endsection
