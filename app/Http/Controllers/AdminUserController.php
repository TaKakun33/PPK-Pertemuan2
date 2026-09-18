<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    // FR-08: Admin dapat melihat seluruh akun user beserta perannya
    public function index()
    {
        $users = User::withCount(['tasks', 'collaboratedTasks'])->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    // FR-09: Admin dapat menambah akun User baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user,collaborator',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun user berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // FR-09: Admin dapat mengatur (mengubah data/peran) akun User
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|in:admin,user,collaborator',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    // FR-09: Admin dapat menghapus akun User
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir di sistem.');
        }

        $user->delete();

        return back()->with('success', 'Akun user berhasil dihapus.');
    }
}
