
@props([
    'q' => '',
    'kategori' => '',
    'tanggal' => '',
    'daftarKategori' => [],
])

<form method="GET" action="{{ route('alat.hasil') }}" class="card" role="search">
    <div class="card-body">
        <div class="grid gap-4 md:grid-cols-4 md:items-end">
            <x-field
                name="q"
                label="Nama alat"
                :value="$q"
                placeholder="Contoh: Multimeter"
            />

            <div class="field">
                <label class="field-label" for="field-kategori">
                    Kategori
                </label>

                <select
                    class="field-input"
                    id="field-kategori"
                    name="kategori"
                >
                    <option value="">Semua kategori</option>

                    @foreach ($daftarKategori as $k)
                        <option
                            value="{{ $k }}"
                            @selected($kategori === $k)
                        >
                            {{ $k }}
                        </option>
                    @endforeach
                </select>
            </div>

            <x-field
                name="tanggal"
                label="Tanggal pinjam"
                type="date"
                :value="$tanggal"
            />

            <x-button type="submit" class="w-full">
                Cari Alat
            </x-button>
        </div>
    </div>
</form>
