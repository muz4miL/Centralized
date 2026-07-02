<?php

namespace App\Http\Controllers;

use App\Models\ExamSummary;
use Illuminate\Http\Request;

class AcademicsController extends Controller
{
    /**
     * Display the academics drill-down page with filters and stats.
     */
    public function index(Request $request)
    {
        // Available filter options
        $campuses = ExamSummary::select('campus')->distinct()->orderBy('campus')->pluck('campus');
        $classes  = ExamSummary::select('class_name')->distinct()->orderBy('class_name')->pluck('class_name');

        // Active filters
        $selectedCampus = $request->input('campus');
        $selectedClass  = $request->input('class');
        $dateFrom       = $request->input('date_from');
        $dateTo         = $request->input('date_to');
        $search         = $request->input('search');
        $sortBy         = $request->input('sort', 'exam_date');
        $sortDir        = $request->input('dir', 'desc');

        $allowed = ['exam_name', 'campus', 'class_name', 'total_students', 'pass_percentage', 'average_score', 'exam_date'];
        if (! in_array($sortBy, $allowed)) {
            $sortBy = 'exam_date';
        }

        $query = ExamSummary::query();

        if ($selectedCampus) {
            $query->where('campus', $selectedCampus);
        }
        if ($selectedClass) {
            $query->where('class_name', $selectedClass);
        }
        if ($dateFrom) {
            $query->where('exam_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('exam_date', '<=', $dateTo);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('exam_name', 'like', "%{$search}%")
                  ->orWhere('class_name', 'like', "%{$search}%");
            });
        }

        $records = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();

        // Calculate summary stats for the filtered view
        $filteredAvgPass = $query->avg('pass_percentage') ?? 0;
        $filteredAvgScore = $query->avg('average_score') ?? 0;

        $bestClass = ExamSummary::when($selectedCampus, fn ($q) => $q->where('campus', $selectedCampus))
            ->selectRaw('class_name, AVG(pass_percentage) as avg_pass')
            ->groupBy('class_name')
            ->orderByDesc('avg_pass')
            ->first();

        $worstClass = ExamSummary::when($selectedCampus, fn ($q) => $q->where('campus', $selectedCampus))
            ->selectRaw('class_name, AVG(pass_percentage) as avg_pass')
            ->groupBy('class_name')
            ->orderBy('avg_pass')
            ->first();

        // Chart data: pass rate by class (aggregate average pass rate per class name in selected context)
        $chartData = ExamSummary::when($selectedCampus, fn ($q) => $q->where('campus', $selectedCampus))
            ->selectRaw('class_name, AVG(pass_percentage) as avg_pass')
            ->groupBy('class_name')
            ->orderBy('class_name')
            ->get();

        return view('academics.index', compact(
            'records', 'campuses', 'classes',
            'selectedCampus', 'selectedClass', 'dateFrom', 'dateTo', 'search',
            'sortBy', 'sortDir',
            'filteredAvgPass', 'filteredAvgScore', 'bestClass', 'worstClass', 'chartData'
        ));
    }
}
