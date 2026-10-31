<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Batas percobaan login dan durasi pemblokiran (15 menit = 900 detik).
     */
    public const MAX_LOGIN_ATTEMPTS = 3;
    public const LOCKOUT_SECONDS = 900;

    /**
     * Menampilkan formulir login.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Menghasilkan key unik rate limiter berdasarkan email dan IP pemohon.
     */
    protected function throttleKey(Request $request): string
    {
        return 'login_attempts:' . Str::transliterate(Str::lower($request->input('email', '')) . '|' . $request->ip());
    }

    /**
     * Memproses autentikasi login dengan pembatasan maksimal 3 kali gagal.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = $this->throttleKey($request);

        // Periksa apakah akun/identitas ini sedang dalam masa pemblokiran sementara
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login yang gagal. Akun atau identitas login diblokir sementara selama 15 menit demi keamanan. Silakan coba lagi dalam {$minutes} menit ({$seconds} detik).",
            ]);
        }

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            // Tambahkan hit pada rate limiter dengan masa berlaku 15 menit (900 detik)
            RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);

            $retriesLeft = RateLimiter::retriesLeft($throttleKey, self::MAX_LOGIN_ATTEMPTS);

            if ($retriesLeft <= 0) {
                $seconds = RateLimiter::availableIn($throttleKey);
                $minutes = ceil($seconds / 60);

                throw ValidationException::withMessages([
                    'email' => "Password salah. Anda telah gagal sebanyak 3 kali. Akun Anda diblokir sementara selama 15 menit. Silakan tunggu {$minutes} menit ({$seconds} detik) sebelum mencoba login kembali.",
                ]);
            }

            throw ValidationException::withMessages([
                'email' => "Email atau password yang Anda masukkan salah. Sisa kesempatan mencoba: {$retriesLeft} kali sebelum diblokir selama 15 menit.",
            ]);
        }

        // Login berhasil: hapus riwayat percobaan gagal dan regenerasi session
        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        $user = Auth::user();

        return $this->redirectBasedOnRole($user)
            ->with('success', "Selamat datang kembali, {$user->name}!");
    }

    /**
     * Menampilkan halaman registrasi peminjam.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Memproses pendaftaran peminjam baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'nim' => ['required', 'string', 'regex:/^[0-9]{8,20}$/', 'unique:users,nim'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.regex' => 'NIM harus berupa 8 sampai 20 digit angka.',
            'nim.unique' => 'NIM ini sudah terdaftar dalam sistem.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
        ]);

        // Otomatis menetapkan role peminjam (tidak dapat memilih role admin/laboran saat registrasi)
        $user = User::create([
            'name' => $validated['name'],
            'nim' => $validated['nim'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_PEMINJAM,
            'phone' => $validated['phone'] ?? null,
        ]);

        // Tautkan data pengajuan lama jika ada yang memiliki NIM sama
        Peminjaman::where('nim', $user->nim)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        // Login otomatis setelah registrasi berhasil
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('peminjam.dashboard')
            ->with('success', "Pendaftaran berhasil! Selamat datang di SIPINJAM-LAB, {$user->name}.");
    }

    /**
     * Logout pengguna dari sistem.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar dari sistem SIPINJAM-LAB.');
    }

    /**
     * Mengarahkan pengguna ke dashboard sesuai perannya.
     */
    public function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
            User::ROLE_LABORAN => redirect()->route('laboran.dashboard'),
            default => redirect()->route('peminjam.dashboard'),
        };
    }
}
