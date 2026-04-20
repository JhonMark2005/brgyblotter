<?php

namespace App\Http\Controllers;

use App\Models\IncidentType;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class IncidentTypeController extends Controller
{
    public function index()
    {
        $types = IncidentType::orderBy('type_name')->get()->toArray();
        return view('incident_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $typeName = trim($request->input('type_name', ''));

        if (empty($typeName)) {
            return redirect()->route('incident-types.index')->with('error', 'Incident type name is required.');
        }

        if (IncidentType::where('type_name', $typeName)->exists()) {
            return redirect()->route('incident-types.index')->with('error', 'This incident type already exists.');
        }

        $type = IncidentType::create(['type_name' => $typeName]);

        AuditLog::log('created', 'incident_type', $type->id,
            "Added incident type: \"{$typeName}\"."
        );

        return redirect()->route('incident-types.index')->with('success', "Incident type \"{$typeName}\" added successfully.");
    }

    public function destroy(int $id)
    {
        $type = IncidentType::find($id);

        if (!$type) {
            return redirect()->route('incident-types.index')->with('error', 'Incident type not found.');
        }

        $typeName = $type->type_name;
        $type->delete();

        AuditLog::log('deleted', 'incident_type', $id,
            "Deleted incident type: \"{$typeName}\"."
        );

        return redirect()->route('incident-types.index')->with('success', "Incident type \"{$typeName}\" deleted.");
    }
}
