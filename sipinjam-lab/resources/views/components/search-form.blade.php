@props([
    'q' => '',
    'kategori' => '',
    'tanggal' => '',
    'daftarKategori' => [],
])

<form method="GET" action="{{ route('alat.hasil') }}" role="search" class="space-y-3">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
        <div class="form-group">
            <label for="field-q" class="form-label">Nama / Kode Alat</label>
            <input type="text"
                   id="field-q"
                   name="q"
                   value="{{ $q }}"
                   placeholder="Contoh: Osiloskop, Multimeter..."
                   class="form-input">
        </div>

        <div class="form-group">
            <label for="field-kategori" class="form-label">Bidang Kategori</label>
            <select id="field-kategori" name="kategori" class="form-input">
                <option value="">Semua Kategori</option>
                @foreach ($daftarKategori as $k)
                    <option value="{{ $k }}" @selected($kategori === $k)>
                        {{ $k }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="field-tanggal" class="form-label">Tanggal Peminjaman</label>
            <input type="date"
                   id="field-tanggal"
                   name="tanggal"
                   value="{{ $tanggal }}"
                   class="form-input">
        </div>

        <div>
            <button type="submit" class="btn btn-primary w-full">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3"/>
                </svg>
                <span>Cari Alat</span>
            </button>
        </div>
    </div>
</form>
