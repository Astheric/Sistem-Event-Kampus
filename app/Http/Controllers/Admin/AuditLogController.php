<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display system audit trail (FR-20, BR-08).
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with('user');

        if ($action = $request->input('action')) {
            $query->where('action', 'like', "%{$action}%");
        }

        if ($entity = $request->input('entity')) {
            $query->where('entity_type', $entity);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $actions = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit_logs.index', compact('logs', 'actions'));
    }
}
