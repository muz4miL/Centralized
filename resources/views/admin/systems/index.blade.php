<x-layouts.dashboard pageTitle="System Management">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">System Management</h1>
            <p class="text-sm text-slate-600 mt-1">Manage systems and role-based access control</p>
            
            {{-- Inline Stats Summary --}}
            <div class="flex items-center gap-6 mt-3 text-sm">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500">Active Systems:</span>
                    <span class="font-bold text-slate-900">{{ $systems->where('coming_soon', false)->count() }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500">Roles Configured:</span>
                    <span class="font-bold text-slate-900">{{ count($roles) }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500">Permission Mappings:</span>
                    <span class="font-bold text-slate-900">{{ \App\Models\RoleSystemAccess::count() }}</span>
                </div>
            </div>
        </div>
        <button 
            onclick="document.getElementById('addSystemModal').classList.remove('hidden')"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-semibold text-sm"
        >
            + Add New System
        </button>
    </div>

    <div id="successMessage" class="hidden mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg"></div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Systems Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">System</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Assigned Roles</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($systems as $system)
                    @php
                        // Determine system category and icon/color based on name
                        $iconData = match(true) {
                            str_contains(strtolower($system->name), 'analytic') || str_contains(strtolower($system->name), 'emis') => [
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>',
                                'bg' => 'bg-violet-100',
                                'text' => 'text-violet-600'
                            ],
                            str_contains(strtolower($system->name), 'enrollment') || str_contains(strtolower($system->name), 'district') => [
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
                                'bg' => 'bg-blue-100',
                                'text' => 'text-blue-600'
                            ],
                            str_contains(strtolower($system->name), 'attendance') => [
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>',
                                'bg' => 'bg-blue-100',
                                'text' => 'text-blue-600'
                            ],
                            str_contains(strtolower($system->name), 'budget') || str_contains(strtolower($system->name), 'grant') || str_contains(strtolower($system->name), 'financ') => [
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>',
                                'bg' => 'bg-amber-100',
                                'text' => 'text-amber-600'
                            ],
                            str_contains(strtolower($system->name), 'facilitat') || str_contains(strtolower($system->name), 'director') || str_contains(strtolower($system->name), 'staff') => [
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                                'bg' => 'bg-blue-100',
                                'text' => 'text-blue-600'
                            ],
                            str_contains(strtolower($system->name), 'exam') || str_contains(strtolower($system->name), 'academic') => [
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
                                'bg' => 'bg-teal-100',
                                'text' => 'text-teal-600'
                            ],
                            default => [
                                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>',
                                'bg' => 'bg-slate-100',
                                'text' => 'text-slate-600'
                            ]
                        };
                    @endphp
                    <tr class="group hover:bg-{{ str_replace('bg-', '', $iconData['bg']) }}/10 transition-all duration-200 hover:shadow-sm">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $iconData['bg'] }} {{ $iconData['text'] }} group-hover:scale-105 transition-transform">
                                    {!! $iconData['icon'] !!}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $system->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $system->slug }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                @forelse($system->access as $accessItem)
                                    @php
                                        $roleObj = \App\Models\Role::where('slug', $accessItem->role)->first();
                                        $roleName = $roleObj ? $roleObj->name : ucfirst($accessItem->role);
                                        
                                        // Visual hierarchy for roles
                                        $roleStyle = match(strtolower($accessItem->role)) {
                                            'admin' => 'bg-primary-600 text-white font-bold',
                                            'principal' => 'bg-primary-500 text-white font-semibold',
                                            default => 'bg-primary-50 text-primary-700 border border-primary-200 font-medium'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 {{ $roleStyle }} text-xs rounded">
                                        {{ $roleName }}
                                    </span>
                                @empty
                                    <span class="text-sm text-slate-400 italic">No roles assigned</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($system->coming_soon)
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Coming Soon
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <button 
                                    onclick='openPermissionsModal({{ $system->id }}, "{{ addslashes($system->name) }}", @json($system->access->pluck("role")->toArray()))'
                                    class="text-purple-600 hover:text-purple-700 font-medium text-sm hover:underline"
                                >
                                    Permissions
                                </button>
                                
                                {{-- Overflow Menu --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button 
                                        @click="open = !open"
                                        class="text-slate-400 hover:text-slate-600 p-1 rounded hover:bg-slate-100 transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/>
                                        </svg>
                                    </button>
                                    <div 
                                        x-show="open" 
                                        @click.outside="open = false"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-36 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-10"
                                        style="display: none;"
                                    >
                                        <button 
                                            onclick="openEditModal({{ $system->id }}, '{{ addslashes($system->name) }}', '{{ $system->slug }}', '{{ addslashes($system->description) }}', '{{ $system->icon }}', '{{ $system->accent_color }}', {{ $system->coming_soon ? 'true' : 'false' }}, '{{ $system->launch_url }}', {{ $system->sort_order }})"
                                            class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            onclick="deleteSystem({{ $system->id }}, '{{ addslashes($system->name) }}')"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add System Modal -->
    <div id="addSystemModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Add New System</h2>
            
            <form onsubmit="addSystem(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">System Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Slug (URL-friendly)</label>
                        <input type="text" name="slug" required pattern="[a-z0-9-]+" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        <p class="text-xs text-slate-500 mt-1">Use lowercase letters, numbers, and hyphens only</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Icon</label>
                            <select name="icon" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                <option value="chart-bar">Chart Bar</option>
                                <option value="users">Users</option>
                                <option value="calendar-check">Calendar Check</option>
                                <option value="currency-dollar">Currency Dollar</option>
                                <option value="user-group">User Group</option>
                                <option value="academic-cap">Academic Cap</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Accent Color</label>
                            <input type="color" name="accent_color" value="#7c5df2" required class="w-full h-10 px-2 py-1 border border-slate-300 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Launch URL (optional)</label>
                        <input type="text" name="launch_url" placeholder="/dashboard" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ $systems->count() + 1 }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        </div>

                        <div class="flex items-center pt-8">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="coming_soon" class="w-4 h-4 text-primary-600 rounded">
                                <span class="text-sm font-semibold text-slate-700">Coming Soon</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Grant Access To:</label>
                        <div class="flex gap-4">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="{{ $role }}" class="w-4 h-4 text-primary-600 rounded">
                                    <span class="text-sm text-slate-700">{{ ucfirst($role) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t">
                    <button type="button" onclick="document.getElementById('addSystemModal').classList.add('hidden')" class="px-4 py-2 text-slate-600 hover:text-slate-800 font-semibold">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-semibold">
                        Add System
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit System Modal -->
    <div id="editSystemModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Edit System</h2>
            
            <form onsubmit="updateSystem(event)">
                <input type="hidden" name="system_id" id="edit_system_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">System Name</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Slug (cannot be changed)</label>
                        <input type="text" id="edit_slug" disabled class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-100">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Icon</label>
                            <select name="icon" id="edit_icon" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                                <option value="chart-bar">Chart Bar</option>
                                <option value="users">Users</option>
                                <option value="calendar-check">Calendar Check</option>
                                <option value="currency-dollar">Currency Dollar</option>
                                <option value="user-group">User Group</option>
                                <option value="academic-cap">Academic Cap</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Accent Color</label>
                            <input type="color" name="accent_color" id="edit_accent_color" required class="w-full h-10 px-2 py-1 border border-slate-300 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Launch URL (optional)</label>
                        <input type="text" name="launch_url" id="edit_launch_url" placeholder="/dashboard" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order</label>
                            <input type="number" name="sort_order" id="edit_sort_order" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                        </div>

                        <div class="flex items-center pt-8">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="coming_soon" id="edit_coming_soon" class="w-4 h-4 text-primary-600 rounded">
                                <span class="text-sm font-semibold text-slate-700">Coming Soon</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t">
                    <button type="button" onclick="document.getElementById('editSystemModal').classList.add('hidden')" class="px-4 py-2 text-slate-600 hover:text-slate-800 font-semibold">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-semibold">
                        Update System
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Permissions Modal -->
    <div id="permissionsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Manage Permissions</h2>
                    <p class="text-sm text-slate-600 mt-1">System: <span id="permissions_system_name" class="font-semibold"></span></p>
                </div>
                <button onclick="closePermissionsModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <input type="hidden" id="permissions_system_id">
            
            <div class="space-y-2" id="permissions_list">
                <!-- Checkboxes will be dynamically generated here -->
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t">
                <button type="button" onclick="closePermissionsModal()" class="px-4 py-2 text-slate-600 hover:text-slate-800 font-semibold">
                    Cancel
                </button>
                <button type="button" onclick="savePermissions()" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-semibold">
                    Save Permissions
                </button>
            </div>
        </div>
    </div>

    <script>
        // All available roles (fetched from backend)
        const allRoles = @json(\App\Models\Role::orderBy('sort_order')->get(['id', 'name', 'slug']));

        // Toast notification
        function showToast(message, type = 'success') {
            const div = document.getElementById('successMessage');
            div.textContent = message;
            div.className = `mb-6 p-4 rounded-lg ${type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'}`;
            div.classList.remove('hidden');
            setTimeout(() => div.classList.add('hidden'), 4000);
        }

        // Open permissions modal
        function openPermissionsModal(systemId, systemName, assignedRoles) {
            document.getElementById('permissions_system_id').value = systemId;
            document.getElementById('permissions_system_name').textContent = systemName;
            
            // Build checkboxes
            const container = document.getElementById('permissions_list');
            container.innerHTML = '';
            
            allRoles.forEach(role => {
                const isChecked = assignedRoles.includes(role.slug);
                container.innerHTML += `
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                        <input 
                            type="checkbox" 
                            value="${role.slug}" 
                            ${isChecked ? 'checked' : ''}
                            class="w-5 h-5 text-primary-600 rounded focus:ring-2 focus:ring-primary-500"
                        >
                        <div class="flex-1">
                            <div class="font-semibold text-slate-900">${role.name}</div>
                            <div class="text-xs text-slate-500">${role.slug}</div>
                        </div>
                    </label>
                `;
            });
            
            document.getElementById('permissionsModal').classList.remove('hidden');
        }

        function closePermissionsModal() {
            document.getElementById('permissionsModal').classList.add('hidden');
        }

        // Save permissions
        async function savePermissions() {
            const systemId = document.getElementById('permissions_system_id').value;
            const checkboxes = document.querySelectorAll('#permissions_list input[type="checkbox"]');
            const selectedRoles = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            try {
                // Update each role's access
                for (const role of allRoles.map(r => r.slug)) {
                    const hasAccess = selectedRoles.includes(role);
                    
                    await fetch(`/admin/systems/${systemId}/access`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ role, has_access: hasAccess })
                    });
                }

                showToast('Permissions updated successfully! Reloading...');
                closePermissionsModal();
                setTimeout(() => location.reload(), 1000);
            } catch (error) {
                showToast('Error updating permissions', 'error');
            }
        }

        // Open edit modal
        function openEditModal(id, name, slug, description, icon, accentColor, comingSoon, launchUrl, sortOrder) {
            document.getElementById('edit_system_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_slug').value = slug;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_icon').value = icon;
            document.getElementById('edit_accent_color').value = accentColor;
            document.getElementById('edit_launch_url').value = launchUrl || '';
            document.getElementById('edit_sort_order').value = sortOrder;
            document.getElementById('edit_coming_soon').checked = comingSoon;
            document.getElementById('editSystemModal').classList.remove('hidden');
        }

        // Update system
        async function updateSystem(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const systemId = formData.get('system_id');

            const data = {
                name: formData.get('name'),
                description: formData.get('description') || null,
                icon: formData.get('icon'),
                accent_color: formData.get('accent_color'),
                launch_url: formData.get('launch_url') || null,
                sort_order: parseInt(formData.get('sort_order')),
                coming_soon: document.getElementById('edit_coming_soon').checked
            };

            try {
                const response = await fetch(`/admin/systems/${systemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    showToast('System updated! Reloading...');
                    document.getElementById('editSystemModal').classList.add('hidden');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    const result = await response.json();
                    showToast('Error: ' + (result.message || 'Could not update system'), 'error');
                }
            } catch (error) {
                showToast('Error updating system', 'error');
            }
        }

        // Add new system
        async function addSystem(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            try {
                const response = await fetch('/admin/systems', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                if (response.ok) {
                    showToast('System added successfully! Reloading...');
                    document.getElementById('addSystemModal').classList.add('hidden');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    const data = await response.json();
                    showToast('Error: ' + (data.message || 'Could not add system'), 'error');
                }
            } catch (error) {
                showToast('Error adding system', 'error');
            }
        }

        // Delete system with confirmation
        async function deleteSystem(systemId, systemName) {
            if (!confirm(`Delete "${systemName}"? This action cannot be undone.`)) {
                return;
            }

            try {
                const response = await fetch(`/admin/systems/${systemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    showToast('System deleted! Reloading...');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('Error deleting system', 'error');
                }
            } catch (error) {
                showToast('Error deleting system', 'error');
            }
        }
    </script>

</x-layouts.dashboard>
