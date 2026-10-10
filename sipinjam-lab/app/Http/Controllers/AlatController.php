<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlatController extends Controller
{
    // Data contoh alat laboratorium
    private function semuaAlat(): array
    {
        return [
            [
                'id' => 1,
                'kode' => 'ALT-001',
                'nama' => 'Multimeter Digital',
                'kategori' => 'Elektronika',
                'stok' => 5,
                'lokasi' => 'Lab Elektronika Dasar',
            ],
            [
                'id' => 14,
                'kode' => 'ALT-014',
                'nama' => 'Osiloskop 100 MHz',
                'kategori' => 'Elektronika',
                'stok' => 2,
                'lokasi' => 'Lab Elektronika Dasar',
            ],
            [
                'id' => 22,
                'kode' => 'ALT-022',
                'nama' => 'Power Supply DC',
                'kategori' => 'Elektronika',
                'stok' => 0,
                'lokasi' => 'Lab Elektronika Dasar',
            ],
            [
                'id' => 31,
                'kode' => 'ALT-031',
                'nama' => 'Mikroskop Binokuler',
                'kategori' => 'Biologi',
                'stok' => 4,
                'lokasi' => 'Lab Biologi',
            ],
            [
                'id' => 40,
                'kode' => 'ALT-040',
                'nama' => 'Timbangan Analitik',
                'kategori' => 'Kimia',
                'stok' => 1,
                'lokasi' => 'Lab Kimia',
            ],
            [
                'id' => 52,
                'kode' => 'ALT-052',
                'nama' => 'Kit Arduino Uno',
                'kategori' => 'Elektronika',
                'stok' => 8,
                'lokasi' => 'Lab Elektronika Dasar',
            ],
        ];
    }

    // Mengambil daftar kategori unik
    private function daftarKategori(): array
    {
        return array_values(
            array_unique(
                array_column($this->semuaAlat(), 'kategori')
            )
        );
    }

    // Halaman beranda
    public function beranda()
    {
        $tersedia = array_slice(
            array_values(
                array_filter(
                    $this->semuaAlat(),
                    fn ($alat) => $alat['stok'] > 0
                )
            ),
            0,
            3
        );

        return view('pages.beranda', [
            'daftarKategori' => $this->daftarKategori(),
            'tersedia' => $tersedia,
        ]);
    }

    // Halaman hasil pencarian
    public function hasil(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = (string) $request->query('kategori', '');
        $tanggal = (string) $request->query('tanggal', '');

        $hasil = array_values(
            array_filter(
                $this->semuaAlat(),
                function ($alat) use ($q, $kategori) {
                    $cocokNama = $q === ''
                        || mb_stripos($alat['nama'], $q) !== false;

                    $cocokKategori = $kategori === ''
                        || $alat['kategori'] === $kategori;

                    return $cocokNama && $cocokKategori;
                }
            )
        );

        return view('pages.hasil', [
            'hasil' => $hasil,
            'q' => $q,
            'kategori' => $kategori,
            'tanggal' => $tanggal,
            'daftarKategori' => $this->daftarKategori(),
        ]);
    }

    // Halaman detail alat
    public function detail(int $id)
    {
        $alat = collect($this->semuaAlat())
            ->firstWhere('id', $id);

        if (!$alat) {
            abort(404);
        }

        return view('pages.detail-alat', compact('alat'));
    }

    // Halaman form peminjaman
    public function formPinjam(int $id)
    {
        $alat = collect($this->semuaAlat())
            ->firstWhere('id', $id);

        if (!$alat) {
            abort(404);
        }

        if ($alat['stok'] < 1) {
            return redirect()
                ->route('alat.detail', $id)
                ->with(
                    'error',
                    'Alat sedang tidak tersedia untuk dipinjam.'
                );
        }

        return view('pages.form-pinjam', compact('alat'));
    }

    // Proses pengajuan peminjaman simulasi
    public function ajukan(Request $request, int $id)
    {
        $alat = collect($this->semuaAlat())
            ->firstWhere('id', $id);

        if (!$alat) {
            abort(404);
        }

        if ($alat['stok'] < 1) {
            return redirect()
                ->route('alat.detail', $id)
                ->with(
                    'error',
                    'Alat sedang tidak tersedia untuk dipinjam.'
                );
        }

        $data = $request->validate([
            'nama_peminjam' => [
                'required',
                'string',
                'max:100',
            ],
            'nim' => [
                'required',
                'string',
                'max:30',
            ],
            'tanggal_pinjam' => [
                'required',
                'date',
            ],
            'durasi' => [
                'required',
                'integer',
                'min:1',
                'max:30',
            ],
            'keperluan' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        return view('pages.konfirmasi-pinjam', [
            'alat' => $alat,
            'pengajuan' => $data,
        ]);
    }
}
