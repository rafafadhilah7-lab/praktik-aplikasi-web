<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mengarahkan ke dashboard yang tepat berdasarkan role.
     */
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_LABORAN => redirect()->route('laboran.dashboard'),
            default => redirect()->route('peminjam.dashboard'),
        };
    }

    /**
     * Dashboard untuk role Admin.
     */
    public function admin()
    {
        $totalUsers = User::count();
        $totalAlat = Alat::count();
        $totalPengajuan = Peminjaman::count();

        $pengajuanMenunggu = Peminjaman::where('status', Peminjaman::STATUS_MENUNGGU)->count();
        $pengajuanDisetujui = Peminjaman::where('status', Peminjaman::STATUS_DISETUJUI)->count();
        $pengajuanDitolak = Peminjaman::where('status', Peminjaman::STATUS_DITOLAK)->count();
        $alatDipinjam = Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)->count();
        $alatDikembalikan = Peminjaman::where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count();

        // Statistik berdasarkan kategori alat
        $kategoriStats = Alat::query()
            ->selectRaw('kategori, COUNT(*) as total, SUM(stok) as total_stok')
            ->groupBy('kategori')
            ->get();

        // Pengajuan terbaru
        $pengajuanTerbaru = Peminjaman::with(['alat', 'user'])
            ->latest()
            ->limit(6)
            ->get();

        // Alat dengan stok rendah (<= 2 unit)
        $alatStokRendah = Alat::where('stok', '<=', 2)
            ->orderBy('stok')
            ->get();

        return view('dashboards.admin', compact(
            'totalUsers',
            'totalAlat',
            'totalPengajuan',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'alatDipinjam',
            'alatDikembalikan',
            'kategoriStats',
            'pengajuanTerbaru',
            'alatStokRendah'
        ));
    }

    /**
     * Dashboard untuk role Laboran.
     */
    public function laboran()
    {
        // Pengajuan baru yang menunggu persetujuan
        $pengajuanMenunggu = Peminjaman::where('status', Peminjaman::STATUS_MENUNGGU)
            ->with(['alat', 'user'])
            ->latest()
            ->get();

        // Pengajuan yang disetujui dan siap diambil
        $siapDiambil = Peminjaman::where('status', Peminjaman::STATUS_DISETUJUI)
            ->with(['alat', 'user'])
            ->latest()
            ->get();

        // Alat yang sedang dipinjam
        $sedangDipinjam = Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)
            ->with(['alat', 'user'])
            ->latest()
            ->get();

        // Alat yang harus segera dikembalikan (jatuh tempo hari ini atau terlambat)
        $harusDikembalikan = Peminjaman::where('status', Peminjaman::STATUS_DIPINJAM)
            ->whereDate('tanggal_selesai', '<=', Carbon::now()->addDay())
            ->with(['alat', 'user'])
            ->orderBy('tanggal_selesai')
            ->get();

        // Riwayat peminjaman terbaru (disetujui/dikembalikan/ditolak)
        $riwayatTerbaru = Peminjaman::whereIn('status', [
                Peminjaman::STATUS_DIKEMBALIKAN,
                Peminjaman::STATUS_DITOLAK,
            ])
            ->with(['alat', 'user'])
            ->latest('updated_at')
            ->limit(6)
            ->get();

        $countMenunggu = $pengajuanMenunggu->count();
        $countDisetujui = $siapDiambil->count();
        $countDipinjam = $sedangDipinjam->count();
        $countHarusKembali = $harusDikembalikan->count();

        return view('dashboards.laboran', compact(
            'pengajuanMenunggu',
            'siapDiambil',
            'sedangDipinjam',
            'harusDikembalikan',
            'riwayatTerbaru',
            'countMenunggu',
            'countDisetujui',
            'countDipinjam',
            'countHarusKembali'
        ));
    }

    /**
     * Dashboard untuk role Peminjam (Mahasiswa).
     */
    public function peminjam()
    {
        $user = Auth::user();

        // Mengambil data peminjaman milik user (baik berdasarkan user_id atau pencocokan NIM)
        $queryUserPeminjaman = Peminjaman::where(function ($q) use ($user) {
            $q->where('user_id', $user->id);
            if ($user->nim) {
                $q->orWhere('nim', $user->nim);
            }
        });

        $totalPengajuanSaya = (clone $queryUserPeminjaman)->count();
        $pengajuanMenunggu = (clone $queryUserPeminjaman)->where('status', Peminjaman::STATUS_MENUNGGU)->count();
        $pengajuanDisetujui = (clone $queryUserPeminjaman)->where('status', Peminjaman::STATUS_DISETUJUI)->count();
        $alatSedangDipinjam = (clone $queryUserPeminjaman)->where('status', Peminjaman::STATUS_DIPINJAM)->count();

        // Riwayat peminjaman aktif & terkini milik peminjam
        $peminjamanAktif = (clone $queryUserPeminjaman)
            ->whereIn('status', [
                Peminjaman::STATUS_MENUNGGU,
                Peminjaman::STATUS_DISETUJUI,
                Peminjaman::STATUS_DIPINJAM,
            ])
            ->with('alat')
            ->latest()
            ->get();

        // Riwayat peminjaman selesai / ditolak
        $riwayatPeminjaman = (clone $queryUserPeminjaman)
            ->whereIn('status', [
                Peminjaman::STATUS_DIKEMBALIKAN,
                Peminjaman::STATUS_DITOLAK,
            ])
            ->with('alat')
            ->latest()
            ->limit(5)
            ->get();

        // Rekomendasi katalog alat yang tersedia
        $alatTersedia = Alat::where('stok', '>', 0)
            ->limit(4)
            ->get();

        return view('dashboards.peminjam', compact(
            'user',
            'totalPengajuanSaya',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'alatSedangDipinjam',
            'peminjamanAktif',
            'riwayatPeminjaman',
            'alatTersedia'
        ));
    }
}
