<?php

namespace App\Models;

use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    /**
     * Nama tabel database.
     */
    protected $table = 'peminjaman';

    /**
     * Daftar status pengajuan.
     */
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DIPINJAM = 'dipinjam';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';
    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS = [
        self::STATUS_MENUNGGU,
        self::STATUS_DISETUJUI,
        self::STATUS_DIPINJAM,
        self::STATUS_DIKEMBALIKAN,
        self::STATUS_DITOLAK,
    ];

    /**
     * Status yang masih menggunakan stok alat.
     */
    public const AKTIF = [
        self::STATUS_MENUNGGU,
        self::STATUS_DISETUJUI,
        self::STATUS_DIPINJAM,
    ];

    /**
     * Kolom yang boleh diisi melalui mass assignment.
     */
    protected $fillable = [
        'user_id',
        'alat_id',
        'nama_peminjam',
        'nim',
        'tanggal_pinjam',
        'tanggal_selesai',
        'durasi',
        'jumlah',
        'keperluan',
        'status',
        'catatan_petugas',
        'tanggal_persetujuan',
        'tanggal_pengambilan',
        'tanggal_pengembalian',
    ];

    /**
     * Konversi tipe data atribut.
     */
    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_selesai' => 'date',
            'durasi' => 'integer',
            'jumlah' => 'integer',
            'tanggal_persetujuan' => 'datetime',
            'tanggal_pengambilan' => 'datetime',
            'tanggal_pengembalian' => 'datetime',
        ];
    }

    /**
     * Relasi ke akun user jika ada.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Satu peminjaman terkait dengan satu alat.
     */
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id')->withTrashed();
    }

    public function isMenunggu(): bool
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    public function isDisetujui(): bool
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isDipinjam(): bool
    {
        return $this->status === self::STATUS_DIPINJAM;
    }

    public function isDikembalikan(): bool
    {
        return $this->status === self::STATUS_DIKEMBALIKAN;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    /**
     * Peminjaman terlambat: alat masih dipinjam tetapi
     * tanggal selesai sudah lewat dari hari ini.
     */
    public function isTerlambat(): bool
    {
        return $this->status === self::STATUS_DIPINJAM
            && $this->tanggal_selesai !== null
            && $this->tanggal_selesai->format('Y-m-d') < now()->toDateString();
    }

    /**
     * Menghitung jumlah unit maksimum yang terpakai
     * pada satu hari dalam rentang tanggal.
     *
     * Pengajuan berstatus tidak aktif tidak dihitung.
     * Peminjaman berstatus "dipinjam" yang sudah lewat tanggal selesai
     * tetap dihitung memakai unit pada seluruh rentang, karena alat
     * belum dikembalikan ke laboratorium.
     */
    public static function puncakTerpakai(
        int $alatId,
        string $mulai,
        string $selesai,
        ?int $abaikanId = null
    ): int {
        $hariIni = now()->toDateString();

        $query = static::query()
            ->where('alat_id', $alatId)
            ->whereIn('status', self::AKTIF)
            ->whereDate('tanggal_pinjam', '<=', $selesai)
            ->where(function ($q) use ($mulai, $hariIni) {
                $q->whereDate('tanggal_selesai', '>=', $mulai)
                    ->orWhere(function ($terlambat) use ($hariIni) {
                        $terlambat
                            ->where('status', self::STATUS_DIPINJAM)
                            ->whereDate('tanggal_selesai', '<', $hariIni);
                    });
            });

        if ($abaikanId !== null) {
            $query->where('id', '!=', $abaikanId);
        }

        $daftarPeminjaman = $query->get([
            'tanggal_pinjam',
            'tanggal_selesai',
            'jumlah',
            'status',
        ]);

        $awal = new DateTimeImmutable($mulai);
        $akhir = new DateTimeImmutable($selesai);
        $puncak = 0;

        for (
            $tanggal = $awal;
            $tanggal <= $akhir;
            $tanggal = $tanggal->modify('+1 day')
        ) {
            $hari = $tanggal->format('Y-m-d');
            $terpakai = 0;

            foreach ($daftarPeminjaman as $item) {
                $tanggalPinjam = $item->tanggal_pinjam->format('Y-m-d');
                $tanggalSelesai = $item->tanggal_selesai->format('Y-m-d');

                $terlambat = $item->status === self::STATUS_DIPINJAM
                    && $tanggalSelesai < $hariIni;

                if (
                    $tanggalPinjam <= $hari &&
                    ($terlambat || $tanggalSelesai >= $hari)
                ) {
                    $terpakai += (int) $item->jumlah;
                }
            }

            $puncak = max($puncak, $terpakai);
        }

        return $puncak;
    }

    /**
     * Menghitung tanggal selesai otomatis sebelum data disimpan.
     * Hari peminjaman dihitung sebagai hari pertama.
     */
    protected static function booted(): void
    {
        static::saving(function (Peminjaman $peminjaman) {
            if (
                $peminjaman->tanggal_pinjam !== null &&
                $peminjaman->durasi !== null &&
                (int) $peminjaman->durasi > 0
            ) {
                $tanggalPinjam = $peminjaman->tanggal_pinjam;

                if ($tanggalPinjam instanceof DateTimeInterface) {
                    $tanggalPinjam = DateTimeImmutable::createFromInterface(
                        $tanggalPinjam
                    );
                } else {
                    $tanggalPinjam = new DateTimeImmutable(
                        (string) $tanggalPinjam
                    );
                }

                $peminjaman->tanggal_selesai = $tanggalPinjam
                    ->modify('+' . ((int) $peminjaman->durasi - 1) . ' days')
                    ->format('Y-m-d');
            }
        });
    }
}