<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->orderBy('id')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'user' => new User,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['sometimes', 'boolean'],
        ]);

        $validated['is_admin'] = $request->boolean('is_admin');
        User::query()->create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['sometimes', 'boolean'],
        ]);

        $isAdmin = $request->boolean('is_admin');

        if ($user->is(auth()->user()) && ! $isAdmin) {
            return back()->withErrors([
                'is_admin' => 'Admin tidak dapat menghapus akses admin miliknya sendiri.',
            ]);
        }

        if ($user->isAdmin() && ! $isAdmin && User::query()->where('is_admin', true)->count() === 1) {
            return back()->withErrors([
                'is_admin' => 'Setidaknya harus ada satu akun admin.',
            ]);
        }

        $validated['is_admin'] = $isAdmin;

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return back()->withErrors([
                'user' => 'Akun admin yang sedang digunakan tidak dapat dihapus.',
            ]);
        }

        if ($user->isAdmin() && User::query()->where('is_admin', true)->count() === 1) {
            return back()->withErrors([
                'user' => 'Setidaknya harus ada satu akun admin.',
            ]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
