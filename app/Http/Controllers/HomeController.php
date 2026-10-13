<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the EventHub landing page.
     */
    public function index(): View
    {
        $categories = Category::withCount(['events' => function ($q) {
            $q->where('status', 'PUBLISHED');
        }])->get();

        $featuredEvents = Event::with(['category', 'venue', 'tickets', 'organization'])
            ->where('status', 'PUBLISHED')
            ->where('end_time', '>=', Carbon::now())
            ->orderBy('start_time', 'asc')
            ->take(6)
            ->get();

        $pastEvents = Event::with(['category', 'venue', 'reviews'])
            ->where('status', 'COMPLETED')
            ->orderBy('end_time', 'desc')
            ->take(3)
            ->get();

        $stats = [
            'total_events' => Event::whereIn('status', ['PUBLISHED', 'COMPLETED'])->count(),
            'total_participants' => User::where('role', 'participant')->count(),
            'certificates_issued' => Certificate::count(),
            'active_organizers' => Organization::count(),
        ];

        return view('home', compact('categories', 'featuredEvents', 'pastEvents', 'stats'));
    }
}
