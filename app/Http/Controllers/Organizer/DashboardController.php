<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display organizer dashboard with key metrics and Chart.js analytics (FR-17).
     */
    public function index(): View
    {
        $user = Auth::user();
        $org = $user->organization;

        $eventIds = $org ? $org->events()->pluck('id')->toArray() : [];

        $totalEvents = count($eventIds);
        $totalRegistrations = Registration::whereIn('event_id', $eventIds)->count();
        $totalAttended = Registration::whereIn('event_id', $eventIds)->where('status', 'ATTENDED')->count();

        $totalRevenue = Payment::whereHas('registration', function ($q) use ($eventIds) {
            $q->whereIn('event_id', $eventIds);
        })->where('status', 'VERIFIED')->sum('amount');

        // Recent registrations
        $recentRegistrations = Registration::with(['user', 'event', 'ticket', 'payment'])
            ->whereIn('event_id', $eventIds)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data for Chart.js
        $eventsWithStats = Event::whereIn('id', $eventIds)
            ->withCount([
                'registrations',
                'registrations as attended_count' => function ($q) {
                    $q->where('status', 'ATTENDED');
                },
            ])
            ->take(6)
            ->get();

        $chartLabels = $eventsWithStats->pluck('title')->map(function ($title) {
            return mb_strimwidth($title, 0, 20, '...');
        })->toArray();
        $chartRegistrations = $eventsWithStats->pluck('registrations_count')->toArray();
        $chartAttended = $eventsWithStats->pluck('attended_count')->toArray();

        return view('organizer.dashboard', compact(
            'totalEvents',
            'totalRegistrations',
            'totalAttended',
            'totalRevenue',
            'recentRegistrations',
            'chartLabels',
            'chartRegistrations',
            'chartAttended',
            'org'
        ));
    }
}
