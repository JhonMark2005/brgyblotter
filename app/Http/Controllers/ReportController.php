<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private const PER_PAGE = 15;

    public function index()
    {
        $types = IncidentType::orderBy('type_name')->pluck('type_name')->toArray();
        return view('reports.index', [
            'types'     => $types,
            'generated' => false,
            'incidents' => [],
            'filters'   => [],
            'page'      => 1,
            'pages'     => 1,
            'total'     => 0,
            'isStaff'   => (session('user.role') ?? '') === 'staff',
        ]);
    }

    public function generate(Request $request)
    {
        $isStaff = (session('user.role') ?? '') === 'staff';
        $page    = max(1, (int) $request->input('page', 1));

        $filters = [
            'incident_type' => $request->input('incident_type', ''),
            'status'        => $request->input('status', ''),
            'date_from'     => $request->input('date_from', ''),
            'date_to'       => $request->input('date_to', ''),
        ];

        $query = Incident::query();

        if ($isStaff) {
            $query->where('recorded_by', session('user.id'));
        }
        if (!empty($filters['incident_type'])) {
            $query->where('incident_type', $filters['incident_type']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }

        $total  = $query->count();
        $pages  = (int) ceil($total / self::PER_PAGE);

        $incidents = (clone $query)
            ->with('recordedBy')
            ->orderBy('date', 'desc')
            ->offset(($page - 1) * self::PER_PAGE)
            ->limit(self::PER_PAGE)
            ->get()
            ->map(fn($i) => array_merge($i->toArray(), [
                'recorded_by_name' => $i->recordedBy->full_name ?? 'N/A',
                'date'             => $i->date ? $i->date->format('Y-m-d') : null,
                'hearing_date'     => $i->hearing_date ? $i->hearing_date->format('Y-m-d H:i:s') : null,
            ]))
            ->toArray();

        $types = IncidentType::orderBy('type_name')->pluck('type_name')->toArray();

        AuditLog::log('generated', 'report', null,
            'Generated incident report with filters: ' . json_encode(array_filter($filters))
        );

        return view('reports.index', compact('incidents', 'filters', 'page', 'pages', 'total', 'types', 'isStaff'))
            ->with('generated', true);
    }
}
