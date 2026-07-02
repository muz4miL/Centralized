<x-layouts.dashboard pageTitle="Overview" breadcrumb="Home / Dashboard">

    {{-- KPI Cards Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <x-kpi-card
            :value="$kpis['totalStudents']"
            label="Total Students"
            icon="users"
            color="primary"
        />
        <x-kpi-card
            :value="$kpis['avgAttendance']"
            label="Avg Attendance"
            :trend="$kpis['attendanceTrend']"
            icon="attendance"
            color="success"
            suffix="%"
        />
        <x-kpi-card
            :value="$kpis['feeCollectionPct']"
            label="Fee Collection Rate"
            :trend="$kpis['feeTrend']"
            icon="wallet"
            color="warning"
            suffix="%"
        />
        <x-kpi-card
            :value="$kpis['avgPassRate']"
            label="Avg Exam Pass Rate"
            icon="trophy"
            color="info"
            suffix="%"
        />
    </div>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- FEATURE 3: Needs Attention Alerts Widget --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div class="chart-card mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                {{-- Lucide: AlertTriangle --}}
                <svg class="w-5 h-5 text-warning" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                    <path d="M12 9v4"/><path d="M12 17h.01"/>
                </svg>
                <h3 class="chart-title mb-0">Needs Attention</h3>
            </div>
            @if($alerts['total'] > 5)
                <span class="text-xs text-surface-500 font-medium">
                    Showing 5 of {{ $alerts['total'] }} alerts
                </span>
            @endif
        </div>

        @if(empty($alerts['items']))
            {{-- Healthy empty state --}}
            <div class="flex items-center gap-3 py-3 px-4 bg-success-light rounded-btn">
                <svg class="w-5 h-5 text-success flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>
                </svg>
                <p class="text-sm font-medium text-success-dark">All campuses performing within healthy ranges</p>
            </div>
        @else
            <ul class="space-y-2">
                @foreach($alerts['items'] as $alert)
                    @php
                        $isCritical = $alert['type'] === 'critical';
                        $borderColor = $isCritical ? 'border-danger' : 'border-warning';
                        $bgColor     = $isCritical ? 'bg-danger-light' : 'bg-warning-light';
                        $textColor   = $isCritical ? 'text-danger-dark' : 'text-warning-dark';
                        $iconColor   = $isCritical ? 'text-danger' : 'text-warning';
                    @endphp
                    <li class="flex items-start gap-3 px-4 py-3 rounded-btn border-l-4 {{ $borderColor }} {{ $bgColor }} transition-all duration-200 hover:brightness-95">
                        {{-- Icon --}}
                        <div class="flex-shrink-0 mt-0.5 {{ $iconColor }}">
                            @if($alert['icon'] === 'attendance')
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                            @elseif($alert['icon'] === 'wallet')
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                            @else
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium {{ $textColor }}">{{ $alert['message'] }}</p>
                        </div>
                        {{-- Link to drill-down --}}
                        @if(!empty($alert['campus']))
                            <a href="{{ route('attendance', ['campus' => $alert['campus']]) }}"
                               class="flex-shrink-0 text-xs font-semibold {{ $textColor }} hover:underline whitespace-nowrap">
                                View →
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>

            @if($alerts['total'] > 5)
                <div class="mt-3 text-center">
                    <a href="{{ route('attendance') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                        View all {{ $alerts['total'] }} alerts →
                    </a>
                </div>
            @endif
        @endif
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">

        {{-- Attendance Trend Line Chart --}}
        <div class="chart-card">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="chart-title">Attendance Overview</h3>
                    <p class="chart-subtitle">Monthly attendance trend across campuses — last 6 months</p>
                </div>
                <div class="flex items-center gap-1 text-xs text-surface-400">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    <span>Trend</span>
                </div>
            </div>
            <div class="relative" style="height: 320px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        {{-- Fee Collection Bar Chart --}}
        <div class="chart-card">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="chart-title">Fee Collection by Campus</h3>
                    <p class="chart-subtitle">Collected vs. outstanding — current month</p>
                </div>
                <div class="flex items-center gap-1 text-xs text-surface-400">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                    <span>Finance</span>
                </div>
            </div>
            <div class="relative" style="height: 320px;">
                <canvas id="feeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- FEATURE 1: Campus Performance Comparison Table --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div class="chart-card mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="chart-title">Campus Performance Comparison</h3>
                <p class="chart-subtitle">Side-by-side metrics across all campuses — sorted by health score</p>
            </div>
        </div>

        @if($campusComparison->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
                <p>No campus data available</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-200">
                            <th class="text-left py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Campus</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Students</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Attendance %</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Fee Collection %</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Pass Rate %</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Health Score</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @foreach($campusComparison as $row)
                        <tr class="hover:bg-surface-50 transition-colors duration-150 cursor-pointer group"
                            onclick="window.location='{{ route('attendance', ['campus' => $row->campus]) }}'">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full flex-shrink-0
                                        {{ $row->status === 'excellent' ? 'bg-success' : ($row->status === 'attention' ? 'bg-warning' : 'bg-danger') }}">
                                    </div>
                                    <span class="font-semibold text-surface-800 group-hover:text-primary-600 transition-colors">{{ $row->campus }}</span>
                                    <svg class="w-3.5 h-3.5 text-surface-300 group-hover:text-primary-500 transition-colors ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center text-surface-600 font-medium">{{ number_format($row->total_students) }}</td>

                            {{-- Attendance % cell --}}
                            @php $attPct = $row->attendance_pct; @endphp
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold
                                    {{ $attPct >= 90 ? 'text-success-dark' : ($attPct < 75 ? 'text-danger-dark' : 'text-surface-700') }}">
                                    @if($attPct >= 90)
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                    @elseif($attPct < 75)
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    @endif
                                    {{ number_format($attPct, 1) }}%
                                </span>
                            </td>

                            {{-- Fee % cell --}}
                            @php $feePct = $row->fee_pct; @endphp
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold
                                    {{ $feePct >= 90 ? 'text-success-dark' : ($feePct < 75 ? 'text-danger-dark' : 'text-surface-700') }}">
                                    @if($feePct >= 90)
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                    @elseif($feePct < 75)
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    @endif
                                    {{ number_format($feePct, 1) }}%
                                </span>
                            </td>

                            {{-- Exam pass % cell --}}
                            @php $examPct = $row->exam_pass_pct; @endphp
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-semibold
                                    {{ $examPct >= 90 ? 'text-success-dark' : ($examPct < 75 ? 'text-danger-dark' : 'text-surface-700') }}">
                                    @if($examPct >= 90)
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                    @elseif($examPct < 75)
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    @endif
                                    {{ number_format($examPct, 1) }}%
                                </span>
                            </td>

                            {{-- Health score --}}
                            <td class="py-4 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-16 h-1.5 rounded-full bg-surface-200 overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500
                                            {{ $row->health_score >= 90 ? 'bg-success' : ($row->health_score >= 75 ? 'bg-warning' : 'bg-danger') }}"
                                             style="width: {{ min($row->health_score, 100) }}%">
                                        </div>
                                    </div>
                                    <span class="font-bold text-surface-800 text-xs">{{ number_format($row->health_score, 0) }}</span>
                                </div>
                            </td>

                            {{-- Status badge --}}
                            <td class="py-4 px-4 text-center">
                                @if($row->status === 'excellent')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-success-light text-success-dark">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                        Excellent
                                    </span>
                                @elseif($row->status === 'attention')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-warning-light text-warning-dark">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                        Needs Attention
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-danger-light text-danger-dark">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                        Critical
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-surface-400 mt-3 px-4">Click any campus row to view attendance drill-down for that campus.</p>
        @endif
    </div>

    {{-- Recent Exams Table --}}
    <div class="chart-card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="chart-title">Recent Exam Results</h3>
                <p class="chart-subtitle">Latest examination performance across campuses</p>
            </div>
        </div>

        @if($recentExams->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                <p>No exam data available yet</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-200">
                            <th class="text-left py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Exam</th>
                            <th class="text-left py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Campus</th>
                            <th class="text-left py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Class</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Students</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Pass Rate</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Avg Score</th>
                            <th class="text-center py-3 px-4 font-semibold text-surface-500 text-xs uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @foreach($recentExams as $exam)
                        <tr class="hover:bg-surface-50 transition-colors duration-150">
                            <td class="py-3 px-4 font-medium text-surface-800">{{ $exam->exam_name }}</td>
                            <td class="py-3 px-4 text-surface-600">{{ $exam->campus }}</td>
                            <td class="py-3 px-4 text-surface-600">{{ $exam->class_name }}</td>
                            <td class="py-3 px-4 text-center text-surface-600">{{ $exam->total_students }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $exam->pass_percentage >= 80 ? 'bg-success-light text-success-dark' :
                                       ($exam->pass_percentage >= 60 ? 'bg-warning-light text-warning-dark' : 'bg-danger-light text-danger-dark') }}">
                                    {{ number_format($exam->pass_percentage, 1) }}%
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center text-surface-600">{{ number_format($exam->average_score, 1) }}</td>
                            <td class="py-3 px-4 text-center text-surface-400 text-xs">{{ $exam->exam_date->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Chart.js Initialization --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const attendanceData = @json($attendanceTrend);
        if (attendanceData.labels && attendanceData.labels.length > 0) {
            new Chart(document.getElementById('attendanceChart'), {
                type: 'line',
                data: { labels: attendanceData.labels, datasets: attendanceData.datasets },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: {
                            beginAtZero: false, min: 70, max: 100,
                            ticks: { callback: v => v + '%', stepSize: 5 },
                        },
                    },
                },
            });
        }

        const feeData = @json($feesByCampus);
        if (feeData.labels && feeData.labels.length > 0) {
            new Chart(document.getElementById('feeChart'), {
                type: 'bar',
                data: {
                    labels: feeData.labels,
                    datasets: [
                        { label: 'Collected',    data: feeData.collected,   backgroundColor: '#6d3de6', borderRadius: 8, borderSkipped: false },
                        { label: 'Outstanding',  data: feeData.outstanding, backgroundColor: '#f59e0b', borderRadius: 8, borderSkipped: false },
                    ],
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => {
                                    if (v >= 1000000) return '₨' + (v/1000000).toFixed(1) + 'M';
                                    if (v >= 1000)    return '₨' + (v/1000).toFixed(0) + 'K';
                                    return '₨' + v;
                                },
                            },
                        },
                        x: { grid: { display: false } },
                    },
                },
            });
        }
    });
    </script>
    @endpush

</x-layouts.dashboard>
