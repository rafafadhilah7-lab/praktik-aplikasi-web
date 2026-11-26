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

        if ($status !== '' && in_array($status, Peminjaman::STATUS, true)) {
            $query->where('status', $status);
        }

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

        Gate::authorize('view', $peminjaman);

        return view('peminjaman.detail', compact('peminjaman'));
    }

    /**
     * Form ubah pengajuan peminjaman (hanya pemilik saat status masih menunggu).
     */
    public function edit(int $id)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($id);

        Gate::authorize('update', $peminjaman);

        if (! $peminjaman->isMenunggu()) {
            return redirect()
                ->route('peminjaman.detail', $peminjaman->id)
                ->with('error', 'Hanya pengajuan berstatus menunggu yang dapat diubah.');
        }

        $alat = $peminjaman->alat;
        $user = Auth::user();

        return view('peminjam.edit', compact('peminjaman', 'alat', 'user'));
    }

    /**
     * Menyimpan perubahan pengajuan peminjaman (hanya pemilik saat status masih menunggu).
     */
    public function update(Request $request, int $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        Gate::authorize('update', $peminjaman);

        $data = $request->validate([
            'tanggal_pinjam' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'durasi' => ['required', 'integer', 'min:1', 'max:30'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keperluan' => ['required', 'string', 'max:500'],
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

        $actor = Auth::user();

        DB::transaction(function () use ($data, $id, $actor) {
            $locked = Peminjaman::lockForUpdate()->findOrFail($id);

            if (! $locked->isMenunggu()) {
                throw ValidationException::withMessages([
                    'error' => 'Pengajuan sudah diproses dan tidak dapat diubah lagi.',
                ]);
            }

            $alat = Alat::lockForUpdate()->findOrFail($locked->alat_id);

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
                $tanggalSelesai,
                $locked->id
            );

            if ($terpakai + (int) $data['jumlah'] > (int) $alat->stok) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Stok tidak mencukupi pada tanggal tersebut. Sudah teralokasi ' . $terpakai . ' unit dari total ' . $alat->stok . ' unit.',
                ]);
            }

            $locked->tanggal_pinjam = $tanggalMulai;
            $locked->tanggal_selesai = $tanggalSelesai;
            $locked->durasi = (int) $data['durasi'];
            $locked->jumlah = (int) $data['jumlah'];
            $locked->keperluan = $data['keperluan'];
            $locked->save();

            PeminjamanLogger::catat(
                $actor,
                $locked,
                'pengajuan_diubah',
                "Pengajuan #{$locked->id} diperbarui: {$locked->jumlah} unit, {$locked->tanggal_pinjam->format('d/m/Y')} ({$locked->durasi} hari)."
            );
        });

        return redirect()
            ->route('peminjaman.detail', $id)
            ->with('success', "Pengajuan #{$id} berhasil diperbarui.");
    }

    /**
     * Membatalkan pengajuan peminjaman (berbasis status, tanpa hard delete).
     */
    public function batalkan(int $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        Gate::authorize('delete', $peminjaman);

        $actor = Auth::user();

        DB::transaction(function () use ($id, $actor) {
            $locked = Peminjaman::with(['alat', 'user'])->lockForUpdate()->findOrFail($id);

            if (! $locked->isMenunggu()) {
                throw ValidationException::withMessages([
                    'error' => 'Hanya pengajuan berstatus menunggu yang dapat dibatalkan.',
                ]);
            }

            $locked->status = Peminjaman::STATUS_DIBATALKAN;
            $locked->save();

            $namaAlat = $locked->alat?->nama ?? 'Alat';

            PeminjamanLogger::catat(
                $actor,
                $locked,
                'pengajuan_dibatalkan',
                "Pengajuan #{$locked->id} untuk {$namaAlat} dibatalkan oleh peminjam."
            );

            PeminjamanLogger::kirimKeUser(
                $locked->user,
                $locked->id,
                'Pengajuan Dibatalkan',
                "Pengajuan #{$locked->id} untuk {$namaAlat} telah Anda batalkan.",
                Peminjaman::STATUS_DIBATALKAN,
                route('peminjaman.detail', $locked->id)
            );

            PeminjamanLogger::kirimKeRole(
                User::ROLE_LABORAN,
                $locked->id,
                'Pengajuan Dibatalkan Peminjam',
                "Pengajuan #{$locked->id} ({$locked->nama_peminjam} - {$namaAlat}) telah dibatalkan.",
                Peminjaman::STATUS_DIBATALKAN,
                route('peminjaman.detail', $locked->id)
            );
        });

        return redirect()
            ->route('peminjam.riwayat')
            ->with('success', "Pengajuan #{$id} berhasil dibatalkan.");
    }

    /**
     * Laboran/Admin menyetujui pengajuan peminjaman.
     */
    public function setujui(Request $request, int $id)
    {
        Gate::authorize('review', Peminjaman::findOrFail($id));

        $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $actor = Auth::user();

        DB::transaction(function () use ($request, $id, $actor) {
            $peminjaman = Peminjaman::with(['alat', 'user'])->lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU) {
                throw ValidationException::withMessages([
                    'error' => 'Hanya pengajuan dengan status "menunggu" yang dapat disetujui.',
                ]);
            }

            $alat = Alat::lockForUpdate()->findOrFail($peminjaman->alat_id);

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

            PeminjamanLogger::catat(
                $actor,
                $peminjaman,
                'pengajuan_disetujui',
                "Pengajuan #{$peminjaman->id} ({$alat->nama}) disetujui."
            );

            PeminjamanLogger::kirimKeUser(
                $peminjaman->user,
                $peminjaman->id,
                'Pengajuan Disetujui',
                "Pengajuan #{$peminjaman->id} untuk {$alat->nama} telah disetujui dan siap diambil di laboratorium.",
                Peminjaman::STATUS_DISETUJUI,
                route('peminjaman.detail', $peminjaman->id)
            );
        });

        return back()->with('success', "Pengajuan #{$id} berhasil disetujui. Alat siap diserahkan kepada peminjam.");
    }

    /**
     * Laboran/Admin menolak pengajuan peminjaman.
     */
    public function tolak(Request $request, int $id)
    {
        Gate::authorize('review', Peminjaman::findOrFail($id));

        $data = $request->validate([
            'alasan' => ['required', 'string', 'max:500'],
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi agar peminjam memahami penyebabnya.',
        ]);

        $actor = Auth::user();

        DB::transaction(function () use ($data, $id, $actor) {
            $peminjaman = Peminjaman::with(['alat', 'user'])->lockForUpdate()->findOrFail($id);

            if (! in_array($peminjaman->status, [Peminjaman::STATUS_MENUNGGU, Peminjaman::STATUS_DISETUJUI], true)) {
                throw ValidationException::withMessages([
                    'error' => 'Hanya pengajuan yang sedang menunggu atau baru disetujui yang dapat ditolak.',
                ]);
            }

            $peminjaman->status = Peminjaman::STATUS_DITOLAK;
            $peminjaman->catatan_petugas = $data['alasan'];
            $peminjaman->save();

            $namaAlat = $peminjaman->alat?->nama ?? 'Alat';

            PeminjamanLogger::catat(
                $actor,
                $peminjaman,
                'pengajuan_ditolak',
                "Pengajuan #{$peminjaman->id} ({$namaAlat}) ditolak."
            );

            PeminjamanLogger::kirimKeUser(
                $peminjaman->user,
                $peminjaman->id,
                'Pengajuan Ditolak',
                "Pengajuan #{$peminjaman->id} untuk {$namaAlat} ditolak. Alasan: {$data['alasan']}",
                Peminjaman::STATUS_DITOLAK,
                route('peminjaman.detail', $peminjaman->id)
            );
        });

        return back()->with('success', "Pengajuan #{$id} telah ditolak. Alokasi stok telah dilepaskan kembali.");
    }

    /**
     * Memproses penyerahan/pengambilan alat (Status: Disetujui -> Dipinjam).
     */
    public function serahkan(Request $request, int $id)
    {
        Gate::authorize('review', Peminjaman::findOrFail($id));

        $actor = Auth::user();

        DB::transaction(function () use ($id, $actor) {
            $peminjaman = Peminjaman::with(['alat', 'user'])->lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== Peminjaman::STATUS_DISETUJUI) {
                throw ValidationException::withMessages([
                    'error' => 'Alat hanya dapat diserahkan jika pengajuan telah berstatus "disetujui".',
                ]);
            }

            $peminjaman->status = Peminjaman::STATUS_DIPINJAM;
            $peminjaman->tanggal_pengambilan = Carbon::now();
            $peminjaman->save();

            $namaAlat = $peminjaman->alat?->nama ?? 'Alat';

            PeminjamanLogger::catat(
                $actor,
                $peminjaman,
                'alat_diserahkan',
                "Alat {$namaAlat} untuk pengajuan #{$peminjaman->id} diserahkan kepada peminjam."
            );

            PeminjamanLogger::kirimKeUser(
                $peminjaman->user,
                $peminjaman->id,
                'Alat Telah Diserahkan',
                "Alat {$namaAlat} pada transaksi #{$peminjaman->id} telah diserahkan. Harap kembalikan tepat waktu.",
                Peminjaman::STATUS_DIPINJAM,
                route('peminjaman.detail', $peminjaman->id)
            );
        });

        return back()->with('success', "Alat untuk pengajuan #{$id} berhasil diserahkan kepada peminjam. Status saat ini: Dipinjam.");
    }

    /**
     * Memproses pengembalian alat (Status: Dipinjam -> Dikembalikan).
     */
    public function kembalikan(Request $request, int $id)
    {
        Gate::authorize('review', Peminjaman::findOrFail($id));

        $request->validate([
            'kondisi' => ['nullable', 'string', 'max:500'],
        ]);

        $actor = Auth::user();

        DB::transaction(function () use ($request, $id, $actor) {
            $peminjaman = Peminjaman::with(['alat', 'user'])->lockForUpdate()->findOrFail($id);

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

            $namaAlat = $peminjaman->alat?->nama ?? 'Alat';

            PeminjamanLogger::catat(
                $actor,
                $peminjaman,
                'alat_dikembalikan',
                "Alat {$namaAlat} untuk pengajuan #{$peminjaman->id} telah dikembalikan."
            );

            PeminjamanLogger::kirimKeUser(
                $peminjaman->user,
                $peminjaman->id,
                'Pengembalian Diproses',
                "Pengembalian alat {$namaAlat} untuk transaksi #{$peminjaman->id} telah diterima laboratorium.",
                Peminjaman::STATUS_DIKEMBALIKAN,
                route('peminjaman.detail', $peminjaman->id)
            );
        });

        return back()->with('success', "Alat untuk pengajuan #{$id} berhasil dikembalikan ke laboratorium. Alokasi stok telah tersedia kembali.");
    }

    /**
     * Menampilkan riwayat peminjaman milik pengguna yang sedang login (Peminjam).
     */
    public function riwayatSaya(Request $request)
    {
        $user = Auth::user();
        $status = (string) $request->query('status', '');

        $query = Peminjaman::where('user_id', $user->id)
            ->with('alat')
            ->latest();

        if ($status !== '' && in_array($status, Peminjaman::STATUS, true)) {
            $query->where('status', $status);
        }

        $daftarRiwayat = $query->paginate(10)->withQueryString();

        return view('peminjam.riwayat', compact('daftarRiwayat', 'status'));
    }
}
