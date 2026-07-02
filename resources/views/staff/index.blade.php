<x-layouts.dashboard
    pageTitle="Staff Management"
    breadcrumb="Home / Staff Directory"
>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 py-3 px-4 bg-success-light rounded-btn mb-6 transition-all duration-200">
            <svg class="w-5 h-5 text-success flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>
            </svg>
            <p class="text-sm font-medium text-success-dark">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 py-3 px-4 bg-danger-light rounded-btn mb-6 transition-all duration-200">
            <svg class="w-5 h-5 text-danger flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/>
            </svg>
            <p class="text-sm font-medium text-danger-dark">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Top Action & Filters --}}
    <div class="chart-card mb-6" x-data="{ addModalOpen: false }">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('staff') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                {{-- Search --}}
                <div class="flex items-center bg-surface-50 border border-surface-200 rounded-btn px-3 py-2 focus-within:ring-2 focus-within:ring-primary-500/30 focus-within:border-primary-400 transition-all">
                    <svg class="w-4 h-4 text-surface-400 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search name or email..."
                           class="bg-transparent border-none text-sm text-surface-700 placeholder-surface-400 focus:ring-0 focus:outline-none w-48">
                </div>

                {{-- Role Dropdown --}}
                <select name="role" class="bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400 transition-all">
                    <option value="">All Roles</option>
                    <option value="admin" {{ $selectedRole === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="principal" {{ $selectedRole === 'principal' ? 'selected' : '' }}>Principal</option>
                    <option value="teacher" {{ $selectedRole === 'teacher' ? 'selected' : '' }}>Teacher</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-surface-200 hover:bg-surface-300 text-surface-700 text-sm font-semibold rounded-btn transition-colors">
                    Filter
                </button>
            </form>

            {{-- Trigger Modal --}}
            <button @click="addModalOpen = true"
                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-btn hover:bg-primary-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Add Staff Member
            </button>
        </div>

        {{-- Add Staff Modal --}}
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 transition-all duration-300"
             x-show="addModalOpen" x-transition style="display: none;">
            <div class="bg-white rounded-card shadow-lg max-w-md w-full overflow-hidden" @click.outside="addModalOpen = false">
                <div class="bg-primary-600 px-6 py-4 flex items-center justify-between text-white">
                    <h3 class="font-heading font-bold text-lg">Add New Staff Member</h3>
                    <button @click="addModalOpen = false" class="text-white/80 hover:text-white">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form action="{{ route('staff.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1">Name</label>
                        <input type="text" name="name" required placeholder="Full Name"
                               class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" required placeholder="name@school.com"
                               class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" name="password" required placeholder="Minimum 8 characters"
                               class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1">Assigned Role</label>
                        <select name="role" required class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400">
                            <option value="teacher">Teacher</option>
                            <option value="principal">Principal</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 text-sm font-semibold text-surface-500 hover:text-surface-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-btn transition-colors">
                            Save Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Staff Directory Table --}}
    <div class="chart-card">
        @if($staffList->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/></svg>
                <p class="text-base font-medium text-surface-500">No staff members found matching parameters</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-200 text-left">
                            <th class="py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Member</th>
                            <th class="py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Assigned Role</th>
                            <th class="py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Joined Date</th>
                            <th class="py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @foreach($staffList as $staff)
                        <tr class="hover:bg-surface-50 transition-colors duration-150"
                            x-data="{ editModalOpen: false, deleteConfirmOpen: false }">
                            {{-- Info --}}
                            <td class="py-4 px-4 flex items-center gap-3">
                                <div class="w-9 h-9 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-surface-800 leading-tight">{{ $staff->name }}</p>
                                    <p class="text-xs text-surface-400 mt-0.5">{{ $staff->email }}</p>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $staff->role === 'admin' ? 'bg-primary-100 text-primary-700' :
                                       ($staff->role === 'principal' ? 'bg-info-light text-info-dark' : 'bg-warning-light text-warning-dark') }}">
                                    {{ ucfirst($staff->role) }}
                                </span>
                            </td>

                            {{-- Joined --}}
                            <td class="py-4 px-4 text-surface-400 text-xs">
                                {{ $staff->created_at->format('M d, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="py-4 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    {{-- Edit Role Button --}}
                                    <button @click="editModalOpen = true" class="text-xs font-semibold text-primary-600 hover:text-primary-700 hover:underline">
                                        Change Role
                                    </button>

                                    {{-- Delete Button (Self excluded) --}}
                                    @if($staff->id !== auth()->id())
                                        <button @click="deleteConfirmOpen = true" class="text-xs font-semibold text-danger hover:text-danger-dark hover:underline">
                                            Remove
                                        </button>
                                    @endif
                                </div>

                                {{-- Edit Role Modal --}}
                                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 text-left transition-all duration-300"
                                     x-show="editModalOpen" x-transition style="display: none;">
                                    <div class="bg-white rounded-card shadow-lg max-w-sm w-full overflow-hidden" @click.outside="editModalOpen = false">
                                        <div class="bg-primary-600 px-6 py-4 flex items-center justify-between text-white">
                                            <h3 class="font-heading font-bold text-base">Update {{ $staff->name }}'s Role</h3>
                                            <button @click="editModalOpen = false" class="text-white/80 hover:text-white">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                        <form action="{{ route('staff.update', $staff->id) }}" method="POST" class="p-6 space-y-4">
                                            @csrf
                                            @method('PATCH')
                                            <div>
                                                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-2">Select Role</label>
                                                <select name="role" class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400">
                                                    <option value="teacher" {{ $staff->role === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                                    <option value="principal" {{ $staff->role === 'principal' ? 'selected' : '' }}>Principal</option>
                                                    <option value="admin" {{ $staff->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </div>
                                            <div class="flex items-center justify-end gap-3 pt-2">
                                                <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-sm font-semibold text-surface-500 hover:text-surface-700 transition-colors">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-btn transition-colors">
                                                    Update Role
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Delete Confirmation Modal --}}
                                <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 text-left transition-all duration-300"
                                     x-show="deleteConfirmOpen" x-transition style="display: none;">
                                    <div class="bg-white rounded-card shadow-lg max-w-sm w-full overflow-hidden" @click.outside="deleteConfirmOpen = false">
                                        <div class="p-6">
                                            <div class="w-12 h-12 rounded-full bg-danger-light text-danger flex items-center justify-center mb-4 mx-auto">
                                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </div>
                                            <h3 class="font-heading font-bold text-lg text-center text-surface-900 mb-2">Remove Staff Member?</h3>
                                            <p class="text-sm text-surface-500 text-center mb-6">Are you sure you want to remove {{ $staff->name }}? This action is permanent and cannot be undone.</p>
                                            <div class="flex items-center justify-center gap-3">
                                                <button type="button" @click="deleteConfirmOpen = false" class="px-4 py-2 text-sm font-semibold text-surface-500 hover:text-surface-700 transition-colors">
                                                    Cancel
                                                </button>
                                                <form action="{{ route('staff.destroy', $staff->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-5 py-2 bg-danger hover:bg-danger-dark text-white text-sm font-semibold rounded-btn transition-colors">
                                                        Confirm Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($staffList->hasPages())
                <div class="mt-6 flex items-center justify-between">
                    <p class="text-sm text-surface-500">
                        Showing {{ $staffList->firstItem() }}–{{ $staffList->lastItem() }} of {{ $staffList->total() }} records
                    </p>
                    <div class="flex items-center gap-1">
                        @if($staffList->onFirstPage())
                            <span class="px-3 py-1.5 text-sm text-surface-300 rounded-btn cursor-not-allowed">← Prev</span>
                        @else
                            <a href="{{ $staffList->previousPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-surface-600 rounded-btn hover:bg-surface-100 transition-colors">← Prev</a>
                        @endif

                        @foreach($staffList->getUrlRange(max(1, $staffList->currentPage()-2), min($staffList->lastPage(), $staffList->currentPage()+2)) as $page => $url)
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-btn transition-colors
                                      {{ $page === $staffList->currentPage() ? 'bg-primary-600 text-white' : 'text-surface-600 hover:bg-surface-100' }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        @if($staffList->hasMorePages())
                            <a href="{{ $staffList->nextPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-surface-600 rounded-btn hover:bg-surface-100 transition-colors">Next →</a>
                        @else
                            <span class="px-3 py-1.5 text-sm text-surface-300 rounded-btn cursor-not-allowed">Next →</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

</x-layouts.dashboard>
