<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventCatalogController extends Controller
{
    /**
     * Display searchable and filterable event catalog.
     */
    public function index(Request $request): View
    {
        $query = Event::with(['category', 'venue', 'tickets', 'organization'])
            ->where('status', 'PUBLISHED');

        // Search keyword
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Price filter
        if ($price = $request->input('price')) {
            if ($price === 'free') {
                $query->whereHas('tickets', function ($q) {
                    $q->where('price', 0);
                });
            } elseif ($price === 'paid') {
                $query->whereHas('tickets', function ($q) {
                    $q->where('price', '>', 0);
                });
            }
        }

        // City filter
        if ($city = $request->input('city')) {
            $query->whereHas('venue', function ($q) use ($city) {
                $q->where('city', $city);
            });
        }

        // Date filter
        $now = Carbon::now();
        if ($dateFilter = $request->input('date')) {
            if ($dateFilter === 'today') {
                $query->whereDate('start_time', $now->toDateString());
            } elseif ($dateFilter === 'this_week') {
                $query->whereBetween('start_time', [$now->startOfWeek()->toDateTimeString(), $now->copy()->endOfWeek()->toDateTimeString()]);
            } elseif ($dateFilter === 'upcoming') {
                $query->where('start_time', '>=', $now);
            }
        } else {
            // Default show upcoming and ongoing
            $query->where('end_time', '>=', $now->subHours(6));
        }

        // Sorting
        $sort = $request->input('sort', 'date_asc');
        if ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'popular') {
            $query->withCount('registrations')->orderBy('registrations_count', 'desc');
        } else {
            $query->orderBy('start_time', 'asc');
        }

        $events = $query->paginate(9)->withQueryString();
        $categories = Category::all();
        $cities = Venue::select('city')->distinct()->pluck('city');

        return view('events.index', compact('events', 'categories', 'cities'));
    }

    /**
     * Display single event details.
     */
    public function show(string $slug): View
    {
        $event = Event::with([
            'category',
            'venue',
            'organization.user',
            'tickets' => function ($q) {
                $q->orderBy('price', 'asc');
            },
            'reviews.user',
        ])->where('slug', $slug)->firstOrFail();

        // BR-05: Non-published event only visible to its organizer or admin
        if ($event->status !== 'PUBLISHED' && $event->status !== 'COMPLETED') {
            $user = Auth::user();
            if (! $user || (! $user->isAdmin() && $event->organization->user_id !== $user->id)) {
                abort(404);
            }
        }

        // Check if user already registered (BR-01)
        $userRegistration = null;
        if (Auth::check()) {
            $userRegistration = Registration::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('events.show', compact('event', 'userRegistration'));
    }
}
