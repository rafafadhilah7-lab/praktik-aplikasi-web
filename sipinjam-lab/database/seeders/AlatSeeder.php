<?php

namespace Database\Seeders;

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    /**
     * Mengisi data awal alat laboratorium.
     */
    public function run(): void
    {
        $daftarAlat = [
            [
                'kode' => 'ALT-001',
                'nama' => 'Multimeter Digital',
                'kategori' => 'Elektronika',
                'stok' => 5,
                'lokasi' => 'Lab Elektronika Dasar',
                'deskripsi' => 'Alat untuk mengukur tegangan, arus, dan hambatan listrik.',
            ],
            [
                'kode' => 'ALT-014',
                'nama' => 'Osiloskop 100 MHz',
                'kategori' => 'Elektronika',
                'stok' => 2,
                'lokasi' => 'Lab Elektronika Dasar',
                'deskripsi' => 'Alat untuk menampilkan dan menganalisis bentuk gelombang listrik.',
            ],
            [
                'kode' => 'ALT-022',
                'nama' => 'Power Supply DC',
                'kategori' => 'Elektronika',
                'stok' => 0,
                'lokasi' => 'Lab Elektronika Dasar',
                'deskripsi' => 'Catu daya DC untuk kebutuhan praktikum elektronika.',
            ],
            [
                'kode' => 'ALT-031',
                'nama' => 'Mikroskop Binokuler',
                'kategori' => 'Biologi',
                'stok' => 4,
                'lokasi' => 'Lab Biologi',
                'deskripsi' => 'Mikroskop untuk mengamati objek berukuran kecil.',
            ],
            [
                'kode' => 'ALT-040',
                'nama' => 'Timbangan Analitik',
                'kategori' => 'Kimia',
                'stok' => 1,
                'lokasi' => 'Lab Kimia',
                'deskripsi' => 'Timbangan presisi untuk mengukur massa bahan praktikum.',
            ],
            [
                'kode' => 'ALT-052',
                'nama' => 'Kit Arduino Uno',
                'kategori' => 'Elektronika',
                'stok' => 8,
                'lokasi' => 'Lab Elektronika Dasar',
                'deskripsi' => 'Kit mikrokontroler untuk eksperimen sistem tertanam.',
            ],
        ];

        foreach ($daftarAlat as $data) {
            Alat::updateOrCreate(
                ['kode' => $data['kode']],
                $data
            );
        }
    }
}
