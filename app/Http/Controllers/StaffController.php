<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    /**
     * Display the staff directory list.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedRole = $request->input('role');

        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($selectedRole) {
            $query->where('role', $selectedRole);
        }

        $staffList = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('staff.index', compact('staffList', 'search', 'selectedRole'));
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,principal,teacher'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('staff')->with('success', 'Staff member added successfully!');
    }

    /**
     * Update the specified staff member's role.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:admin,principal,teacher'],
        ]);

        // Prevent admin from removing their own admin role
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return redirect()->route('staff')->with('error', 'You cannot change your own admin role!');
        }

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('staff')->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('staff')->with('error', 'You cannot delete yourself!');
        }

        $user->delete();

        return redirect()->route('staff')->with('success', 'Staff member deleted successfully!');
    }
}
