<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan daftar akun pengguna dengan pencarian dan filter role.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');

        $query = User::query();

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (! empty($role) && in_array($role, ['admin', 'manager', 'staff'])) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('pages.users.index', compact('users', 'search', 'role'));
    }

    /**
     * Formulir tambah akun pengguna baru.
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Simpan data pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'manager', 'staff'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi wajib diisi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'role.required' => 'Pilih peran otorisasi pengguna.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_USER',
            'description' => "Menambahkan akun pengguna baru: {$user->name} ({$user->email}) dengan peran {$user->role}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', "Akun pengguna {$user->name} berhasil didaftarkan.");
    }

    /**
     * Formulir edit data pengguna.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('pages.users.edit', compact('user'));
    }

    /**
     * Perbarui data pengguna.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'manager', 'staff'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'role.required' => 'Pilih peran otorisasi pengguna.',
        ]);

        // Pencegahan keamanan: Admin yang sedang login tidak boleh menonaktifkan atau menurunkan role dirinya sendiri
        if ($user->id === Auth::id()) {
            if ($validated['role'] !== 'admin') {
                return redirect()->back()->with('error', 'Anda tidak dapat menurunkan hak akses peran akun Anda sendiri.');
            }
            if ($request->has('is_active') && ! $request->is_active) {
                return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->phone = $validated['phone'] ?? null;
        $user->is_active = $request->has('is_active') ? (bool) $request->is_active : $user->is_active;

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_USER',
            'description' => "Memperbarui data akun pengguna: {$user->name} ({$user->email})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', "Data akun {$user->name} berhasil diperbarui.");
    }

    /**
     * Hapus akun pengguna.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Pencegahan keamanan: Tidak boleh menghapus akun sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $email = $user->email;
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_USER',
            'description' => "Menghapus akun pengguna: {$name} ({$email})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('users.index')->with('success', "Akun pengguna {$name} telah berhasil dihapus.");
    }
}
