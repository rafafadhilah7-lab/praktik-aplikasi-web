<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AlatManageController extends Controller
{
    /**
     * Menampilkan daftar seluruh alat inventaris untuk Admin & Laboran.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', '');

        $query = Alat::query();

        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                $builder->where('nama', 'like', "%{$q}%")
                    ->orWhere('kode', 'like', "%{$q}%")
                    ->orWhere('lokasi', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        if ($kategori !== '') {
            $query->where('kategori', $kategori);
        }

        $daftarAlat = $query->orderBy('nama')->paginate(10)->withQueryString();

        $daftarKategori = Alat::query()
            ->select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->orderBy('kategori')
            ->pluck('kategori');

        $totalAlat = Alat::count();
        $totalStok = (int) Alat::sum('stok');
        $stokRendah = Alat::where('stok', '>', 0)->where('stok', '<=', 2)->count();
        $stokHabis = Alat::where('stok', 0)->count();

        return view('admin.alat.index', compact(
            'daftarAlat',
            'daftarKategori',
            'q',
            'kategori',
            'totalAlat',
            'totalStok',
            'stokRendah',
            'stokHabis'
        ));
    }

    /**
     * Menampilkan formulir tambah alat baru.
     */
    public function create()
    {
        $daftarKategori = Alat::query()
            ->select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('admin.alat.create', compact('daftarKategori'));
    }

    /**
     * Menyimpan data alat baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', 'unique:alat,kode'],
            'nama' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'lokasi' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'kode.required' => 'Kode alat wajib diisi.',
            'kode.unique' => 'Kode alat sudah terdaftar dalam sistem.',
            'nama.required' => 'Nama alat laboratorium wajib diisi.',
            'kategori.required' => 'Kategori alat wajib ditentukan.',
            'stok.required' => 'Jumlah stok alat wajib diisi.',
            'stok.min' => 'Jumlah stok tidak boleh bernilai negatif.',
            'lokasi.required' => 'Lokasi penyimpanan alat wajib diisi.',
        ]);

        $alat = Alat::create($validated);

        return redirect()->route('admin.alat.index')
            ->with('success', "Alat \"{$alat->nama}\" ({$alat->kode}) berhasil ditambahkan ke inventaris.");
    }

    /**
     * Menampilkan formulir edit alat.
     */
    public function edit(int $id)
    {
        $alat = Alat::findOrFail($id);

        $daftarKategori = Alat::query()
            ->select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('admin.alat.edit', compact('alat', 'daftarKategori'));
    }

    /**
     * Memperbarui data alat di database.
     */
    public function update(Request $request, int $id)
    {
        $alat = Alat::findOrFail($id);

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('alat', 'kode')->ignore($alat->id)],
            'nama' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'lokasi' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'kode.required' => 'Kode alat wajib diisi.',
            'kode.unique' => 'Kode alat sudah terdaftar pada alat lain.',
            'nama.required' => 'Nama alat laboratorium wajib diisi.',
            'kategori.required' => 'Kategori alat wajib ditentukan.',
            'stok.required' => 'Jumlah stok alat wajib diisi.',
            'stok.min' => 'Jumlah stok tidak boleh bernilai negatif.',
            'lokasi.required' => 'Lokasi penyimpanan alat wajib diisi.',
        ]);

        // Verifikasi jika stok baru lebih kecil dari unit yang saat ini aktif dipinjam
        $terpakaiSekarang = Peminjaman::where('alat_id', $alat->id)
            ->whereIn('status', [Peminjaman::STATUS_DIPINJAM])
            ->sum('jumlah');

        if ((int) $validated['stok'] < $terpakaiSekarang) {
            throw ValidationException::withMessages([
                'stok' => "Stok tidak boleh lebih kecil dari unit yang sedang dipinjam saat ini ({$terpakaiSekarang} unit).",
            ]);
        }

        $alat->update($validated);

        return redirect()->route('admin.alat.index')
            ->with('success', "Data alat \"{$alat->nama}\" ({$alat->kode}) berhasil diperbarui.");
    }

    /**
     * Menghapus data alat secara aman (Soft Delete).
     */
    public function destroy(int $id)
    {
        $alat = Alat::findOrFail($id);

        // Jangan menghapus alat yang masih memiliki transaksi peminjaman aktif
        if ($alat->hasActiveLoans()) {
            return back()->with('error', "Alat \"{$alat->nama}\" tidak dapat dihapus karena masih memiliki transaksi peminjaman yang aktif (menunggu persetujuan, disetujui, atau sedang dipinjam).");
        }

        $namaAlat = $alat->nama;
        $alat->delete();

        return redirect()->route('admin.alat.index')
            ->with('success', "Alat \"{$namaAlat}\" berhasil dihapus dari inventaris.");
    }
}
