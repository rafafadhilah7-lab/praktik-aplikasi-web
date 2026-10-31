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
     * Menghitung jumlah unit maksimum yang terpakai
     * pada satu hari dalam rentang tanggal.
     *
     * Pengajuan berstatus tidak aktif tidak dihitung.
     */
    public static function puncakTerpakai(
        int $alatId,
        string $mulai,
        string $selesai,
        ?int $abaikanId = null
    ): int {
        $query = static::query()
            ->where('alat_id', $alatId)
            ->whereIn('status', self::AKTIF)
            ->whereDate('tanggal_pinjam', '<=', $selesai)
            ->whereDate('tanggal_selesai', '>=', $mulai);

        if ($abaikanId !== null) {
            $query->where('id', '!=', $abaikanId);
        }

        $daftarPeminjaman = $query->get([
            'tanggal_pinjam',
            'tanggal_selesai',
            'jumlah',
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

                if (
                    $tanggalPinjam <= $hari &&
                    $tanggalSelesai >= $hari
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
