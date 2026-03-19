<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,super',
            'super_admin_key' => 'required_if:role,super'
        ]);

        // Production Fix: Check Super Admin Key in API too
        if ($request->role === 'super') {
            $expectedKey = env('ADMIN_SUPER_KEY', 'arcadia-admin-2026');
            if ($request->super_admin_key !== $expectedKey) {
                return response()->json(['message' => 'Unauthorized: Invalid Super Admin Access Key'], 403);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign Role
        $roleSlug = ($request->role === 'super') ? 'super_admin' : 'admin';
        $role = \App\Models\Role::where('slug', $roleSlug)->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $roleSlug
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,super'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user = Auth::user();

        // Production Fix: Ensure user has the correct role for API access
        $roleSlug = ($request->role === 'super') ? 'super_admin' : 'admin';
        if (!$user->hasRole($roleSlug)) {
            $token = $user->currentAccessToken();
            if ($token) $token->delete();
            Auth::logout();
            return response()->json(['message' => 'Unauthorized: Incorrect role for this endpoint'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $roleSlug
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
