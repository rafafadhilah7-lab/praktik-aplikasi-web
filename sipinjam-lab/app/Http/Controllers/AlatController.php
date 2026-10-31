<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AlatController extends Controller
{
    /**
     * Mengambil daftar kategori unik dari database.
     */
    private function daftarKategori(): array
    {
        return Alat::query()
            ->select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->orderBy('kategori')
            ->pluck('kategori')
            ->all();
    }

    /**
     * Halaman beranda.
     */
    public function beranda()
    {
        $tersedia = Alat::query()
            ->where('stok', '>', 0)
            ->limit(6)
            ->get();

        $totalAlat = Alat::count();
        $totalTersedia = Alat::where('stok', '>', 0)->count();

        return view('pages.beranda', [
            'daftarKategori' => $this->daftarKategori(),
            'tersedia' => $tersedia,
            'totalAlat' => $totalAlat,
            'totalTersedia' => $totalTersedia,
        ]);
    }

    /**
     * Halaman hasil pencarian alat.
     */
    public function hasil(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', '');
        $tanggal = (string) $request->query('tanggal', '');

        $query = Alat::query();

        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                $builder->where('nama', 'like', "%{$q}%")
                    ->orWhere('kode', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");
            });
        }

        if ($kategori !== '') {
            $query->where('kategori', $kategori);
        }

        $hasil = $query->paginate(9)->withQueryString();

        return view('pages.hasil', [
            'hasil' => $hasil,
            'q' => $q,
            'kategori' => $kategori,
            'tanggal' => $tanggal,
            'daftarKategori' => $this->daftarKategori(),
        ]);
    }

    /**
     * Halaman detail alat.
     */
    public function detail(int $id)
    {
        $alat = Alat::findOrFail($id);

        return view('pages.detail-alat', compact('alat'));
    }

    /**
     * Halaman formulir peminjaman.
     */
    public function formPinjam(int $id)
    {
        $alat = Alat::findOrFail($id);

        if ($alat->stok < 1) {
            return redirect()
                ->route('alat.detail', $id)
                ->with('error', 'Alat sedang tidak tersedia untuk dipinjam.');
        }

        $user = Auth::user();

        return view('pages.form-pinjam', compact('alat', 'user'));
    }

    /**
     * Memvalidasi dan menyimpan pengajuan peminjaman.
     */
    public function ajukan(Request $request, int $id)
    {
        $data = $request->validate([
            'nama_peminjam' => [
                'required',
                'string',
                'max:100',
            ],
            'nim' => [
                'required',
                'string',
                'regex:/^[0-9]{8,20}$/',
            ],
            'tanggal_pinjam' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'durasi' => [
                'required',
                'integer',
                'min:1',
                'max:30',
            ],
            'jumlah' => [
                'required',
                'integer',
                'min:1',
            ],
            'keperluan' => [
                'required',
                'string',
                'max:500',
            ],
        ], [
            'nama_peminjam.required' => 'Nama peminjam wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex' => 'NIM harus berupa 8 sampai 20 digit angka.',
            'tanggal_pinjam.required' => 'Tanggal peminjaman wajib ditentukan.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal peminjaman tidak boleh di masa lampau.',
            'durasi.required' => 'Durasi peminjaman wajib diisi.',
            'durasi.min' => 'Durasi peminjaman minimal 1 hari.',
            'durasi.max' => 'Durasi peminjaman maksimal 30 hari.',
            'jumlah.required' => 'Jumlah unit alat wajib diisi.',
            'jumlah.min' => 'Jumlah unit alat minimal 1 unit.',
            'keperluan.required' => 'Keperluan peminjaman wajib dijelaskan.',
        ]);

        $userId = Auth::id();

        $peminjaman = DB::transaction(function () use ($data, $id, $userId) {
            $alat = Alat::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if ($alat->stok < 1) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Alat sedang tidak tersedia untuk dipinjam.',
                ]);
            }

            if ((int) $data['jumlah'] > (int) $alat->stok) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah peminjaman melebihi total stok alat yang ada (' . $alat->stok . ' unit).',
                ]);
            }

            $tanggalMulai = $data['tanggal_pinjam'];

            $tanggalSelesai = Carbon::parse($tanggalMulai)
                ->addDays((int) $data['durasi'] - 1)
                ->toDateString();

            $terpakai = Peminjaman::puncakTerpakai(
                $alat->id,
                $tanggalMulai,
                $tanggalSelesai
            );

            if (
                $terpakai + (int) $data['jumlah'] > (int) $alat->stok
            ) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Stok tidak mencukupi pada tanggal tersebut. Sudah teralokasi ' . $terpakai . ' unit dari total ' . $alat->stok . ' unit. Silakan pilih tanggal atau jumlah lain.',
                ]);
            }

            return Peminjaman::create([
                'user_id' => $userId,
                'alat_id' => $alat->id,
                'nama_peminjam' => $data['nama_peminjam'],
                'nim' => $data['nim'],
                'tanggal_pinjam' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi' => (int) $data['durasi'],
                'jumlah' => (int) $data['jumlah'],
                'keperluan' => $data['keperluan'],
                'status' => Peminjaman::STATUS_MENUNGGU,
            ]);
        });

        $peminjaman->load('alat');

        return view('pages.konfirmasi-pinjam', [
            'alat' => $peminjaman->alat,
            'pengajuan' => $data,
            'peminjaman' => $peminjaman,
        ]);
    }
}
