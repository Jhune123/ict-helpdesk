<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // ✅ ADDED AUTH FACADE
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Fetch all users, newest first
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can add users.');
        }

        $roles = Role::all(); 
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can save users.');
        }

        // 1. Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        // 2. Create the user and hash the password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Assign the Spatie Role
        $user->assignRole($request->role);

        // 4. Redirect back to table
        return redirect()->route('users.index')->with('success', 'User added successfully! ✅');
    }

    public function edit(User $user)
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can edit users.');
        }

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can update users.');
        }

        // 1. Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        // 2. Update basic info
        $user->name = $request->name;
        $user->email = $request->email;

        // 3. Only update password if they typed a new one
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 4. Sync the new role (removes old roles, adds new one)
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User updated successfully! ✏️');
    }

    public function destroy(User $user)
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can delete users.');
        }

        // Prevent users from deleting themselves
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account! ❌');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully! 🗑️');
    }
}