<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(): View
    {
        $users = User::with(['department', 'roles'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can add users.');
        }

        $roles = Role::all(); 
        $departments = class_exists(Department::class) ? Department::all() : collect();

        return view('users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can save users.');
        }

        // 1. Validate input data
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email',
            'password'       => 'required|string|min:8',
            'role'           => 'required|exists:roles,name',
            'department_id'  => 'nullable|exists:departments,id',
            'required_hours' => 'nullable|integer|min:0',
        ]);

        $isOjt = ($request->role === 'ojt');

        // 2. Create the user record (use 0 instead of null for non-OJT users)
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'department_id'  => $request->department_id,
            'is_ojt'         => $isOjt,
            'required_hours' => $isOjt ? ($request->required_hours ?? 486) : 0,
        ]);

        // 3. Assign the Spatie Role
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User added successfully! ✅');
    }

    /**
     * Display the specified user details.
     */
    public function show(User $user): View
    {
        $user->load(['department', 'roles', 'ojtAttendances']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing an existing user.
     */
    public function edit(User $user): View
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can edit users.');
        }

        $roles = Role::all();
        $departments = class_exists(Department::class) ? Department::all() : collect();

        return view('users.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can update users.');
        }

        // 1. Validate updated details
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'       => 'nullable|string|min:8',
            'role'           => 'required|exists:roles,name',
            'department_id'  => 'nullable|exists:departments,id',
            'required_hours' => 'nullable|integer|min:0',
        ]);

        $isOjt = ($request->role === 'ojt');

        // 2. Update basic info & attributes
        $user->name          = $request->name;
        $user->email         = $request->email;
        $user->department_id = $request->department_id;
        $user->is_ojt        = $isOjt;

        // Set hours to 0 instead of null if user is not an OJT intern
        if ($isOjt) {
            $user->required_hours = $request->required_hours ?? ($user->required_hours ?? 486);
        } else {
            $user->required_hours = 0;
        }

        // 3. Update password only if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // 4. Sync Spatie role
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'User updated successfully! ✏️');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // 🔒 SECURITY CHECK: Only Admin and IT Staff
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can delete users.');
        }

        // Prevent self-deletion
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account! ❌');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully! 🗑️');
    }
}