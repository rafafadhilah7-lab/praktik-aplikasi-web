<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use App\Services\PeminjamanLogger;
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
        $hariIni = now()->toDateString();

        $semuaAlat = Alat::query()
            ->orderBy('nama')
            ->get();

        $ketersediaanHariIni = [];
        $totalTersediaHariIni = 0;

        foreach ($semuaAlat as $alat) {
            $terpakai = Peminjaman::puncakTerpakai($alat->id, $hariIni, $hariIni);
            $sisa = max(0, (int) $alat->stok - $terpakai);
            $ketersediaanHariIni[$alat->id] = $sisa;

            if ($sisa > 0) {
                $totalTersediaHariIni++;
            }
        }

        $tersedia = $semuaAlat
            ->where('stok', '>', 0)
            ->take(6)
            ->values();

        $alatPopuler = Alat::query()
            ->withCount(['peminjaman as total_peminjaman' => function ($query) {
                $query->where('status', '!=', Peminjaman::STATUS_DITOLAK);
            }])
            ->orderByDesc('total_peminjaman')
            ->orderBy('nama')
            ->limit(4)
            ->get();

        if ($alatPopuler->count() < 4) {
            $existingIds = $alatPopuler->pluck('id')->all();
            $tambahan = Alat::query()
                ->where('stok', '>', 0)
                ->whereNotIn('id', $existingIds)
                ->orderBy('nama')
                ->limit(4 - $alatPopuler->count())
                ->get();

            $alatPopuler = $alatPopuler->concat($tambahan);
        }

        $daftarKategori = $this->daftarKategori();
        $totalAlat = $semuaAlat->count();
        $totalTersedia = $semuaAlat->where('stok', '>', 0)->count();
        $jumlahKategori = count($daftarKategori);
        $jumlahLokasi = Alat::query()
            ->whereNotNull('lokasi')
            ->where('lokasi', '!=', '')
            ->distinct('lokasi')
            ->count('lokasi');

        return view('pages.beranda', [
            'daftarKategori' => $daftarKategori,
            'tersedia' => $tersedia,
            'alatPopuler' => $alatPopuler,
            'ketersediaanHariIni' => $ketersediaanHariIni,
            'totalAlat' => $totalAlat,
            'totalTersedia' => $totalTersedia,
            'totalTersediaHariIni' => $totalTersediaHariIni,
            'jumlahKategori' => $jumlahKategori,
            'jumlahLokasi' => $jumlahLokasi,
        ]);
    }

    /**
     * Halaman hasil pencarian alat.
     */
    public function hasil(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', '');

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
     * bukan dari input form.
     * Setelah berhasil, pengguna diarahkan (redirect) ke halaman konfirmasi
     * agar refresh (F5) tidak mengirim ulang form.
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

        $peminjaman = DB::transaction(function () use ($data, $id, $user, $userId, $namaPeminjam, $nim) {
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

            $created = Peminjaman::create([
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

            PeminjamanLogger::catat(
                $user,
                $created,
                'pengajuan_dibuat',
                "Mengajukan {$created->jumlah} unit {$alat->nama} ({$created->tanggal_pinjam->format('d/m/Y')} - {$created->tanggal_selesai->format('d/m/Y')})."
            );

            PeminjamanLogger::kirimKeUser(
                $user,
                $created->id,
                'Pengajuan Berhasil Dikirim',
                "Pengajuan #{$created->id} untuk {$alat->nama} ({$created->jumlah} unit) sedang menunggu persetujuan laboran.",
                Peminjaman::STATUS_MENUNGGU,
                route('peminjaman.detail', $created->id)
            );

            PeminjamanLogger::kirimKeRole(
                User::ROLE_LABORAN,
                $created->id,
                'Pengajuan Baru Masuk',
                "Pengajuan #{$created->id} oleh {$namaPeminjam} untuk {$alat->nama} ({$created->jumlah} unit) perlu diverifikasi.",
                Peminjaman::STATUS_MENUNGGU,
                route('peminjaman.detail', $created->id)
            );

            return $created;
        });

        return redirect()->route('peminjaman.konfirmasi', $peminjaman->id);
    }

    /**
     * Halaman konfirmasi setelah pengajuan (GET, aman di-refresh).
     * Hanya pemilik pengajuan (atau laboran/admin menurut Policy) yang boleh melihat.
     */
    public function konfirmasi(int $id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        Gate::authorize('view', $peminjaman);

        // Disusun ulang dari data tersimpan agar view lama tetap berfungsi.
        $pengajuan = [
            'tanggal_pinjam' => Carbon::parse($peminjaman->tanggal_pinjam)->toDateString(),
            'durasi' => $peminjaman->durasi,
            'jumlah' => $peminjaman->jumlah,
            'keperluan' => $peminjaman->keperluan,
            'nama_peminjam' => $peminjaman->nama_peminjam,
            'nim' => $peminjaman->nim,
        ];

        return view('pages.konfirmasi-pinjam', [
            'alat' => $peminjaman->alat,
            'pengajuan' => $pengajuan,
            'peminjaman' => $peminjaman,
        ]);
    }
}