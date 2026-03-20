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
            'role' => 'nullable|in:customer,admin,super',
            'super_admin_key' => 'required_if:role,super'
        ]);

        $roleType = $request->role ?? 'customer';

        if ($roleType === 'super') {
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
        $roleMapping = [
            'customer' => 'customer',
            'admin' => 'admin',
            'super' => 'super_admin'
        ];
        
        $roleSlug = $roleMapping[$roleType];
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
            'role' => 'nullable|in:customer,admin,super'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        $user = Auth::user();
        $roleType = $request->role ?? 'customer';
        
        $roleMapping = [
            'customer' => 'customer',
            'admin' => 'admin',
            'super' => 'super_admin'
        ];
        
        $roleSlug = $roleMapping[$roleType];

        // Ensure user has the correct role
        if (!$user->hasRole($roleSlug) && $roleType !== 'customer') {
             // For non-customers, we strictly check role.
             Auth::logout();
             return response()->json(['message' => 'Unauthorized: Incorrect role for this endpoint'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $roleSlug,
            'user' => [
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:8|confirmed'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
