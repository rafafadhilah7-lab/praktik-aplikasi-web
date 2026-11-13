<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
     * Apakah NIM pada akun sudah lengkap dan valid (8-20 digit angka).
     */
    private function nimAkunValid($user): bool
    {
        return $user !== null
            && is_string($user->nim)
            && preg_match('/^[0-9]{8,20}$/', $user->nim) === 1;
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

        if (! $this->nimAkunValid($user)) {
            return redirect()
                ->route('profil.edit')
                ->with('error', 'Lengkapi NIM (8-20 digit angka) pada profil Anda sebelum mengajukan peminjaman.');
        }

        return view('pages.form-pinjam', compact('alat', 'user'));
    }

    /**
     * Memvalidasi dan menyimpan pengajuan peminjaman.
     * Nama dan NIM peminjam selalu diambil dari akun yang login,
     * bukan dari input form. Setelah berhasil, diarahkan ke halaman
     * konfirmasi (pola Post/Redirect/Get) agar refresh tidak
     * membuat pengajuan ganda.
     */
    public function ajukan(Request $request, int $id)
    {
        $user = Auth::user();

        if (! $this->nimAkunValid($user)) {
            return redirect()
                ->route('profil.edit')
                ->with('error', 'Lengkapi NIM (8-20 digit angka) pada profil Anda sebelum mengajukan peminjaman.');
        }

        $data = $request->validate([
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
            'tanggal_pinjam.required' => 'Tanggal peminjaman wajib ditentukan.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal peminjaman tidak boleh di masa lampau.',
            'durasi.required' => 'Durasi peminjaman wajib diisi.',
            'durasi.min' => 'Durasi peminjaman minimal 1 hari.',
            'durasi.max' => 'Durasi peminjaman maksimal 30 hari.',
            'jumlah.required' => 'Jumlah unit alat wajib diisi.',
            'jumlah.min' => 'Jumlah unit alat minimal 1 unit.',
            'keperluan.required' => 'Keperluan peminjaman wajib dijelaskan.',
        ]);

        // Identitas peminjam dari akun, bukan dari form.
        $userId = $user->id;
        $namaPeminjam = $user->name;
        $nim = $user->nim;

        $peminjaman = DB::transaction(function () use ($data, $id, $userId, $namaPeminjam, $nim) {
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
                'nama_peminjam' => $namaPeminjam,
                'nim' => $nim,
                'tanggal_pinjam' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi' => (int) $data['durasi'],
                'jumlah' => (int) $data['jumlah'],
                'keperluan' => $data['keperluan'],
                'status' => Peminjaman::STATUS_MENUNGGU,
            ]);
        });

        return redirect()->route('peminjaman.konfirmasi', $peminjaman->id);
    }

    /**
     * Halaman konfirmasi setelah pengajuan tersimpan.
     * Hanya pemilik pengajuan yang boleh membukanya (PeminjamanPolicy@view).
     */
    public function konfirmasi(int $id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        Gate::authorize('view', $peminjaman);

        return view('pages.konfirmasi-pinjam', [
            'alat' => $peminjaman->alat,
            'peminjaman' => $peminjaman,
        ]);
    }
}