<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman kelola profil pengguna.
     */
    public function edit()
    {
        $user = Auth::user();

        return view('pages.profil', compact('user'));
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:new_password', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        // NIM hanya boleh diisi jika akun belum punya NIM.
        // Jika sudah terisi, kiriman NIM dari request diabaikan.
        $nimBolehDiisi = empty($user->nim);

        if ($nimBolehDiisi) {
            $rules['nim'] = [
                'nullable',
                'string',
                'regex:/^[0-9]{8,20}$/',
                Rule::unique('users', 'nim')->ignore($user->id),
            ];
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'nim.regex' => 'NIM harus berupa 8 sampai 20 digit angka.',
            'nim.unique' => 'NIM ini sudah terdaftar pada akun lain.',
            'current_password.required_with' => 'Password saat ini diperlukan untuk mengubah password.',
            'new_password.min' => 'Password baru minimal harus 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Password hanya diubah jika password baru diisi.
        if (!empty($validated['new_password'])) {
            if (!Hash::check((string) ($validated['current_password'] ?? ''), $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Password saat ini yang Anda masukkan salah.',
                ]);
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;

        if ($nimBolehDiisi && !empty($validated['nim'])) {
            $user->nim = $validated['nim'];
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }
}