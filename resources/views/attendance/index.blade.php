<x-layouts.dashboard
    pageTitle="Attendance"
    breadcrumb="Home / Attendance Drill-Down"
>

    {{-- Filters --}}
    <form method="GET" action="{{ route('attendance') }}" class="chart-card mb-6" x-data>
        <div class="flex flex-wrap items-end gap-4">
            {{-- Search --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">Search Class</label>
                <div class="flex items-center bg-surface-50 border border-surface-200 rounded-btn px-3 py-2.5 focus-within:ring-2 focus-within:ring-primary-500/30 focus-within:border-primary-400 transition-all">
                    <svg class="w-4 h-4 text-surface-400 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="e.g. Grade 8"
                           class="bg-transparent border-none text-sm text-surface-700 placeholder-surface-400 focus:ring-0 focus:outline-none w-full">
                </div>
            </div>

            {{-- Campus --}}
            <div class="min-w-[180px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">Campus</label>
                <select name="campus" class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400 transition-all">
                    <option value="">All Campuses</option>
                    @foreach($campuses as $c)
                        <option value="{{ $c }}" {{ $selectedCampus === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Class --}}
            <div class="min-w-[160px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">Class</label>
                <select name="class" class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400 transition-all">
                    <option value="">All Classes</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls }}" {{ $selectedClass === $cls ? 'selected' : '' }}>{{ $cls }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div class="min-w-[150px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">From</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                       class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400 transition-all">
            </div>

            {{-- Date To --}}
            <div class="min-w-[150px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">To</label>
                <input type="date" name="date_to" value="{{ $dateTo }}"
                       class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400 transition-all">
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-btn hover:bg-primary-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Filter
                </button>
                <a href="{{ route('attendance') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-surface-100 text-surface-600 text-sm font-medium rounded-btn hover:bg-surface-200 transition-all duration-200">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset
                </a>
            </div>
        </div>

        {{-- Active filter pill --}}
        @if($selectedCampus)
            <div class="mt-4 flex items-center gap-2">
                <span class="text-xs text-surface-500">Filtered by:</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-primary-100 text-primary-700 text-xs font-semibold rounded-full">
                    {{ $selectedCampus }}
                    <a href="{{ route('attendance', array_filter(['class' => $selectedClass, 'search' => $search])) }}" class="hover:text-primary-900">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </a>
                </span>
            </div>
        @endif
    </form>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="kpi-card !py-4 !px-5">
            <p class="text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1">Avg Attendance (Filtered)</p>
            <p class="text-2xl font-heading font-bold text-surface-900">{{ number_format($filteredAvg, 1) }}<span class="text-base font-medium text-surface-400">%</span></p>
        </div>
        <div class="kpi-card !py-4 !px-5">
            <p class="text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1">Best Performing Class</p>
            @if($bestClass)
                <p class="text-lg font-heading font-bold text-surface-900">{{ $bestClass->class_name }}</p>
                <p class="text-xs text-success-dark font-semibold mt-0.5">{{ number_format($bestClass->avg_pct, 1) }}% avg</p>
            @else
                <p class="text-sm text-surface-400">No data</p>
            @endif
        </div>
        <div class="kpi-card !py-4 !px-5">
            <p class="text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1">Needs Improvement</p>
            @if($worstClass)
                <p class="text-lg font-heading font-bold text-surface-900">{{ $worstClass->class_name }}</p>
                <p class="text-xs text-danger-dark font-semibold mt-0.5">{{ number_format($worstClass->avg_pct, 1) }}% avg</p>
            @else
                <p class="text-sm text-surface-400">No data</p>
            @endif
        </div>
    </div>

    {{-- Data Table --}}
    <div class="chart-card">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="chart-title">Attendance Records</h3>
                <p class="chart-subtitle">
                    {{ $records->total() }} records found
                    @if($selectedCampus) — {{ $selectedCampus }} @endif
                    @if($selectedClass) · {{ $selectedClass }} @endif
                </p>
            </div>
        </div>

        @if($records->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/></svg>
                <p class="text-base font-medium">No attendance records match your filters</p>
                <p class="text-sm mt-1"><a href="{{ route('attendance') }}" class="text-primary-600 hover:underline">Clear all filters</a></p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-200">
                            @php
                                $cols = [
                                    'class_name' => 'Class',
                                    'campus'     => 'Campus',
                                    'month'      => 'Month',
                                    'total_students' => 'Students',
                                    'total_present'  => 'Present',
                                    'total_absent'   => 'Absent',
                                    'attendance_percentage' => 'Attendance %',
                                ];
                            @endphp
                            @foreach($cols as $col => $label)
                                <th class="py-3 px-4 {{ in_array($col, ['total_students','total_present','total_absent','attendance_percentage']) ? 'text-center' : 'text-left' }} font-semibold text-surface-500 text-xs uppercase tracking-wider">
                                    <a href="{{ route('attendance', array_merge(request()->except(['sort','dir','page']), [
                                        'sort' => $col,
                                        'dir'  => ($sortBy === $col && $sortDir === 'asc') ? 'desc' : 'asc',
                                    ])) }}"
                                       class="inline-flex items-center gap-1 hover:text-primary-600 transition-colors">
                                        {{ $label }}
                                        @if($sortBy === $col)
                                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                @if($sortDir === 'asc')
                                                    <path d="m18 15-6-6-6 6"/>
                                                @else
                                                    <path d="m6 9 6 6 6-6"/>
                                                @endif
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 opacity-30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                        @endif
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @foreach($records as $rec)
                        <tr class="hover:bg-surface-50 transition-colors duration-150">
                            <td class="py-3 px-4 font-medium text-surface-800">{{ $rec->class_name }}</td>
                            <td class="py-3 px-4 text-surface-600">{{ $rec->campus }}</td>
                            <td class="py-3 px-4 text-surface-500 text-xs">{{ $rec->month->format('M Y') }}</td>
                            <td class="py-3 px-4 text-center text-surface-600">{{ number_format($rec->total_students) }}</td>
                            <td class="py-3 px-4 text-center text-success-dark font-medium">{{ number_format($rec->total_present) }}</td>
                            <td class="py-3 px-4 text-center text-danger-dark font-medium">{{ number_format($rec->total_absent) }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $rec->attendance_percentage >= 90 ? 'bg-success-light text-success-dark' :
                                       ($rec->attendance_percentage >= 75 ? 'bg-warning-light text-warning-dark' : 'bg-danger-light text-danger-dark') }}">
                                    {{ number_format($rec->attendance_percentage, 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($records->hasPages())
                <div class="mt-6 flex items-center justify-between">
                    <p class="text-sm text-surface-500">
                        Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }} records
                    </p>
                    <div class="flex items-center gap-1">
                        {{-- Prev --}}
                        @if($records->onFirstPage())
                            <span class="px-3 py-1.5 text-sm text-surface-300 rounded-btn cursor-not-allowed">← Prev</span>
                        @else
                            <a href="{{ $records->previousPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-surface-600 rounded-btn hover:bg-surface-100 transition-colors">← Prev</a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($records->getUrlRange(max(1, $records->currentPage()-2), min($records->lastPage(), $records->currentPage()+2)) as $page => $url)
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-btn transition-colors
                                      {{ $page === $records->currentPage() ? 'bg-primary-600 text-white' : 'text-surface-600 hover:bg-surface-100' }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        {{-- Next --}}
                        @if($records->hasMorePages())
                            <a href="{{ $records->nextPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-surface-600 rounded-btn hover:bg-surface-100 transition-colors">Next →</a>
                        @else
                            <span class="px-3 py-1.5 text-sm text-surface-300 rounded-btn cursor-not-allowed">Next →</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

</x-layouts.dashboard>
