<?php

namespace App\Http\Controllers;

use App\Models\FeeSummary;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinanceController extends Controller
{
    /**
     * Display the finance dashboard page with filters, KPIs, charts, and detailed data.
     */
    public function index(Request $request)
    {
        // Available filter options
        $campuses = FeeSummary::select('campus')->distinct()->orderBy('campus')->pluck('campus');

        // Active filters
        $selectedCampus = $request->input('campus');
        $dateFrom       = $request->input('date_from');
        $dateTo         = $request->input('date_to');
        $sortBy         = $request->input('sort', 'month');
        $sortDir        = $request->input('dir', 'desc');

        $allowed = ['month', 'campus', 'total_expected', 'total_collected', 'total_outstanding', 'collection_percentage'];
        if (! in_array($sortBy, $allowed)) {
            $sortBy = 'month';
        }

        $query = FeeSummary::query();

        if ($selectedCampus) {
            $query->where('campus', $selectedCampus);
        }
        if ($dateFrom) {
            $query->where('month', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('month', '<=', $dateTo);
        }

        $records = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();

        // Calculate summary stats
        $totalExpected    = $query->sum('total_expected');
        $totalCollected   = $query->sum('total_collected');
        $totalOutstanding = $query->sum('total_outstanding');
        $avgCollectionPct = $totalExpected > 0 ? ($totalCollected / $totalExpected) * 100 : 0;

        // Chart data: Monthly collection trend (aggregated by month)
        $chartData = FeeSummary::when($selectedCampus, fn ($q) => $q->where('campus', $selectedCampus))
            ->selectRaw('month, SUM(total_expected) as expected, SUM(total_collected) as collected')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn ($r) => [
                'month' => Carbon::parse($r->month)->format('M Y'),
                'expected' => (float) $r->expected,
                'collected' => (float) $r->collected,
            ]);

        return view('finance.index', compact(
            'records', 'campuses', 'selectedCampus', 'dateFrom', 'dateTo',
            'sortBy', 'sortDir', 'totalExpected', 'totalCollected', 'totalOutstanding',
            'avgCollectionPct', 'chartData'
        ));
    }
}
