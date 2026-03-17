<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        /** @var \App\Models\User $user */
        if (Auth::check() && ($user = Auth::user()) && $user->isAnyAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user->isAnyAdmin()) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'You do not have administrative access.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegisterForm()
    {
        /** @var \App\Models\User $user */
        if (Auth::check() && ($user = Auth::user()) && $user->isAnyAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['sometimes', 'string', 'in:admin,super'],
            'super_admin_key' => ['required_if:role,super'],
        ]);

        if ($request->role === 'super') {
            if ($request->super_admin_key !== config('app.admin_super_key')) {
                return back()->withErrors(['super_admin_key' => 'Invalid Super Admin authorization key.'])->withInput();
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role based on request or default to 'admin'
        $roleSlug = $request->role === 'super' ? 'super_admin' : 'admin';
        $role = Role::where('slug', $roleSlug)->first();
        
        // Auto-create role if missing
        if (!$role) {
            if ($roleSlug === 'super_admin') {
                $role = Role::create([
                    'slug' => 'super_admin',
                    'name' => 'Super Administrator',
                    'description' => 'System owner with unrestricted access.'
                ]);
            } else {
                $role = Role::create([
                    'slug' => 'admin',
                    'name' => 'Administrator',
                    'description' => 'System administrator with management access.'
                ]);
            }
        }
        
        if ($role) {
            $user->roles()->attach($role);
        }

        Auth::login($user);

        return redirect()->route('admin.dashboard');
    }

    public function showForgotPasswordForm()
    {
        return "Forgot password functionality is coming soon.";
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
