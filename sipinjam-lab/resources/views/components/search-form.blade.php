@props([
    'q' => '',
    'kategori' => '',
    'daftarKategori' => [],
])

<form method="GET" action="{{ route('alat.hasil') }}" class="card" role="search">
    <div class="card-body">
        <div class="grid gap-4 md:grid-cols-[1fr_220px_auto] md:items-end">
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

            <div class="flex items-center gap-2 mb-4">
                <x-button type="submit" class="w-full md:w-auto">
                    Cari Alat
                </x-button>

                @if ($q !== '' || $kategori !== '')
                    <a href="{{ route('alat.hasil') }}" class="btn btn-secondary">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </div>
</form>
