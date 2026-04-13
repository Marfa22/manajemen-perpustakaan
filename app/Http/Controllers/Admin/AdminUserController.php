<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($builder) use ($q) {
                    $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin-users.index', [
            'users' => $users,
            'q' => $q,
            'accessLabels' => User::accessLabels(),
        ]);
    }

    public function create()
    {
        return view('pages.admin-users.create', [
            'roleSuperAdmin' => User::superAdminRole(),
            'roleMenuAdmin' => User::menuAdminRole(),
            'accessLabels' => User::accessLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([User::superAdminRole(), User::menuAdminRole()])],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(array_keys(User::accessLabels()))],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash, dan underscore.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        if (
            $validated['role'] === User::menuAdminRole()
            && empty((array) ($validated['permissions'] ?? []))
        ) {
            return back()
                ->withInput()
                ->withErrors(['permissions' => 'Pilih minimal satu hak akses untuk Admin Menu.']);
        }

        $user = new User();
        $user->name = $validated['name'];
        $user->username = strtolower($validated['username']);
        $user->email = empty($validated['email']) ? null : strtolower($validated['email']);
        $user->password = $validated['password'];
        $user->role = $validated['role'];

        if ($user->isSuperAdmin()) {
            $user->permissions = null;
        } else {
            $user->syncPermissions((array) ($validated['permissions'] ?? []));
        }

        $user->save();

        return redirect('/admin/users')->with('success', 'Berhasil menambahkan admin.');
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->isSuperAdmin()) {
            return redirect('/admin/users')->with('error', 'Akun super admin bersifat permanen dan tidak dapat diubah.');
        }

        return view('pages.admin-users.edit', [
            'user' => $user,
            'roleSuperAdmin' => User::superAdminRole(),
            'roleMenuAdmin' => User::menuAdminRole(),
            'accessLabels' => User::accessLabels(),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        if ($user->isSuperAdmin()) {
            return redirect('/admin/users')->with('error', 'Akun super admin bersifat permanen dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in([User::superAdminRole(), User::menuAdminRole()])],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(array_keys(User::accessLabels()))],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash, dan underscore.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        if (
            $validated['role'] === User::menuAdminRole()
            && empty((array) ($validated['permissions'] ?? []))
        ) {
            return back()
                ->withInput()
                ->withErrors(['permissions' => 'Pilih minimal satu hak akses untuk Admin Menu.']);
        }

        if (
            $user->isSuperAdmin() &&
            $validated['role'] !== User::superAdminRole() &&
            $this->remainingSuperAdminCount($user->id) < 1
        ) {
            return back()
                ->withInput()
                ->with('error', 'Minimal harus ada satu akun super admin aktif.');
        }

        $user->name = $validated['name'];
        $user->username = strtolower($validated['username']);
        $user->email = empty($validated['email']) ? null : strtolower($validated['email']);
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        if ($user->isSuperAdmin()) {
            $user->permissions = null;
        } else {
            $user->syncPermissions((array) ($validated['permissions'] ?? []));
        }

        $user->save();

        return redirect('/admin/users')->with('success', 'Berhasil memperbarui admin.');
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        if ($user->isSuperAdmin()) {
            return redirect('/admin/users')->with('error', 'Akun super admin bersifat permanen dan tidak dapat dihapus.');
        }

        if ((int) $user->id === (int) Auth::id()) {
            return redirect('/admin/users')->with('error', 'Akun yang sedang digunakan tidak bisa dihapus.');
        }

        if ($user->isSuperAdmin() && $this->remainingSuperAdminCount($user->id) < 1) {
            return redirect('/admin/users')->with('error', 'Minimal harus ada satu akun super admin aktif.');
        }

        $user->delete();

        return redirect('/admin/users')->with('success', 'Berhasil menghapus admin.');
    }

    private function remainingSuperAdminCount(?int $exceptUserId = null): int
    {
        return User::query()
            ->where('role', User::superAdminRole())
            ->when($exceptUserId, function ($query) use ($exceptUserId) {
                $query->where('id', '!=', $exceptUserId);
            })
            ->count();
    }
}
