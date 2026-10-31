<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@sipinjam.lab'],
            [
                'name' => 'Administrator Laboratorium',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_ADMIN,
                'nim' => null,
                'phone' => '081234567890',
            ]
        );

        // 2. Laboran
        $laboran = User::updateOrCreate(
            ['email' => 'laboran@sipinjam.lab'],
            [
                'name' => 'Petugas Laboran',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_LABORAN,
                'nim' => null,
                'phone' => '081234567891',
            ]
        );

        // 3. Peminjam (Rafa Fadhilah - mencocokkan pengajuan eksisting #1)
        $peminjam1 = User::updateOrCreate(
            ['email' => 'peminjam@sipinjam.lab'],
            [
                'name' => 'Rafa Fadhilah',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_PEMINJAM,
                'nim' => '24051130041',
                'phone' => '081234567892',
            ]
        );

        // 4. Peminjam 2 (Apip - mencocokkan pengajuan eksisting #2)
        $peminjam2 = User::updateOrCreate(
            ['email' => 'apip@sipinjam.lab'],
            [
                'name' => 'Apip',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_PEMINJAM,
                'nim' => '24051130042',
                'phone' => '081234567893',
            ]
        );

        // Hubungkan peminjaman eksisting dengan akun peminjam
        Peminjaman::where('nim', '24051130041')->whereNull('user_id')->update(['user_id' => $peminjam1->id]);
        Peminjaman::where('nim', '24051130042')->whereNull('user_id')->update(['user_id' => $peminjam2->id]);
    }
}
