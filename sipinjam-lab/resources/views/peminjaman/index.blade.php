@extends('layouts.app')

@section('title', 'Manajemen Transaksi Peminjaman')

@section('content')
<div class="space-y-6">
  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Seluruh Transaksi Peminjaman</h1>
      <p class="text-xs text-slate-500 mt-1">Daftar lengkap permohonan, riwayat, dan status peminjaman laboratorium</p>
    </div>
  </div>

  <!-- Search & Filter Card -->
  <div class="card p-5 bg-white border-pink-100 shadow-sm">
    <form method="GET" action="{{ route('peminjaman.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
      <!-- Search Input -->
      <div class="lg:col-span-2">
        <label for="q" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
          Cari (Peminjam / NIM / Alat)
        </label>
        <input type="text"
               id="q"
               name="q"
               value="{{ $q }}"
               placeholder="Ketik nama mahasiswa, NIM, atau alat..."
               class="field-input">
      </div>

      <!-- Filter Status -->
      <div>
        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
          Status Transaksi
        </label>
        <select id="status" name="status" class="field-input">
          <option value="">Semua Status</option>
          @foreach($statusList as $st)
            <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>
              {{ ucfirst($st) }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filter Tanggal Mulai -->
      <div>
        <label for="tanggal_mulai" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
          Tanggal Pinjam Dari
        </label>
        <input type="date"
               id="tanggal_mulai"
               name="tanggal_mulai"
               value="{{ $tanggalMulai }}"
               class="field-input">
      </div>

      <!-- Submit & Reset Buttons -->
      <div class="flex gap-2">
        <button type="submit" class="btn btn-sm w-full font-semibold">
          Filter
        </button>
        @if($q !== '' || $status !== '' || $tanggalMulai !== '' || $tanggalSelesai !== '')
          <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary btn-sm" title="Reset Filter">
            Reset
          </a>
        @endif
      </div>
    </form>
  </div>

  <!-- Tabel Transaksi -->
  <div class="table-container">
    <table class="data-table">
      <thead>
        <tr>
          <th>ID / Tgl</th>
          <th>Peminjam</th>
          <th>Alat & Lokasi</th>
          <th>Jadwal & Durasi</th>
          <th>Unit</th>
          <th>Status</th>
          <th class="text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($daftarPeminjaman as $item)
          <tr>
            <td>
              <span class="font-mono font-bold text-xs text-pink-700">#{{ $item->id }}</span>
              <div class="text-xs text-slate-400">{{ $item->created_at->format('d/m/Y') }}</div>
            </td>
            <td>
              <div class="font-bold text-slate-900">{{ $item->nama_peminjam }}</div>
              <div class="text-xs text-slate-500 font-mono">NIM: {{ $item->nim }}</div>
            </td>
            <td>
              <div class="font-semibold text-slate-900">{{ $item->alat->nama }}</div>
              <div class="text-xs text-slate-500 font-mono">{{ $item->alat->kode }} ({{ $item->alat->lokasi }})</div>
            </td>
            <td>
              <div class="text-xs font-semibold text-slate-800">{{ $item->tanggal_pinjam->format('d M') }} - {{ $item->tanggal_selesai->format('d M Y') }}</div>
              <div class="text-xs text-slate-500">{{ $item->durasi }} hari</div>
            </td>
            <td>
              <span class="font-extrabold text-xs text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
                {{ $item->jumlah }} unit
              </span>
            </td>
            <td>
              <x-badge :status="$item->status" />
            </td>
            <td class="text-right">
              <div class="flex items-center justify-end gap-1.5">
                @if($item->isMenunggu())
                  <form action="{{ route('peminjaman.setujui', $item->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan #{{ $item->id }}?');" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm px-2 text-xs" title="Setujui">
                      Setujui
                    </button>
                  </form>
                  <button type="button"
                          onclick="openRejectModal({{ $item->id }}, '{{ $item->nama_peminjam }}', '{{ $item->alat->nama }}')"
                          class="btn btn-danger btn-sm px-2 text-xs" title="Tolak">
                    Tolak
                  </button>
                @elseif($item->isDisetujui())
                  <form action="{{ route('peminjaman.serahkan', $item->id) }}" method="POST" onsubmit="return confirm('Serahkan alat kepada peminjam? Status akan berubah menjadi Dipinjam.');" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-sm px-2 text-xs bg-purple-600 text-white hover:bg-purple-700" title="Serahkan Alat">
                      Serahkan
                    </button>
                  </form>
                @elseif($item->isDipinjam())
                  <form action="{{ route('peminjaman.kembalikan', $item->id) }}" method="POST" onsubmit="return confirm('Alat telah dikembalikan? Status akan berubah menjadi Dikembalikan dan stok dirilis.');" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm px-2 text-xs" title="Kembalikan Alat">
                      Kembalikan
                    </button>
                  </form>
                @endif

                <a href="{{ route('peminjaman.detail', $item->id) }}" class="btn btn-secondary btn-sm px-2 text-xs">
                  Detail
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-10 text-slate-400">
              Tidak ada data transaksi peminjaman yang ditemukan.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($daftarPeminjaman->hasPages())
    <div class="mt-4">
      {{ $daftarPeminjaman->links() }}
    </div>
  @endif
</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-rose-100">
    <div class="flex items-center gap-3 mb-4 text-rose-600">
      <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>
      <div>
        <h3 class="text-lg font-bold text-slate-900">Tolak Pengajuan Peminjaman</h3>
        <p class="text-xs text-slate-500" id="rejectSubtitle">Pengajuan #</p>
      </div>
    </div>

    <form id="rejectForm" method="POST" action="">
      @csrf
      <div class="mb-4">
        <label for="alasan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
          Alasan Penolakan <span class="text-rose-500">*</span>
        </label>
        <textarea id="alasan"
                  name="alasan"
                  rows="3"
                  required
                  placeholder="Contoh: Alat sedang dalam kalibrasi atau tidak memenuhi syarat."
                  class="w-full rounded-xl border border-slate-200 p-3 text-sm focus:border-rose-500 focus:outline-none focus:ring-4 focus:ring-rose-100 transition-all"></textarea>
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeRejectModal()" class="btn btn-secondary btn-sm">
          Batal
        </button>
        <button type="submit" class="btn btn-danger btn-sm">
          Konfirmasi Tolak
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  function openRejectModal(id, peminjam, alat) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    const subtitle = document.getElementById('rejectSubtitle');

    subtitle.textContent = '#' + id + ' (' + peminjam + ' - ' + alat + ')';
    form.action = '/peminjaman/' + id + '/tolak';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('alasan').focus();
  }

  function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
</script>
@endpush
@endsection
