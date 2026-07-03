<x-layouts.dashboard pageTitle="Role Management">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Role Management</h1>
            <p class="text-sm text-slate-600 mt-1">Define roles that can be assigned to users and systems</p>
        </div>
        <button 
            onclick="document.getElementById('addRoleModal').classList.remove('hidden')"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-semibold text-sm"
        >
            + Add New Role
        </button>
    </div>

    <div id="successMessage" class="hidden mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg"></div>

    <!-- Roles Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Role Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Description</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Tier</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($roles as $role)
                    @php
                        // Calculate accent intensity based on sort_order (lower order = higher access = darker)
                        $totalRoles = $roles->count();
                        $intensity = $totalRoles > 1 
                            ? 900 - (($role->sort_order - 1) / ($totalRoles - 1)) * 300  // 900 to 600
                            : 900;
                        $accentClass = 'bg-primary-' . round($intensity / 100) * 100;
                        
                        // Determine access tier indicator based on sort_order
                        $tierIndicator = match(true) {
                            $role->sort_order <= 1 => '●●●', // Full access
                            $role->sort_order <= 2 => '●●○', // Mid-tier
                            default => '●○○' // Limited
                        };
                        
                        // Hover color for row
                        $hoverTint = 'hover:bg-primary-50/30';
                    @endphp
                    <tr class="relative group {{ $hoverTint }} transition-all duration-200 hover:shadow-sm" x-data="{ confirmDelete: false }">
                        {{-- Left accent bar --}}
                        <td class="absolute left-0 top-0 bottom-0 w-1 {{ $accentClass }}"></td>
                        
                        <td class="px-6 py-4 pl-8">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-900">{{ $role->name }}</span>
                                <span class="text-xs text-primary-400 tracking-wider" title="Access Tier Indicator">{{ $tierIndicator }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="px-2.5 py-1 bg-primary-50/50 text-primary-700 rounded text-sm font-mono border border-primary-100">{{ $role->slug }}</code>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $role->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-primary-100 text-primary-700 text-xs font-bold">
                                {{ $role->sort_order }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Edit Button --}}
                                <button 
                                    onclick="openEditModal({{ $role->id }}, '{{ addslashes($role->name) }}', '{{ $role->slug }}', '{{ addslashes($role->description ?? '') }}', {{ $role->sort_order }})"
                                    class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded transition-colors"
                                    title="Edit role"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/>
                                    </svg>
                                </button>
                                
                                {{-- Delete Button with Confirmation --}}
                                <button 
                                    @click="confirmDelete = !confirmDelete"
                                    x-show="!confirmDelete"
                                    class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                    title="Delete role"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>
                                    </svg>
                                </button>
                                
                                <div x-show="confirmDelete" 
                                     x-transition
                                     @click.outside="confirmDelete = false"
                                     class="flex items-center gap-2"
                                     style="display: none;">
                                    <span class="text-xs text-slate-600 font-medium">Confirm?</span>
                                    <button 
                                        onclick="deleteRole({{ $role->id }})"
                                        class="px-2 py-1 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded transition-colors"
                                    >
                                        Yes
                                    </button>
                                    <button 
                                        @click="confirmDelete = false"
                                        class="px-2 py-1 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors"
                                    >
                                        No
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Info Callout Card -->
    <div class="mt-6 p-5 bg-blue-50 border border-blue-200 rounded-xl border-l-4 border-l-blue-500 relative">
        <div class="flex gap-3">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                    <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-blue-900 mb-2">How roles work:</h3>
                <ul class="text-sm text-blue-800 space-y-1.5">
                    <li>• The <strong>slug</strong> is used in the database and URLs (lowercase, no spaces)</li>
                    <li>• The <strong>name</strong> is shown to users (e.g., "Managing Director")</li>
                    <li>• After adding a role, go to <strong>System Management</strong> to assign it to systems</li>
                    <li>• You cannot delete a role that is assigned to systems</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div id="addRoleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-lg w-full mx-4">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Add New Role</h2>
            
            <form onsubmit="addRole(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Role Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            required 
                            placeholder="e.g., Managing Director"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Slug * (lowercase, no spaces)</label>
                        <input 
                            type="text" 
                            name="slug" 
                            required 
                            pattern="[a-z0-9-]+"
                            placeholder="e.g., admin"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        >
                        <p class="text-xs text-slate-500 mt-1">This will be stored in the database</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea 
                            name="description" 
                            rows="2"
                            placeholder="e.g., Full system access"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order *</label>
                        <input 
                            type="number" 
                            name="sort_order" 
                            value="{{ $roles->count() + 1 }}" 
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t">
                    <button 
                        type="button" 
                        onclick="document.getElementById('addRoleModal').classList.add('hidden')" 
                        class="px-4 py-2 text-slate-600 hover:text-slate-800 font-semibold"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-semibold"
                    >
                        Add Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div id="editRoleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-lg w-full mx-4">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">Edit Role</h2>
            
            <form onsubmit="updateRole(event)">
                <input type="hidden" name="role_id" id="edit_role_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Role Name *</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="edit_name"
                            required 
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Slug (cannot be changed)</label>
                        <input 
                            type="text" 
                            id="edit_slug"
                            disabled
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-100 text-slate-500"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea 
                            name="description" 
                            id="edit_description"
                            rows="2"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order *</label>
                        <input 
                            type="number" 
                            name="sort_order" 
                            id="edit_sort_order"
                            required
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t">
                    <button 
                        type="button" 
                        onclick="document.getElementById('editRoleModal').classList.add('hidden')" 
                        class="px-4 py-2 text-slate-600 hover:text-slate-800 font-semibold"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-semibold"
                    >
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(roleId, name, slug, description, sortOrder) {
            document.getElementById('edit_role_id').value = roleId;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_slug').value = slug;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_sort_order').value = sortOrder;
            document.getElementById('editRoleModal').classList.remove('hidden');
        }

        async function updateRole(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const roleId = formData.get('role_id');
            
            const data = {
                name: formData.get('name'),
                description: formData.get('description') || null,
                sort_order: parseInt(formData.get('sort_order'))
            };

            try {
                const response = await fetch(`/admin/roles/${roleId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showSuccess('Role updated! Reloading...');
                    document.getElementById('editRoleModal').classList.add('hidden');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('Error: ' + (result.message || 'Could not update role'));
                }
            } catch (error) {
                alert('Error updating role: ' + error.message);
            }
        }

        async function addRole(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            const data = {
                name: formData.get('name'),
                slug: formData.get('slug'),
                description: formData.get('description'),
                sort_order: parseInt(formData.get('sort_order'))
            };

            try {
                const response = await fetch('/admin/roles', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    showSuccess('Role added successfully! Reloading...');
                    document.getElementById('addRoleModal').classList.add('hidden');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('Error: ' + (result.message || 'Could not add role'));
                }
            } catch (error) {
                alert('Error adding role: ' + error.message);
            }
        }

        async function deleteRole(roleId) {
            try {
                const response = await fetch(`/admin/roles/${roleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    showSuccess('Role deleted! Reloading...');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(result.message || 'Error deleting role');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        function showSuccess(message) {
            const div = document.getElementById('successMessage');
            div.textContent = message;
            div.classList.remove('hidden');
            setTimeout(() => div.classList.add('hidden'), 3000);
        }
    </script>

</x-layouts.dashboard>
