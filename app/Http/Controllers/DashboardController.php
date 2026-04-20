<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncidents = Incident::count();

        $thisMonthCount = Incident::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count();

        $countByStatus = Incident::select('status', DB::raw('COUNT(*) as total'))
                                  ->groupBy('status')
                                  ->pluck('total', 'status')
                                  ->toArray();

        $recentIncidents = Incident::with('recordedBy')
                                    ->orderByDesc('created_at')
                                    ->limit(5)
                                    ->get()
                                    ->map(fn($i) => array_merge($i->toArray(), [
                                        'recorded_by_name' => $i->recordedBy->full_name ?? 'N/A',
                                    ]))
                                    ->toArray();

        $countByType = Incident::select('incident_type', DB::raw('COUNT(*) as total'))
                                ->groupBy('incident_type')
                                ->orderByDesc('total')
                                ->limit(8)
                                ->pluck('total', 'incident_type')
                                ->toArray();

        $countPerMonth = $this->countPerMonth(6);

        $pendingCount       = $countByStatus['pending']             ?? 0;
        $resolvedCount      = $countByStatus['resolved']            ?? 0;
        $investigationCount = $countByStatus['under_investigation'] ?? 0;
        $dismissedCount     = $countByStatus['dismissed']           ?? 0;

        return view('dashboard.index', compact(
            'totalIncidents',
            'thisMonthCount',
            'pendingCount',
            'resolvedCount',
            'investigationCount',
            'dismissedCount',
            'recentIncidents',
            'countByType',
            'countPerMonth'
        ));
    }

    private function countPerMonth(int $months = 6): array
    {
        $rows = Incident::select(
                    DB::raw("DATE_FORMAT(date, '%Y-%m') AS month"),
                    DB::raw('COUNT(*) AS total')
                )
                ->where('date', '>=', DB::raw("DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL {$months} MONTH), '%Y-%m-01')"))
                ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m')"))
                ->orderBy('month')
                ->get()
                ->pluck('total', 'month')
                ->toArray();

        $result = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $key = date('Y-m', strtotime("-{$i} months"));
            $result[$key] = $rows[$key] ?? 0;
        }
        return $result;
    }
}
