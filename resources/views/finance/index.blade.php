<x-layouts.dashboard
    pageTitle="Finance"
    breadcrumb="Home / Finance Drill-Down"
>

    {{-- Filters --}}
    <form method="GET" action="{{ route('finance') }}" class="chart-card mb-6">
        <div class="flex flex-wrap items-end gap-4">
            {{-- Campus --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">Campus</label>
                <select name="campus" class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400 transition-all">
                    <option value="">All Campuses</option>
                    @foreach($campuses as $c)
                        <option value="{{ $c }}" {{ $selectedCampus === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div class="min-w-[180px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">From Month</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}"
                       class="w-full bg-surface-50 border border-surface-200 text-sm text-surface-700 rounded-btn px-3 py-2.5 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-400 transition-all">
            </div>

            {{-- Date To --}}
            <div class="min-w-[180px]">
                <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider mb-1.5">To Month</label>
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
                <a href="{{ route('finance') }}"
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
                <div class="kpi-icon bg-primary-100 text-primary-600">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="kpi-value">₨{{ number_format($totalExpected) }}</div>
            <div class="kpi-label">Total Expected</div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div class="kpi-icon bg-success-light text-success-dark">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="kpi-value text-success-dark">₨{{ number_format($totalCollected) }}</div>
            <div class="kpi-label">Total Collected</div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div class="kpi-icon bg-danger-light text-danger-dark">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="kpi-value text-danger-dark">₨{{ number_format($totalOutstanding) }}</div>
            <div class="kpi-label">Outstanding Balance</div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div class="kpi-icon bg-warning-light text-warning-dark">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
            </div>
            <div class="kpi-value text-warning-dark">{{ number_format($avgCollectionPct, 1) }}%</div>
            <div class="kpi-label">Recovery Rate (Avg)</div>
        </div>
    </div>

    {{-- Monthly Collection Chart --}}
    <div class="chart-card mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="chart-title">Collection Target vs Collection Recovery</h3>
                <p class="chart-subtitle">Expected invoices vs actual collections by month</p>
            </div>
        </div>
        <div class="relative" style="height: 320px;">
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    {{-- Detailed Fee Records Table --}}
    <div class="chart-card">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="chart-title">Financial Records</h3>
                <p class="chart-subtitle">{{ $records->total() }} records found</p>
            </div>
        </div>

        @if($records->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                <p class="text-base font-medium">No financial records match your filters</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-200">
                            @php
                                $cols = [
                                    'month' => 'Month',
                                    'campus' => 'Campus',
                                    'total_expected' => 'Expected Fee',
                                    'total_collected' => 'Collected Fee',
                                    'total_outstanding' => 'Outstanding',
                                    'collection_percentage' => 'Collection %',
                                ];
                            @endphp
                            @foreach($cols as $col => $label)
                                <th class="py-3 px-4 {{ in_array($col, ['total_expected','total_collected','total_outstanding','collection_percentage']) ? 'text-center' : 'text-left' }} font-semibold text-surface-500 text-xs uppercase tracking-wider">
                                    <a href="{{ route('finance', array_merge(request()->except(['sort','dir','page']), [
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
                            <td class="py-3 px-4 text-surface-500 font-medium text-xs">{{ $rec->month->format('M Y') }}</td>
                            <td class="py-3 px-4 text-surface-800 font-semibold">{{ $rec->campus }}</td>
                            <td class="py-3 px-4 text-center text-surface-600 font-medium">₨{{ number_format($rec->total_expected) }}</td>
                            <td class="py-3 px-4 text-center text-success-dark font-medium">₨{{ number_format($rec->total_collected) }}</td>
                            <td class="py-3 px-4 text-center text-danger-dark font-medium">₨{{ number_format($rec->total_outstanding) }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $rec->collection_percentage >= 90 ? 'bg-success-light text-success-dark' :
                                       ($rec->collection_percentage >= 75 ? 'bg-warning-light text-warning-dark' : 'bg-danger-light text-danger-dark') }}">
                                    {{ number_format($rec->collection_percentage, 1) }}%
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
            const labels = chartData.map(d => d.month);
            const expected = chartData.map(d => d.expected);
            const collected = chartData.map(d => d.collected);

            new Chart(document.getElementById('financeChart'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Expected Collection',
                            data: expected,
                            backgroundColor: 'rgba(99, 102, 241, 0.4)',
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            borderRadius: 6,
                        },
                        {
                            label: 'Actual Collected',
                            data: collected,
                            backgroundColor: '#6d3de6',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => {
                                    if (v >= 1000000) return '₨' + (v / 1000000).toFixed(1) + 'M';
                                    if (v >= 1000) return '₨' + (v / 1000).toFixed(0) + 'K';
                                    return '₨' + v;
                                }
                            }
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
