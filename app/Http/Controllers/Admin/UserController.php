<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users', ['users' => User::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:100'], 'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')], 'role' => ['required', 'in:admin,staff'], 'password' => ['nullable', 'string', 'min:10']]);
        $password = $data['password'] ?: Str::password(14, symbols: false);
        User::create(['name' => $data['name'], 'email' => $data['email'], 'role' => $data['role'], 'password' => $password]);

        return back()->with('saved', 'Login created.')->with('credentials', ['email' => $data['email'], 'password' => $password]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['role' => ['nullable', 'in:admin,staff'], 'reset' => ['nullable', 'boolean']]);
        if ($request->boolean('reset')) {
            $password = Str::password(14, symbols: false);
            $user->forceFill(['password' => $password])->save();

            return back()->with('saved', 'Password reset.')->with('credentials', ['email' => $user->email, 'password' => $password]);
        }
        if ($user->id === $request->user()->id && ($data['role'] ?? 'admin') !== 'admin') {
            return back()->withErrors(['role' => 'You cannot remove your own admin access.']);
        }
        $user->update(['role' => $data['role'] ?? $user->role]);

        return back()->with('saved', 'Role updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 403, 'You cannot delete your own login.');
        $user->delete();

        return back()->with('saved', 'Login removed.');
    }
}
