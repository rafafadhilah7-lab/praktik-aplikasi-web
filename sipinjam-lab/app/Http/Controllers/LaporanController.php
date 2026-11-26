<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Rekap & cetak laporan transaksi peminjaman (Admin & Laboran).
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tanggal_awal' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_akhir' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggal_awal'],
            'status' => ['nullable', 'string'],
            'alat_id' => ['nullable', 'integer'],
        ], [
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal awal.',
        ]);

        $tanggalAwal = (string) ($validated['tanggal_awal'] ?? '');
        $tanggalAkhir = (string) ($validated['tanggal_akhir'] ?? '');
        $status = (string) ($validated['status'] ?? '');
        $alatId = isset($validated['alat_id']) ? (int) $validated['alat_id'] : null;

        $baseQuery = Peminjaman::query();

        if ($tanggalAwal !== '') {
            $baseQuery->whereDate('tanggal_pinjam', '>=', $tanggalAwal);
        }
        if ($tanggalAkhir !== '') {
            $baseQuery->whereDate('tanggal_pinjam', '<=', $tanggalAkhir);
        }
        if ($status !== '' && in_array($status, Peminjaman::STATUS, true)) {
            $baseQuery->where('status', $status);
        }
        if ($alatId !== null && $alatId > 0) {
            $baseQuery->where('alat_id', $alatId);
        }

        $ringkasanStatus = (clone $baseQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $ringkasan = [
            'total' => array_sum($ringkasanStatus),
            'menunggu' => (int) ($ringkasanStatus[Peminjaman::STATUS_MENUNGGU] ?? 0),
            'disetujui' => (int) ($ringkasanStatus[Peminjaman::STATUS_DISETUJUI] ?? 0),
            'dipinjam' => (int) ($ringkasanStatus[Peminjaman::STATUS_DIPINJAM] ?? 0),
            'dikembalikan' => (int) ($ringkasanStatus[Peminjaman::STATUS_DIKEMBALIKAN] ?? 0),
            'ditolak' => (int) ($ringkasanStatus[Peminjaman::STATUS_DITOLAK] ?? 0),
            'dibatalkan' => (int) ($ringkasanStatus[Peminjaman::STATUS_DIBATALKAN] ?? 0),
        ];

        $alatTerpopuler = (clone $baseQuery)
            ->whereNotIn('status', [Peminjaman::STATUS_DITOLAK, Peminjaman::STATUS_DIBATALKAN])
            ->select('alat_id', DB::raw('COUNT(*) as total_transaksi'), DB::raw('SUM(jumlah) as total_unit'))
            ->groupBy('alat_id')
            ->orderByDesc('total_transaksi')
            ->with('alat')
            ->limit(5)
            ->get();

        $daftarTransaksi = (clone $baseQuery)
            ->with('alat')
            ->latest()
            ->limit(200)
            ->get();

        $daftarAlat = Alat::query()->orderBy('nama')->get(['id', 'kode', 'nama']);

        return view('laporan.index', [
            'ringkasan' => $ringkasan,
            'alatTerpopuler' => $alatTerpopuler,
            'daftarTransaksi' => $daftarTransaksi,
            'daftarAlat' => $daftarAlat,
            'statusList' => Peminjaman::STATUS,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'status' => $status,
            'alatId' => $alatId,
        ]);
    }
}
