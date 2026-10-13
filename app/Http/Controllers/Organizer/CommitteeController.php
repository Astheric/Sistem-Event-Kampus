<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventMember;
use App\Models\Notification;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CommitteeController extends Controller
{
    /**
     * Authorize organizer event ownership.
     */
    protected function authorizeEvent(Event $event): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }

        if (! $user->organization || $event->organization_id !== $user->organization->id) {
            abort(403, 'Anda tidak memiliki otoritas panitia pada event ini.');
        }
    }

    /**
     * Display committee members for an event (FR-12).
     */
    public function index(Event $event): View
    {
        $this->authorizeEvent($event);

        $event->load('eventMembers.user');

        return view('organizer.committee.index', compact('event'));
    }

    /**
     * Add committee member to event.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'committee_role' => ['required', 'in:Registration,Finance,Documentation'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        // Check if already assigned
        $exists = EventMember::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'User '.$user->name.' sudah menjadi panitia pada event ini.');
        }

        EventMember::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'committee_role' => $validated['committee_role'],
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Penugasan Panitia Event',
            'message' => 'Anda telah ditugaskan sebagai panitia divisi '.$validated['committee_role'].' untuk event '.$event->title.'.',
            'link' => route('events.show', $event->slug),
            'is_read' => false,
        ]);

        AuditLogger::log('committee.added', 'EventMember', $event->id, [
            'user' => $user->name,
            'role' => $validated['committee_role'],
        ]);

        return back()->with('success', 'Panitia '.$user->name.' ('.$validated['committee_role'].') berhasil ditambahkan!');
    }

    /**
     * Remove committee member.
     */
    public function destroy(Event $event, EventMember $member): RedirectResponse
    {
        $this->authorizeEvent($event);

        if ($member->event_id !== $event->id) {
            abort(404);
        }

        $memberName = $member->user->name;
        $member->delete();

        AuditLogger::log('committee.removed', 'EventMember', $event->id, [
            'user' => $memberName,
        ]);

        return back()->with('success', 'Panitia '.$memberName.' berhasil dihapus dari tim.');
    }
}
