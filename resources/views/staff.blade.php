<x-layouts.dashboard pageTitle="Staff Management" breadcrumb="Home / Staff">

    <div class="chart-card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="chart-title">Staff Directory</h3>
                <p class="chart-subtitle">Manage school staff members and their roles</p>
            </div>
            <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-btn
                           hover:bg-primary-700 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Add Staff Member
            </button>
        </div>

        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <p class="text-base font-medium text-surface-500">Staff management coming soon</p>
            <p class="text-sm text-surface-400 mt-1">This section will allow you to manage staff records and assignments.</p>
        </div>
    </div>

</x-layouts.dashboard>
