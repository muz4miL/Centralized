<?php

namespace App\Services;

use App\Models\AttendanceSummary;
use App\Models\ExamSummary;
use App\Models\FeeSummary;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    // --- Alert Thresholds (tune here, not in views) ---
    const ATTENDANCE_WARNING_THRESHOLD = 75;
    const FEE_WARNING_THRESHOLD        = 70;
    const EXAM_WARNING_THRESHOLD       = 60;
    const SCORE_EXCELLENT              = 90;
    const SCORE_CRITICAL               = 75;

    /**
     * Get the full dashboard payload for the controller to pass to the view.
     */
    public function getDashboardData(): array
    {
        return [
            'kpis'              => $this->getKpis(),
            'alerts'            => $this->getAlerts(),
            'attendanceTrend'   => $this->getAttendanceTrend(),
            'feesByCampus'      => $this->getFeeCollectionByCampus(),
            'recentExams'       => $this->getRecentExamResults(),
            'campusComparison'  => $this->getCampusComparison(),
        ];
    }

    /**
     * Aggregate KPI values: total students, avg attendance, fee collection %, avg pass rate.
     */
    public function getKpis(): array
    {
        $latestMonth = AttendanceSummary::max('month');

        $totalStudents = AttendanceSummary::where('month', $latestMonth)
            ->sum('total_students');

        $avgAttendance = AttendanceSummary::where('month', $latestMonth)
            ->avg('attendance_percentage') ?? 0;

        $latestFeeMonth = FeeSummary::max('month');
        $feeData        = FeeSummary::where('month', $latestFeeMonth)->get();
        $totalExpected  = $feeData->sum('total_expected');
        $totalCollected = $feeData->sum('total_collected');
        $feeCollectionPct = $totalExpected > 0
            ? round(($totalCollected / $totalExpected) * 100, 1)
            : 0;

        $avgPassRate = ExamSummary::avg('pass_percentage') ?? 0;

        $previousMonth   = $latestMonth
            ? Carbon::parse($latestMonth)->subMonth()->format('Y-m-01')
            : null;

        $prevAttendance = $previousMonth
            ? (AttendanceSummary::where('month', $previousMonth)->avg('attendance_percentage') ?? 0)
            : 0;

        $prevFeeData  = $previousMonth ? FeeSummary::where('month', $previousMonth)->get() : collect();
        $prevExpected = $prevFeeData->sum('total_expected');
        $prevCollected = $prevFeeData->sum('total_collected');
        $prevFeePct   = $prevExpected > 0
            ? round(($prevCollected / $prevExpected) * 100, 1)
            : 0;

        return [
            'totalStudents'   => (int) $totalStudents,
            'avgAttendance'   => round($avgAttendance, 1),
            'attendanceTrend' => round($avgAttendance - $prevAttendance, 1),
            'feeCollectionPct' => $feeCollectionPct,
            'feeTrend'        => round($feeCollectionPct - $prevFeePct, 1),
            'avgPassRate'     => round($avgPassRate, 1),
            'totalCollected'  => $totalCollected,
            'totalOutstanding' => round($totalExpected - $totalCollected, 2),
        ];
    }

    /**
     * Generate smart alert items from summary data against defined thresholds.
     * Returns up to 5 prioritised alerts plus a total count.
     */
    public function getAlerts(): array
    {
        $alerts     = [];
        $latestMonth = AttendanceSummary::max('month');
        $latestFee   = FeeSummary::max('month');

        // Attendance alerts
        $attendanceByCampus = AttendanceSummary::where('month', $latestMonth)
            ->selectRaw('campus, AVG(attendance_percentage) as avg_pct')
            ->groupBy('campus')
            ->get();

        foreach ($attendanceByCampus as $row) {
            if ($row->avg_pct < self::ATTENDANCE_WARNING_THRESHOLD) {
                $alerts[] = [
                    'type'     => $row->avg_pct < 50 ? 'critical' : 'warning',
                    'message'  => "{$row->campus} attendance dropped to " . number_format($row->avg_pct, 1) . "% this month",
                    'icon'     => 'attendance',
                    'campus'   => $row->campus,
                ];
            }
        }

        // Fee alerts
        $feeByCampus = FeeSummary::where('month', $latestFee)->get();
        foreach ($feeByCampus as $row) {
            if ($row->collection_percentage < self::FEE_WARNING_THRESHOLD) {
                $alerts[] = [
                    'type'    => $row->collection_percentage < 50 ? 'critical' : 'warning',
                    'message' => "{$row->campus} fee collection is behind target at " . number_format($row->collection_percentage, 1) . "%",
                    'icon'    => 'wallet',
                    'campus'  => $row->campus,
                ];
            }
        }

        // Exam alerts
        $examAlerts = ExamSummary::selectRaw('campus, class_name, AVG(pass_percentage) as avg_pass')
            ->groupBy('campus', 'class_name')
            ->get();

        foreach ($examAlerts as $row) {
            if ($row->avg_pass < self::EXAM_WARNING_THRESHOLD) {
                $alerts[] = [
                    'type'    => $row->avg_pass < 50 ? 'critical' : 'warning',
                    'message' => "{$row->class_name} at {$row->campus} has a low pass rate of " . number_format($row->avg_pass, 1) . "%",
                    'icon'    => 'exam',
                    'campus'  => $row->campus,
                ];
            }
        }

        // Sort: critical first, then warning
        usort($alerts, fn ($a, $b) => $a['type'] === 'critical' ? -1 : 1);

        return [
            'items' => array_slice($alerts, 0, 5),
            'total' => count($alerts),
        ];
    }

    /**
     * Campus comparison: aggregate all 3 metrics per campus sorted by composite health score.
     */
    public function getCampusComparison(): Collection
    {
        $latestAttMonth = AttendanceSummary::max('month');
        $latestFeeMonth = FeeSummary::max('month');

        // Attendance avg per campus
        $attendance = AttendanceSummary::where('month', $latestAttMonth)
            ->selectRaw('campus, SUM(total_students) as total_students, AVG(attendance_percentage) as avg_attendance')
            ->groupBy('campus')
            ->get()
            ->keyBy('campus');

        // Fee collection per campus
        $fees = FeeSummary::where('month', $latestFeeMonth)
            ->get()
            ->keyBy('campus');

        // Exam pass rate per campus
        $exams = ExamSummary::selectRaw('campus, AVG(pass_percentage) as avg_pass')
            ->groupBy('campus')
            ->get()
            ->keyBy('campus');

        $campuses = $attendance->keys()
            ->union($fees->keys())
            ->union($exams->keys())
            ->unique();

        $rows = $campuses->map(function ($campus) use ($attendance, $fees, $exams) {
            $att     = $attendance->get($campus);
            $fee     = $fees->get($campus);
            $exam    = $exams->get($campus);

            $attPct  = $att  ? round($att->avg_attendance, 1)     : 0;
            $feePct  = $fee  ? round($fee->collection_percentage, 1) : 0;
            $examPct = $exam ? round($exam->avg_pass, 1)           : 0;

            $health  = round(($attPct + $feePct + $examPct) / 3, 1);

            $status  = match (true) {
                $health >= self::SCORE_EXCELLENT => 'excellent',
                $health >= self::SCORE_CRITICAL  => 'attention',
                default                          => 'critical',
            };

            return (object) [
                'campus'          => $campus,
                'total_students'  => $att ? (int) $att->total_students : 0,
                'attendance_pct'  => $attPct,
                'fee_pct'         => $feePct,
                'exam_pass_pct'   => $examPct,
                'health_score'    => $health,
                'status'          => $status,
            ];
        });

        return $rows->sortByDesc('health_score')->values();
    }

    /**
     * Get monthly attendance trend data for chart rendering.
     */
    public function getAttendanceTrend(): array
    {
        $data = AttendanceSummary::selectRaw('campus, month, AVG(attendance_percentage) as avg_pct')
            ->groupBy('campus', 'month')
            ->orderBy('month')
            ->get();

        if ($data->isEmpty()) {
            return ['labels' => [], 'datasets' => []];
        }

        $grouped = $data->groupBy('campus');
        $labels  = $data->pluck('month')
            ->unique()->sort()->values()
            ->map(fn ($m) => Carbon::parse($m)->format('M Y'))
            ->toArray();

        $colors = [
            'Main Campus'  => ['line' => '#6366f1', 'bg' => 'rgba(99,102,241,0.15)'],
            'North Campus' => ['line' => '#06b6d4', 'bg' => 'rgba(6,182,212,0.15)'],
            'South Campus' => ['line' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.15)'],
        ];

        $datasets = [];
        foreach ($grouped as $campus => $records) {
            $color      = $colors[$campus] ?? ['line' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.15)'];
            $datasets[] = [
                'label'           => $campus,
                'data'            => $records->sortBy('month')->pluck('avg_pct')->map(fn ($v) => round($v, 1))->values()->toArray(),
                'borderColor'     => $color['line'],
                'backgroundColor' => $color['bg'],
                'fill'            => true,
                'tension'         => 0.4,
                'pointRadius'     => 4,
                'pointHoverRadius' => 6,
            ];
        }

        return ['labels' => $labels, 'datasets' => $datasets];
    }

    /**
     * Get fee collection data grouped by campus for bar chart.
     */
    public function getFeeCollectionByCampus(): array
    {
        $latestMonth = FeeSummary::max('month');

        $data = FeeSummary::where('month', $latestMonth)
            ->get()
            ->map(fn ($f) => [
                'campus'      => $f->campus,
                'collected'   => round($f->total_collected, 0),
                'outstanding' => round($f->total_outstanding, 0),
            ]);

        if ($data->isEmpty()) {
            return ['labels' => [], 'collected' => [], 'outstanding' => []];
        }

        return [
            'labels'      => $data->pluck('campus')->toArray(),
            'collected'   => $data->pluck('collected')->toArray(),
            'outstanding' => $data->pluck('outstanding')->toArray(),
        ];
    }

    /**
     * Get the most recent exam results for display.
     */
    public function getRecentExamResults(): Collection
    {
        return ExamSummary::orderByDesc('exam_date')->limit(10)->get();
    }
}
