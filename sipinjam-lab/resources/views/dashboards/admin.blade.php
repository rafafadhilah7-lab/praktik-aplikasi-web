@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-[#e5e7eb]">
    <div>
      <h1 class="text-xl font-bold text-[#202938]">Dashboard Administrator</h1>
      <p class="text-xs text-[#64748b] mt-0.5">Monitoring operasional, inventaris peralatan laboratorium, dan seluruh transaksi.</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.alat.create') }}" class="btn btn-primary btn-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        <span>Tambah Alat</span>
      </a>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
        </svg>
        <span>Kelola Pengguna</span>
      </a>
    </div>
  </div>

  <!-- KPI Metrics Grid (Compact Enterprise KPI) -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="kpi-card">
      <div class="kpi-icon bg-blue-50 text-blue-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 00-3-3.87m-3-12a4 4 0 010 7.75"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Total Pengguna</div>
        <div class="kpi-value">{{ $totalUsers }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-slate-100 text-slate-700">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Total Inventaris</div>
        <div class="kpi-value">{{ $totalAlat }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-indigo-50 text-indigo-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Total Pengajuan</div>
        <div class="kpi-value">{{ $totalPengajuan }}</div>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-icon bg-amber-50 text-amber-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div>
        <div class="kpi-label">Pengajuan Menunggu</div>
        <div class="kpi-value text-amber-600">{{ $pengajuanMenunggu }}</div>
      </div>
    </div>
  </div>

  <!-- Main Content Layout (Table & Side Widgets) -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Tabel Pengajuan Terbaru (2/3) -->
    <div class="lg:col-span-2 space-y-4">
      <div class="card overflow-hidden">
        <div class="card-header">
          <div>
            <h3 class="card-title">Pengajuan Peminjaman Terbaru</h3>
            <p class="text-xs text-[#64748b] mt-0.5">Daftar transaksi permohonan terkini yang masuk ke laboratorium</p>
          </div>
          <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm">
            Semua Transaksi &rarr;
          </a>
        </div>

        <div class="overflow-x-auto">
          <table class="enterprise-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Peminjam & NIM</th>
                <th>Alat</th>
                <th>Jadwal</th>
                <th>Qty</th>
                <th>Status</th>
                <th class="text-right">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($pengajuanTerbaru as $item)
                <tr>
                  <td class="font-mono text-xs font-semibold text-slate-700">#{{ $item->id }}</td>
                  <td>
                    <div class="font-medium text-[#202938]">{{ $item->nama_peminjam }}</div>
                    <div class="text-[11px] text-[#64748b] font-mono">{{ $item->nim }}</div>
                  </td>
                  <td>
                    <div class="font-medium text-[#202938]">{{ $item->alat->nama ?? '-' }}</div>
                    <div class="text-[11px] text-[#64748b] font-mono">{{ $item->alat->kode ?? '-' }}</div>
                  </td>
                  <td class="text-xs">
                    <div>{{ $item->tanggal_pinjam->format('d/m/Y') }}</div>
                    <div class="text-[11px] text-[#64748b]">{{ $item->durasi }} hari</div>
                  </td>
                  <td class="font-semibold">{{ $item->jumlah }}</td>
                  <td>
                    <x-badge :status="$item->status" />
                  </td>
                  <td class="text-right">
                    <a href="{{ route('peminjaman.detail', $item->id) }}" class="btn btn-secondary btn-sm">
                      Detail
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-8 text-slate-400 text-xs">
                    Belum ada pengajuan peminjaman di sistem.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Status Breakdown & Low Stock Table (1/3) -->
    <div class="space-y-6">
      <!-- Tabel Stok Alat Rendah -->
      <div class="card overflow-hidden">
        <div class="card-header">
          <div class="flex items-center justify-between w-full">
            <h3 class="card-title text-sm">Peringatan Stok Rendah (&le; 2)</h3>
            <span class="badge badge-warning text-[10px]">{{ $alatStokRendah->count() }} Item</span>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="enterprise-table">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama Alat</th>
                <th>Stok</th>
                <th class="text-right">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($alatStokRendah as $alat)
                <tr>
                  <td><span class="code-badge">{{ $alat->kode }}</span></td>
                  <td class="font-medium text-xs truncate max-w-[120px]">{{ $alat->nama }}</td>
                  <td>
                    <span class="font-bold text-xs {{ $alat->stok === 0 ? 'text-red-600' : 'text-amber-600' }}">
                      {{ $alat->stok }} Unit
                    </span>
                  </td>
                  <td class="text-right">
                    <a href="{{ route('admin.alat.edit', $alat->id) }}" class="text-xs text-blue-600 hover:underline font-medium">
                      Edit
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center py-6 text-slate-400 text-xs">
                    Seluruh alat memiliki stok mencukupi (> 2 unit).
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Ringkasan Status Sirkulasi -->
      <div class="card p-4">
        <h3 class="text-sm font-semibold text-[#202938] mb-3">Distribusi Status Peminjaman</h3>
        <div class="space-y-2 text-xs">
          <div class="flex items-center justify-between p-2 rounded bg-amber-50/60 border border-amber-100">
            <span class="text-amber-900 font-medium">Menunggu Verifikasi</span>
            <span class="font-bold text-amber-950">{{ $pengajuanMenunggu }}</span>
          </div>
          <div class="flex items-center justify-between p-2 rounded bg-blue-50/60 border border-blue-100">
            <span class="text-blue-900 font-medium">Disetujui (Siap Ambil)</span>
            <span class="font-bold text-blue-950">{{ $pengajuanDisetujui }}</span>
          </div>
          <div class="flex items-center justify-between p-2 rounded bg-sky-50/60 border border-sky-100">
            <span class="text-sky-900 font-medium">Sedang Dipinjam</span>
            <span class="font-bold text-sky-950">{{ $alatDipinjam }}</span>
          </div>
          <div class="flex items-center justify-between p-2 rounded bg-emerald-50/60 border border-emerald-100">
            <span class="text-emerald-900 font-medium">Selesai Dikembalikan</span>
            <span class="font-bold text-emerald-950">{{ $alatDikembalikan }}</span>
          </div>
          <div class="flex items-center justify-between p-2 rounded bg-red-50/60 border border-red-100">
            <span class="text-red-900 font-medium">Ditolak</span>
            <span class="font-bold text-red-950">{{ $pengajuanDitolak }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
