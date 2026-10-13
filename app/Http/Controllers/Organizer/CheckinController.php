<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Event;
use App\Services\CheckinService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckinController extends Controller
{
    public function __construct(
        protected CheckinService $checkinService
    ) {}

    /**
     * Authorize committee or organizer access.
     */
    protected function authorizeEvent(Event $event): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }

        $isOwner = $user->organization && $event->organization_id === $user->organization->id;
        $isCommittee = $user->isCommitteeFor($event, 'Registration') || $user->isCommitteeFor($event);

        if (! $isOwner && ! $isCommittee) {
            abort(403, 'Anda tidak memiliki hak akses pemindaian check-in untuk event ini.');
        }
    }

    /**
     * Display browser QR camera scanner and manual input panel (FR-10).
     */
    public function scanner(Event $event): View
    {
        $this->authorizeEvent($event);

        $recentCheckins = Checkin::with(['registration.user', 'registration.ticket'])
            ->whereHas('registration', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            })
            ->orderBy('checked_in_at', 'desc')
            ->take(10)
            ->get();

        $stats = [
            'total_confirmed' => $event->registrations()->whereIn('status', ['CONFIRMED', 'ATTENDED'])->count(),
            'total_attended' => $event->registrations()->where('status', 'ATTENDED')->count(),
        ];

        return view('organizer.checkin.scanner', compact('event', 'recentCheckins', 'stats'));
    }

    /**
     * Process real-time QR code scan or manual code submission.
     */
    public function processScan(Request $request, Event $event): JsonResponse
    {
        $this->authorizeEvent($event);

        $code = $request->input('code');
        if (! $code) {
            return response()->json([
                'success' => false,
                'status' => 'INVALID',
                'message' => 'Kode tiket wajib diisi.',
            ], 422);
        }

        $result = $this->checkinService->processCheckin($code, $event, Auth::user());

        return response()->json([
            'success' => $result['success'],
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => isset($result['registration']) ? [
                'code' => $result['registration']->registration_code,
                'name' => $result['registration']->user->name,
                'email' => $result['registration']->user->email,
                'ticket' => $result['registration']->ticket->name,
                'time' => Carbon::now()->format('H:i:s').' WIB',
            ] : null,
        ]);
    }
}
