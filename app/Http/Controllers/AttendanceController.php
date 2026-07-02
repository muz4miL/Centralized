<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSummary;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display the attendance drill-down page with filters and paginated data.
     */
    public function index(Request $request)
    {
        // Available filter options
        $campuses = AttendanceSummary::select('campus')->distinct()->orderBy('campus')->pluck('campus');
        $classes  = AttendanceSummary::select('class_name')->distinct()->orderBy('class_name')->pluck('class_name');

        // Active filter values (with campus pre-filter from query param)
        $selectedCampus = $request->input('campus');
        $selectedClass  = $request->input('class');
        $dateFrom       = $request->input('date_from');
        $dateTo         = $request->input('date_to');
        $search         = $request->input('search');
        $sortBy         = $request->input('sort', 'month');
        $sortDir        = $request->input('dir', 'desc');

        $allowed = ['campus', 'class_name', 'month', 'total_students', 'total_present', 'total_absent', 'attendance_percentage'];
        if (! in_array($sortBy, $allowed)) {
            $sortBy = 'month';
        }

        $query = AttendanceSummary::query();

        if ($selectedCampus) {
            $query->where('campus', $selectedCampus);
        }
        if ($selectedClass) {
            $query->where('class_name', $selectedClass);
        }
        if ($dateFrom) {
            $query->where('month', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('month', '<=', $dateTo);
        }
        if ($search) {
            $query->where('class_name', 'like', "%{$search}%");
        }

        $records = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();

        // Summary stats for the filtered view
        $filteredAvg  = $query->avg('attendance_percentage') ?? 0;
        $bestClass    = AttendanceSummary::when($selectedCampus, fn ($q) => $q->where('campus', $selectedCampus))
            ->selectRaw('class_name, AVG(attendance_percentage) as avg_pct')
            ->groupBy('class_name')
            ->orderByDesc('avg_pct')
            ->first();
        $worstClass   = AttendanceSummary::when($selectedCampus, fn ($q) => $q->where('campus', $selectedCampus))
            ->selectRaw('class_name, AVG(attendance_percentage) as avg_pct')
            ->groupBy('class_name')
            ->orderBy('avg_pct')
            ->first();

        return view('attendance.index', compact(
            'records', 'campuses', 'classes',
            'selectedCampus', 'selectedClass', 'dateFrom', 'dateTo', 'search',
            'sortBy', 'sortDir',
            'filteredAvg', 'bestClass', 'worstClass'
        ));
    }
}
