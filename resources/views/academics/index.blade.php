<x-layouts.dashboard
    pageTitle="Academics"
    breadcrumb="Home / Academics Drill-Down"
>

    {{-- Filters --}}
    <form method="GET" action="{{ route('academics') }}" class="chart-card mb-6">
        <div class="flex flex-wrap items-end gap-4">
            {{-- Search --}}
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">Search Exam / Class</label>
                <div class="flex items-center bg-surface-50 border border-surface-200 rounded-btn px-3 py-2.5 focus-within:ring-2 focus-within:ring-primary-500/30 focus-within:border-primary-400 transition-all">
                    <svg class="w-4 h-4 text-surface-400 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="e.g. Mid-Term"
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
                <a href="{{ route('academics') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-surface-100 text-surface-600 text-sm font-medium rounded-btn hover:bg-surface-200 transition-all duration-200">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- Summary Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div class="kpi-icon bg-info-light text-info-dark">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                </div>
            </div>
            <div class="kpi-value">{{ number_format($filteredAvgPass, 1) }}%</div>
            <div class="kpi-label">Avg Pass Rate (Filtered)</div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div class="kpi-icon bg-primary-100 text-primary-600">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
            </div>
            <div class="kpi-value">{{ number_format($filteredAvgScore, 1) }}</div>
            <div class="kpi-label">Avg Class Score</div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div class="kpi-icon bg-success-light text-success-dark">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                </div>
            </div>
            <div class="kpi-value text-xl font-heading truncate mt-3">{{ $bestClass?->class_name ?? 'N/A' }}</div>
            <div class="kpi-label">Best Performing Class ({{ number_format($bestClass?->avg_pass ?? 0, 1) }}%)</div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div class="kpi-icon bg-danger-light text-danger-dark">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>
            <div class="kpi-value text-xl font-heading truncate mt-3">{{ $worstClass?->class_name ?? 'N/A' }}</div>
            <div class="kpi-label">Needs Improvement ({{ number_format($worstClass?->avg_pass ?? 0, 1) }}%)</div>
        </div>
    </div>

    {{-- Pass Rate Comparison Chart --}}
    <div class="chart-card mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="chart-title">Academic Performance by Class</h3>
                <p class="chart-subtitle">Average pass rates across school levels</p>
            </div>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="academicsChart"></canvas>
        </div>
    </div>

    {{-- Detailed Exam Results Table --}}
    <div class="chart-card">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="chart-title">Exam Records</h3>
                <p class="chart-subtitle">{{ $records->total() }} records found</p>
            </div>
        </div>

        @if($records->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <p class="text-base font-medium">No exam records match your filters</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-200">
                            @php
                                $cols = [
                                    'exam_name' => 'Exam',
                                    'campus' => 'Campus',
                                    'class_name' => 'Class',
                                    'total_students' => 'Students',
                                    'pass_percentage' => 'Pass %',
                                    'average_score' => 'Avg Score',
                                    'exam_date' => 'Date',
                                ];
                            @endphp
                            @foreach($cols as $col => $label)
                                <th class="py-3 px-4 {{ in_array($col, ['total_students','pass_percentage','average_score','exam_date']) ? 'text-center' : 'text-left' }} font-semibold text-surface-500 text-xs uppercase tracking-wider">
                                    <a href="{{ route('academics', array_merge(request()->except(['sort','dir','page']), [
                                        'sort' => $col,
                                        'dir'  => ($sortBy === $col && $sortDir === 'asc') ? 'desc' : 'asc',
                                    ])) }}" class="inline-flex items-center gap-1 hover:text-primary-600 transition-colors">
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
                            <td class="py-3 px-4 font-medium text-surface-800">{{ $rec->exam_name }}</td>
                            <td class="py-3 px-4 text-surface-600">{{ $rec->campus }}</td>
                            <td class="py-3 px-4 text-surface-600">{{ $rec->class_name }}</td>
                            <td class="py-3 px-4 text-center text-surface-600">{{ number_format($rec->total_students) }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $rec->pass_percentage >= 80 ? 'bg-success-light text-success-dark' :
                                       ($rec->pass_percentage >= 60 ? 'bg-warning-light text-warning-dark' : 'bg-danger-light text-danger-dark') }}">
                                    {{ number_format($rec->pass_percentage, 1) }}%
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center text-surface-600 font-semibold">{{ number_format($rec->average_score, 1) }}</td>
                            <td class="py-3 px-4 text-center text-surface-400 text-xs">{{ $rec->exam_date->format('M d, Y') }}</td>
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
                        @if($records->onFirstPage())
                            <span class="px-3 py-1.5 text-sm text-surface-300 rounded-btn cursor-not-allowed">← Prev</span>
                        @else
                            <a href="{{ $records->previousPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-surface-600 rounded-btn hover:bg-surface-100 transition-colors">← Prev</a>
                        @endif

                        @foreach($records->getUrlRange(max(1, $records->currentPage()-2), min($records->lastPage(), $records->currentPage()+2)) as $page => $url)
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-btn transition-colors
                                      {{ $page === $records->currentPage() ? 'bg-primary-600 text-white' : 'text-surface-600 hover:bg-surface-100' }}">
                                {{ $page }}
                            </a>
                        @endforeach

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

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($chartData);
        if (chartData && chartData.length > 0) {
            const labels = chartData.map(d => d.class_name);
            const values = chartData.map(d => parseFloat(d.avg_pass).toFixed(1));

            new Chart(document.getElementById('academicsChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average Pass Rate',
                        data: values,
                        backgroundColor: 'rgba(99, 102, 241, 0.85)',
                        borderColor: '#6366f1',
                        borderWidth: 1,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: { callback: v => v + '%' }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
    </script>
    @endpush

</x-layouts.dashboard>
