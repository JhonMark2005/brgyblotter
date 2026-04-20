<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    private const PER_PAGE = 20;

    public function index(Request $request)
    {
        $filters = [
            'action'      => $request->get('action', ''),
            'entity_type' => $request->get('entity_type', ''),
            'user_id'     => $request->get('user_id', ''),
        ];

        $page = max(1, (int) $request->get('page', 1));

        ['logs' => $logs, 'total' => $total] = AuditLog::getFiltered($filters, $page, self::PER_PAGE);

        $pages = (int) ceil($total / self::PER_PAGE);
        $users = AuditLog::getUsers()->toArray();

        return view('audit.index', compact('logs', 'filters', 'page', 'pages', 'total', 'users'));
    }
}
