<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserManageController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna untuk Admin.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', '');

        $query = User::query()->latest();

        if ($q !== '') {
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nim', 'like', "%{$q}%");
            });
        }

        if ($role !== '' && array_key_exists($role, User::ROLES)) {
            $query->where('role', $role);
        }

        $daftarUser = $query->paginate(10)->withQueryString();

        $countAdmin = User::where('role', User::ROLE_ADMIN)->count();
        $countLaboran = User::where('role', User::ROLE_LABORAN)->count();
        $countPeminjam = User::where('role', User::ROLE_PEMINJAM)->count();

        return view('admin.users.index', compact(
            'daftarUser',
            'q',
            'role',
            'countAdmin',
            'countLaboran',
            'countPeminjam'
        ));
    }

    /**
     * Menampilkan formulir pembuatan pengguna baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan data pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in([User::ROLE_ADMIN, User::ROLE_LABORAN, User::ROLE_PEMINJAM])],
            'nim' => ['nullable', 'string', 'regex:/^[0-9]{8,20}$/', 'unique:users,nim'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan.',
            'role.required' => 'Role pengguna wajib ditentukan.',
            'nim.regex' => 'NIM harus berupa 8 sampai 20 digit angka.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'nim' => $validated['role'] === User::ROLE_PEMINJAM ? $validated['nim'] : null,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna \"{$user->name}\" berhasil ditambahkan sebagai {$user->role_label}.");
    }

    /**
     * Menampilkan formulir edit pengguna.
     */
    public function edit(int $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data dan role pengguna.
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in([User::ROLE_ADMIN, User::ROLE_LABORAN, User::ROLE_PEMINJAM])],
            'nim' => ['nullable', 'string', 'regex:/^[0-9]{8,20}$/', Rule::unique('users', 'nim')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan.',
            'role.required' => 'Role pengguna wajib ditentukan.',
            'nim.regex' => 'NIM harus berupa 8 sampai 20 digit angka.',
            'nim.unique' => 'NIM ini sudah terdaftar.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
        ]);

        // Cegah admin mengubah rolenya sendiri menjadi bukan admin jika ia adalah satu-satunya admin
        if ($user->id === Auth::id() && $validated['role'] !== User::ROLE_ADMIN) {
            throw ValidationException::withMessages([
                'role' => 'Anda tidak dapat mencabut hak akses Admin dari akun Anda sendiri.',
            ]);
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'nim' => $validated['role'] === User::ROLE_PEMINJAM ? $validated['nim'] : null,
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $nama = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna \"{$nama}\" berhasil dihapus dari sistem.");
    }
}
