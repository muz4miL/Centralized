<?php

namespace App\Http\Controllers;

use App\Models\RoleSystemAccess;
use App\Models\System;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemManagementController extends Controller
{
    /**
     * Show the system management page (admin only).
     */
    public function index(): View
    {
        $systems = System::with('access')->orderBy('sort_order')->get();
        $roles = \App\Models\Role::orderBy('sort_order')->pluck('slug')->toArray();
        
        return view('admin.systems.index', compact('systems', 'roles'));
    }

    /**
     * Store a new system.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:systems',
            'description' => 'nullable|string',
            'icon' => 'required|string',
            'accent_color' => 'required|string|max:9',
            'coming_soon' => 'boolean',
            'launch_url' => 'nullable|string',
            'sort_order' => 'required|integer',
            'roles' => 'array',
        ]);

        $system = System::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'],
            'accent_color' => $validated['accent_color'],
            'coming_soon' => $validated['coming_soon'] ?? false,
            'launch_url' => $validated['launch_url'] ?? null,
            'sort_order' => $validated['sort_order'],
        ]);

        // Grant access to selected roles
        if (!empty($validated['roles'])) {
            foreach ($validated['roles'] as $role) {
                RoleSystemAccess::create([
                    'role' => $role,
                    'system_id' => $system->id,
                ]);
            }
        }

        return redirect()->route('admin.systems.index')
            ->with('success', 'System created successfully!');
    }

    /**
     * Update system access for a role.
     */
    public function updateAccess(Request $request, System $system): RedirectResponse
    {
        // Get valid role slugs from database
        $validRoles = \App\Models\Role::pluck('slug')->toArray();
        
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:' . implode(',', $validRoles)],
            'has_access' => 'required|boolean',
        ]);

        if ($validated['has_access']) {
            // Grant access
            RoleSystemAccess::firstOrCreate([
                'role' => $validated['role'],
                'system_id' => $system->id,
            ]);
        } else {
            // Revoke access
            RoleSystemAccess::where('role', $validated['role'])
                ->where('system_id', $system->id)
                ->delete();
        }

        return back()->with('success', 'Access updated successfully!');
    }

    /**
     * Update system details.
     */
    public function update(Request $request, System $system): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string',
            'accent_color' => 'required|string|max:9',
            'coming_soon' => 'boolean',
            'launch_url' => 'nullable|string',
            'sort_order' => 'required|integer',
        ]);

        // Explicitly set coming_soon to false if not present in request
        $validated['coming_soon'] = $request->has('coming_soon') ? (bool)$validated['coming_soon'] : false;

        $system->update($validated);

        return back()->with('success', 'System updated successfully!');
    }

    /**
     * Delete a system.
     */
    public function destroy(System $system): RedirectResponse
    {
        $system->delete();

        return redirect()->route('admin.systems.index')
            ->with('success', 'System deleted successfully!');
    }
}
