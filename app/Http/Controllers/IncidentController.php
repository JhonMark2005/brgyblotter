<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\BrevoMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidentController extends Controller
{
    private const PER_PAGE = 10;

    private function getIncidentWithRecordedBy(int $id): ?array
    {
        $incident = Incident::with('recordedBy')->find($id);
        if (!$incident) return null;

        $arr = $incident->toArray();
        $arr['recorded_by_name'] = $incident->recordedBy->full_name ?? 'N/A';
        return $arr;
    }

    private function applyFilters($query, array $filters)
    {
        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($search) {
                $q->where('complainant_name', 'like', $search)
                  ->orWhere('respondent_name', 'like', $search)
                  ->orWhere('incident_type', 'like', $search)
                  ->orWhere('location', 'like', $search);
            });
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['incident_type'])) {
            $query->where('incident_type', $filters['incident_type']);
        }
        if (!empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
        }
        return $query;
    }

    public function index(Request $request)
    {
        $filters = [
            'search'        => trim($request->get('search', '')),
            'status'        => $request->get('status', ''),
            'incident_type' => $request->get('incident_type', ''),
            'date_from'     => $request->get('date_from', ''),
            'date_to'       => $request->get('date_to', ''),
        ];

        $page  = max(1, (int) $request->get('page', 1));
        $query = $this->applyFilters(Incident::query(), $filters);
        $total = $query->count();
        $pages = (int) ceil($total / self::PER_PAGE);

        $incidents = $this->applyFilters(
            Incident::with('recordedBy')->orderByDesc('created_at'),
            $filters
        )->offset(($page - 1) * self::PER_PAGE)->limit(self::PER_PAGE)->get()
         ->map(fn($i) => array_merge($i->toArray(), ['recorded_by_name' => $i->recordedBy->full_name ?? 'N/A']))
         ->toArray();

        $types = IncidentType::orderBy('type_name')->pluck('type_name')->toArray();

        return view('incidents.index', compact('incidents', 'filters', 'page', 'pages', 'total', 'types'));
    }

    public function create()
    {
        $types = IncidentType::orderBy('type_name')->get()->toArray();
        return view('incidents.create', [
            'types' => $types,
            'old'   => session()->pull('old', []),
        ]);
    }

    public function store(Request $request)
    {
        $hearingDateRaw = trim($request->input('hearing_date', ''));
        $data = [
            'complainant_name'  => trim($request->input('complainant_name', '')),
            'respondent_name'   => trim($request->input('respondent_name', '')),
            'incident_type'     => trim($request->input('incident_type', '')),
            'date'              => $request->input('date', ''),
            'location'          => trim($request->input('location', '')),
            'description'       => trim($request->input('description', '')),
            'status'            => $request->input('status', 'pending'),
            'complainant_email' => trim($request->input('complainant_email', '')) ?: null,
            'respondent_email'  => trim($request->input('respondent_email', '')) ?: null,
            'hearing_date'      => $hearingDateRaw ?: null,
            'hearing_notes'     => trim($request->input('hearing_notes', '')) ?: null,
            'recorded_by'       => session('user.id'),
        ];

        $required = ['complainant_name', 'respondent_name', 'incident_type', 'date', 'location', 'description'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                session(['old' => $request->all()]);
                return redirect()->route('incidents.create')->with('error', 'All fields are required.');
            }
        }

        if (!array_key_exists($data['status'], Incident::STATUSES)) {
            $data['status'] = 'pending';
        }

        if (!empty($data['complainant_email']) && !filter_var($data['complainant_email'], FILTER_VALIDATE_EMAIL)) {
            session(['old' => $request->all()]);
            return redirect()->route('incidents.create')->with('error', 'Complainant email address is invalid.');
        }

        if (!empty($data['respondent_email']) && !filter_var($data['respondent_email'], FILTER_VALIDATE_EMAIL)) {
            session(['old' => $request->all()]);
            return redirect()->route('incidents.create')->with('error', 'Respondent email address is invalid.');
        }

        $incident = Incident::create($data);
        $incidentArr = $this->getIncidentWithRecordedBy($incident->id);

        if ($incidentArr) {
            $admins = User::where('role', 'admin')->whereNotNull('email')->where('email', '!=', '')->get()->toArray();
            BrevoMailer::notifyAdminNewCase($incidentArr, $admins);
            if (!empty($incidentArr['hearing_date'])) {
                BrevoMailer::notifyHearingSchedule($incidentArr);
            }
        }

        AuditLog::log('created', 'incident', $incident->id,
            "Created incident #{$incident->id} — {$data['incident_type']} filed by {$data['complainant_name']} against {$data['respondent_name']}."
        );

        return redirect()->route('incidents.index')->with('success', 'Incident record #' . $incident->id . ' has been created successfully.');
    }

    public function view(int $id)
    {
        $incident = $this->getIncidentWithRecordedBy($id);

        if (!$incident) {
            return redirect()->route('incidents.index')->with('error', 'Incident record not found.');
        }

        return view('incidents.view', compact('incident'));
    }

    public function downloadNotice(int $id, string $type)
    {
        $incident = $this->getIncidentWithRecordedBy($id);

        if (!$incident) {
            return redirect()->route('incidents.index')->with('error', 'Incident record not found.');
        }

        $allowed = ['new_case', 'hearing', 'status', 'status_respondent'];
        if (!in_array($type, $allowed)) {
            return redirect()->route('incidents.view', $id);
        }

        return view('incidents.notice', compact('incident', 'type'));
    }

    public function edit(int $id)
    {
        $incident = $this->getIncidentWithRecordedBy($id);

        if (!$incident) {
            return redirect()->route('incidents.index')->with('error', 'Incident record not found.');
        }

        $types = IncidentType::orderBy('type_name')->get()->toArray();

        return view('incidents.edit', compact('incident', 'types'));
    }

    public function update(Request $request, int $id)
    {
        $incident = $this->getIncidentWithRecordedBy($id);

        if (!$incident) {
            return redirect()->route('incidents.index')->with('error', 'Incident record not found.');
        }

        $oldStatus = $incident['status'];
        $hearingDateRaw = trim($request->input('hearing_date', ''));

        $data = [
            'complainant_name'  => trim($request->input('complainant_name', '')),
            'respondent_name'   => trim($request->input('respondent_name', '')),
            'incident_type'     => trim($request->input('incident_type', '')),
            'date'              => $request->input('date', ''),
            'location'          => trim($request->input('location', '')),
            'description'       => trim($request->input('description', '')),
            'status'            => $request->input('status', 'pending'),
            'complainant_email' => trim($request->input('complainant_email', '')) ?: null,
            'respondent_email'  => trim($request->input('respondent_email', '')) ?: null,
            'hearing_date'      => $hearingDateRaw ?: null,
            'hearing_notes'     => trim($request->input('hearing_notes', '')) ?: null,
        ];

        $required = ['complainant_name', 'respondent_name', 'incident_type', 'date', 'location', 'description'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return redirect()->route('incidents.edit', $id)->with('error', 'All fields are required.');
            }
        }

        if (!empty($data['complainant_email']) && !filter_var($data['complainant_email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('incidents.edit', $id)->with('error', 'Complainant email address is invalid.');
        }

        if (!empty($data['respondent_email']) && !filter_var($data['respondent_email'], FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('incidents.edit', $id)->with('error', 'Respondent email address is invalid.');
        }

        Incident::find($id)->update($data);

        $updated = $this->getIncidentWithRecordedBy($id);
        if ($updated) {
            if ($data['status'] !== $oldStatus) {
                BrevoMailer::notifyStatusUpdate($updated, $oldStatus);
            }
            $oldHearing = $incident['hearing_date'] ?? '';
            if (!empty($data['hearing_date']) && $data['hearing_date'] !== $oldHearing) {
                BrevoMailer::notifyHearingSchedule($updated);
            }
        }

        AuditLog::log('updated', 'incident', $id,
            "Updated incident #{$id} — status: {$data['status']}, type: {$data['incident_type']}."
        );

        return redirect()->route('incidents.edit', $id)->with('success', 'Incident record #' . $id . ' has been updated successfully.');
    }

    public function delete(Request $request, int $id)
    {
        $incident = $this->getIncidentWithRecordedBy($id);

        if (!$incident) {
            return redirect()->route('incidents.index')->with('error', 'Incident record not found.');
        }

        AuditLog::log('deleted', 'incident', $id,
            "Deleted incident #{$id} — {$incident['incident_type']} ({$incident['complainant_name']} vs {$incident['respondent_name']})."
        );

        Incident::destroy($id);

        return redirect()->route('incidents.index')->with('success', 'Incident record #' . $id . ' has been deleted.');
    }
}
