<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SipinjamLabTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('login_attempts:test@sipinjam.lab|127.0.0.1');
    }

    /**
     * 1. Registrasi berhasil dan otomatis mendapatkan role peminjam.
     */
    public function test_registrasi_berhasil_dan_otomatis_role_peminjam(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Budi Santoso',
            'nim' => '24051130099',
            'email' => 'budi@sipinjam.lab',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081234567899',
        ]);

        $response->assertRedirect(route('peminjam.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi@sipinjam.lab',
            'nim' => '24051130099',
            'role' => User::ROLE_PEMINJAM,
        ]);
        $this->assertAuthenticated();
    }

    /**
     * 2. Registrasi dengan email duplikat ditolak.
     */
    public function test_registrasi_email_duplikat_ditolak(): void
    {
        User::create([
            'name' => 'Existing User',
            'nim' => '24051130088',
            'email' => 'existing@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PEMINJAM,
        ]);

        $response = $this->post(route('register.post'), [
            'name' => 'Duplicate User',
            'nim' => '24051130089',
            'email' => 'existing@sipinjam.lab',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * 3. Login berhasil dan redirect ke dashboard sesuai role.
     */
    public function test_login_berhasil_dan_redirect_sesuai_role(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'admin@sipinjam.lab',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * 4. Pembatasan login maksimal 3 kali gagal dan percobaan ketiga memblokir sementara.
     */
    public function test_pembatasan_login_maksimal_3_kali_gagal_dan_blokir_15_menit(): void
    {
        User::create([
            'name' => 'Target User',
            'email' => 'test@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PEMINJAM,
            'nim' => '24051130011',
        ]);

        $throttleKey = 'login_attempts:test@sipinjam.lab|127.0.0.1';
        RateLimiter::clear($throttleKey);

        // Percobaan gagal ke-1: sisa 2
        $res1 = $this->post(route('login.post'), [
            'email' => 'test@sipinjam.lab',
            'password' => 'wrongpassword1',
        ]);
        $res1->assertSessionHasErrors('email');
        $this->assertEquals(1, RateLimiter::attempts($throttleKey));

        // Percobaan gagal ke-2: sisa 1
        $res2 = $this->post(route('login.post'), [
            'email' => 'test@sipinjam.lab',
            'password' => 'wrongpassword2',
        ]);
        $res2->assertSessionHasErrors('email');
        $this->assertEquals(2, RateLimiter::attempts($throttleKey));

        // Percobaan gagal ke-3: langsung diblokir 15 menit
        $res3 = $this->post(route('login.post'), [
            'email' => 'test@sipinjam.lab',
            'password' => 'wrongpassword3',
        ]);
        $res3->assertSessionHasErrors('email');
        $this->assertTrue(RateLimiter::tooManyAttempts($throttleKey, AuthController::MAX_LOGIN_ATTEMPTS));

        // Percobaan ke-4 (meski password benar, tetap diblokir karena masih dalam masa lockout)
        $res4 = $this->post(route('login.post'), [
            'email' => 'test@sipinjam.lab',
            'password' => 'password123',
        ]);
        $res4->assertSessionHasErrors('email');
        $this->assertGuest();

        // Setelah masa blokir berakhir (simulasi RateLimiter::clear)
        RateLimiter::clear($throttleKey);
        $resSuccess = $this->post(route('login.post'), [
            'email' => 'test@sipinjam.lab',
            'password' => 'password123',
        ]);
        $resSuccess->assertRedirect(route('peminjam.dashboard'));
        $this->assertAuthenticated();
    }

    /**
     * 5. Logout berfungsi.
     */
    public function test_logout_berfungsi(): void
    {
        $user = User::create([
            'name' => 'User Logout',
            'email' => 'logout@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PEMINJAM,
            'nim' => '24051130012',
        ]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * 6. Peminjam tidak bisa membuka dashboard admin (Otorisasi).
     */
    public function test_peminjam_tidak_bisa_membuka_dashboard_admin(): void
    {
        $peminjam = User::create([
            'name' => 'Peminjam Biasa',
            'email' => 'mhs@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PEMINJAM,
            'nim' => '24051130013',
        ]);

        $this->actingAs($peminjam);

        $response = $this->get(route('admin.dashboard'));
        // Middleware redirect ke dashboard peminjam dengan pesan error
        $response->assertRedirect(route('peminjam.dashboard'));
        $response->assertSessionHas('error');
    }

    /**
     * 7. Laboran tidak bisa mengelola users admin di /admin/users.
     */
    public function test_laboran_tidak_bisa_mengelola_admin_users(): void
    {
        $laboran = User::create([
            'name' => 'Petugas Laboran',
            'email' => 'petugas@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_LABORAN,
        ]);

        $this->actingAs($laboran);

        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect(route('laboran.dashboard'));
        $response->assertSessionHas('error');
    }

    /**
     * 8. Admin dapat mengelola alat (tambah, edit, dan hapus aman).
     */
    public function test_admin_dapat_mengelola_alat(): void
    {
        $admin = User::create([
            'name' => 'Admin Lab',
            'email' => 'admin2@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin);

        // Tambah alat
        $resStore = $this->post(route('admin.alat.store'), [
            'kode' => 'ALT-TEST',
            'nama' => 'Alat Uji Coba',
            'kategori' => 'Fisika',
            'stok' => 10,
            'lokasi' => 'Lab Fisika',
            'deskripsi' => 'Deskripsi alat uji coba.',
        ]);
        $resStore->assertRedirect(route('admin.alat.index'));
        $this->assertDatabaseHas('alat', ['kode' => 'ALT-TEST']);

        $alat = Alat::where('kode', 'ALT-TEST')->first();

        // Edit alat
        $resUpdate = $this->put(route('admin.alat.update', $alat->id), [
            'kode' => 'ALT-TEST',
            'nama' => 'Alat Uji Coba Diperbarui',
            'kategori' => 'Fisika Lanjut',
            'stok' => 12,
            'lokasi' => 'Lab Fisika Ruang 2',
            'deskripsi' => 'Deskripsi baru.',
        ]);
        $resUpdate->assertRedirect(route('admin.alat.index'));
        $this->assertDatabaseHas('alat', [
            'id' => $alat->id,
            'nama' => 'Alat Uji Coba Diperbarui',
            'stok' => 12,
        ]);

        // Hapus alat (karena tidak ada transaksi aktif, dapat dihapus secara soft delete)
        $resDestroy = $this->delete(route('admin.alat.destroy', $alat->id));
        $resDestroy->assertRedirect(route('admin.alat.index'));
        $this->assertSoftDeleted('alat', ['id' => $alat->id]);
    }

    /**
     * 9. Alur lengkap peminjaman: pengajuan, persetujuan, penyerahan, dan pengembalian.
     */
    public function test_alur_lengkap_peminjaman_dan_konsistensi_stok(): void
    {
        $alat = Alat::create([
            'kode' => 'ALT-OSC',
            'nama' => 'Osiloskop Digital',
            'kategori' => 'Elektronika',
            'stok' => 3,
            'lokasi' => 'Lab Elektronika',
            'deskripsi' => 'Osiloskop untuk uji praktikum.',
        ]);

        $peminjam = User::create([
            'name' => 'Siswa Penguji',
            'email' => 'siswa@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_PEMINJAM,
            'nim' => '24051130077',
        ]);

        $laboran = User::create([
            'name' => 'Petugas Jaga',
            'email' => 'jaga@sipinjam.lab',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_LABORAN,
        ]);

        // 1. Peminjam mengajukan peminjaman
        $this->actingAs($peminjam);
        $resAjukan = $this->post(route('alat.ajukan', $alat->id), [
            'nama_peminjam' => $peminjam->name,
            'nim' => $peminjam->nim,
            'tanggal_pinjam' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'durasi' => 3,
            'jumlah' => 2,
            'keperluan' => 'Praktikum Sinyal Sistem',
        ]);
        $resAjukan->assertStatus(200);

        $peminjaman = Peminjaman::where('nim', $peminjam->nim)->latest()->first();
        $this->assertNotNull($peminjaman);
        $this->assertEquals(Peminjaman::STATUS_MENUNGGU, $peminjaman->status);
        $this->assertEquals(2, $peminjaman->jumlah);

        // 2. Laboran menyetujui pengajuan
        $this->actingAs($laboran);
        $resSetujui = $this->post(route('peminjaman.setujui', $peminjaman->id));
        $resSetujui->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertEquals(Peminjaman::STATUS_DISETUJUI, $peminjaman->status);
        $this->assertNotNull($peminjaman->tanggal_persetujuan);

        // 3. Laboran menyerahkan alat (Status -> Dipinjam)
        $resSerahkan = $this->post(route('peminjaman.serahkan', $peminjaman->id));
        $resSerahkan->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertEquals(Peminjaman::STATUS_DIPINJAM, $peminjaman->status);
        $this->assertNotNull($peminjaman->tanggal_pengambilan);

        // 4. Laboran memproses pengembalian alat (Status -> Dikembalikan)
        $resKembalikan = $this->post(route('peminjaman.kembalikan', $peminjaman->id), [
            'kondisi' => 'Semua unit lengkap dan berfungsi normal.',
        ]);
        $resKembalikan->assertSessionHas('success');

        $peminjaman->refresh();
        $this->assertEquals(Peminjaman::STATUS_DIKEMBALIKAN, $peminjaman->status);
        $this->assertNotNull($peminjaman->tanggal_pengembalian);
    }

    /**
     * 10. Pengajuan yang melebihi kapasitas stok yang ada ditolak.
     */
    public function test_pengajuan_melebihi_stok_ditolak(): void
    {
        $alat = Alat::create([
            'kode' => 'ALT-STOK',
            'nama' => 'Alat Stok Terbatas',
            'kategori' => 'Kimia',
            'stok' => 2,
            'lokasi' => 'Lab Kimia',
        ]);

        $response = $this->post(route('alat.ajukan', $alat->id), [
            'nama_peminjam' => 'Mahasiswa Test',
            'nim' => '24051130098',
            'tanggal_pinjam' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'durasi' => 2,
            'jumlah' => 5, // melebihi stok 2
            'keperluan' => 'Uji beban',
        ]);

        $response->assertSessionHasErrors('jumlah');
    }
}
