<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display admin overview dashboard with platform health metrics (FR-18).
     */
    public function index(): View
    {
        $metrics = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'pending_approvals' => Event::where('status', 'SUBMITTED')->count(),
            'total_registrations' => Registration::count(),
            'total_certificates' => Certificate::count(),
        ];

        $pendingEvents = Event::with(['organization', 'category', 'venue', 'tickets'])
            ->where('status', 'SUBMITTED')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        $recentLogs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('metrics', 'pendingEvents', 'recentLogs'));
    }
}
