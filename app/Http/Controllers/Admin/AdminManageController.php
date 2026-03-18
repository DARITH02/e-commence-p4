<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewAdminRegistered;
use Illuminate\Support\Facades\Notification;

class AdminManageController extends Controller
{
    private function checkSuperAdmin()
    {
        if (!Auth::user()->isSuperAdmin()) {
            if (request()->ajax()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized actions.');
        }
    }

    public function index()
    {
        $this->checkSuperAdmin();
        $admins = User::whereHas('roles', function($q) {
            $q->whereIn('slug', ['admin', 'super_admin']);
        })->with('roles')->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function store(Request $request)
    {
        $this->checkSuperAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,slug',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::where('slug', $request->role)->first();
        $user->roles()->attach($role->id);

        // Notify other Super Admins
        $superAdmins = User::whereHas('roles', function($q) {
            $q->where('slug', 'super_admin');
        })->where('id', '!=', Auth::id())->get();
        
        Notification::send($superAdmins, new NewAdminRegistered($user));

        return response()->json(['message' => __('admin.admin_created_success')]);
    }

    public function update(Request $request, User $user)
    {
        $this->checkSuperAdmin();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|exists:roles,slug',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $role = Role::where('slug', $request->role)->first();
        $user->roles()->sync([$role->id]);

        return response()->json(['message' => __('admin.admin_updated_success')]);
    }

    public function destroy(User $user)
    {
        $this->checkSuperAdmin();
        // Prevent deleting self
        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'Cannot delete your own account.'], 422);
        }

        $user->delete();
        return response()->json(['message' => __('admin.admin_deleted_success')]);
    }
}
