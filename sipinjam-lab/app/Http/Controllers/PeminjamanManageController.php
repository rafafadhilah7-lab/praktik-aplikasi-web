<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanManageController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi peminjaman (untuk Laboran dan Admin).
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');
        $tanggalMulai = (string) $request->query('tanggal_mulai', '');
        $tanggalSelesai = (string) $request->query('tanggal_selesai', '');

        $query = Peminjaman::with(['alat', 'user'])->latest();

        // Pencarian berdasarkan nama peminjam, NIM, atau nama/kode alat
        if ($q !== '') {
            $query->where(function ($b) use ($q) {
                $b->where('nama_peminjam', 'like', "%{$q}%")
                    ->orWhere('nim', 'like', "%{$q}%")
                    ->orWhereHas('alat', function ($sub) use ($q) {
                        $sub->where('nama', 'like', "%{$q}%")
                            ->orWhere('kode', 'like', "%{$q}%");
                    });
            });
        }

        // Filter berdasarkan status
        if ($status !== '' && in_array($status, Peminjaman::STATUS, true)) {
            $query->where('status', $status);
        }

        // Filter rentang tanggal
        if ($tanggalMulai !== '') {
            $query->whereDate('tanggal_pinjam', '>=', $tanggalMulai);
        }
        if ($tanggalSelesai !== '') {
            $query->whereDate('tanggal_selesai', '<=', $tanggalSelesai);
        }

        $daftarPeminjaman = $query->paginate(15)->withQueryString();

        return view('peminjaman.index', [
            'daftarPeminjaman' => $daftarPeminjaman,
            'q' => $q,
            'status' => $status,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'statusList' => Peminjaman::STATUS,
        ]);
    }

    /**
     * Menampilkan detail satu transaksi pengajuan peminjaman.
     */
    public function detail(int $id)
    {
        $peminjaman = Peminjaman::with(['alat', 'user'])->findOrFail($id);
        $user = Auth::user();

        // Otorisasi: Peminjam hanya boleh melihat miliknya sendiri
        if ($user->isPeminjam() && $peminjaman->user_id !== $user->id && $peminjaman->nim !== $user->nim) {
            abort(403, 'Anda tidak berhak melihat data pengajuan peminjaman milik pengguna lain.');
        }

        return view('peminjaman.detail', compact('peminjaman'));
    }

    /**
     * Laboran/Admin menyetujui pengajuan peminjaman.
     */
    public function setujui(Request $request, int $id)
    {
        $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $id) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU) {
                throw ValidationException::withMessages([
                    'error' => 'Hanya pengajuan dengan status "menunggu" yang dapat disetujui.',
                ]);
            }

            $alat = Alat::lockForUpdate()->findOrFail($peminjaman->alat_id);

            // Verifikasi kembali ketersediaan stok pada rentang tanggal tersebut
            $terpakai = Peminjaman::puncakTerpakai(
                $alat->id,
                $peminjaman->tanggal_pinjam->format('Y-m-d'),
                $peminjaman->tanggal_selesai->format('Y-m-d'),
                $peminjaman->id
            );

            if ($terpakai + (int) $peminjaman->jumlah > (int) $alat->stok) {
                throw ValidationException::withMessages([
                    'error' => 'Kapasitas stok alat tidak mencukupi untuk rentang tanggal pengajuan ini.',
                ]);
            }

            $peminjaman->status = Peminjaman::STATUS_DISETUJUI;
            $peminjaman->tanggal_persetujuan = Carbon::now();
            if ($request->filled('catatan')) {
                $peminjaman->catatan_petugas = $request->input('catatan');
            }
            $peminjaman->save();
        });

        return back()->with('success', "Pengajuan #{$id} berhasil disetujui. Alat siap diserahkan kepada peminjam.");
    }

    /**
     * Laboran/Admin menolak pengajuan peminjaman.
     */
    public function tolak(Request $request, int $id)
    {
        $data = $request->validate([
            'alasan' => ['required', 'string', 'max:500'],
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi agar peminjam memahami penyebabnya.',
        ]);

        DB::transaction(function () use ($data, $id) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($id);

            if (!in_array($peminjaman->status, [Peminjaman::STATUS_MENUNGGU, Peminjaman::STATUS_DISETUJUI], true)) {
                throw ValidationException::withMessages([
                    'error' => 'Hanya pengajuan yang sedang menunggu atau baru disetujui yang dapat ditolak.',
                ]);
            }

            $peminjaman->status = Peminjaman::STATUS_DITOLAK;
            $peminjaman->catatan_petugas = $data['alasan'];
            $peminjaman->save();
        });

        return back()->with('success', "Pengajuan #{$id} telah ditolak. Alokasi stok telah dilepaskan kembali.");
    }

    /**
     * Memproses penyerahan/pengambilan alat (Status: Disetujui -> Dipinjam).
     */
    public function serahkan(Request $request, int $id)
    {
        DB::transaction(function () use ($id) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== Peminjaman::STATUS_DISETUJUI) {
                throw ValidationException::withMessages([
                    'error' => 'Alat hanya dapat diserahkan jika pengajuan telah berstatus "disetujui".',
                ]);
            }

            $peminjaman->status = Peminjaman::STATUS_DIPINJAM;
            $peminjaman->tanggal_pengambilan = Carbon::now();
            $peminjaman->save();
        });

        return back()->with('success', "Alat untuk pengajuan #{$id} berhasil diserahkan kepada peminjam. Status saat ini: Dipinjam.");
    }

    /**
     * Memproses pengembalian alat (Status: Dipinjam -> Dikembalikan).
     */
    public function kembalikan(Request $request, int $id)
    {
        $request->validate([
            'kondisi' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($request, $id) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== Peminjaman::STATUS_DIPINJAM) {
                throw ValidationException::withMessages([
                    'error' => 'Hanya transaksi yang berstatus "dipinjam" yang dapat dikembalikan.',
                ]);
            }

            $peminjaman->status = Peminjaman::STATUS_DIKEMBALIKAN;
            $peminjaman->tanggal_pengembalian = Carbon::now();

            if ($request->filled('kondisi')) {
                $kondisi = "Catatan Pengembalian: " . $request->input('kondisi');
                $peminjaman->catatan_petugas = $peminjaman->catatan_petugas
                    ? $peminjaman->catatan_petugas . " | " . $kondisi
                    : $kondisi;
            }

            $peminjaman->save();
        });

        return back()->with('success', "Alat untuk pengajuan #{$id} berhasil dikembalikan ke laboratorium. Alokasi stok telah tersedia kembali.");
    }

    /**
     * Menampilkan riwayat peminjaman khusus milik pengguna yang sedang login (Peminjam).
     */
    public function riwayatSaya(Request $request)
    {
        $user = Auth::user();
        $status = (string) $request->query('status', '');

        $query = Peminjaman::where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->nim) {
                $q->orWhere('nim', $user->nim);
            }
        })->with('alat')->latest();

        if ($status !== '' && in_array($status, Peminjaman::STATUS, true)) {
            $query->where('status', $status);
        }

        $daftarRiwayat = $query->paginate(10)->withQueryString();

        return view('peminjam.riwayat', compact('daftarRiwayat', 'status'));
    }
}
