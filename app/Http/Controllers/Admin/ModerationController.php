<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Notification;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerationController extends Controller
{
    /**
     * Display event moderation queue (FR-19).
     */
    public function index(Request $request): View
    {
        $status = $request->input('status', 'SUBMITTED');

        $query = Event::with(['organization', 'category', 'venue', 'tickets']);

        if ($status !== 'ALL') {
            $query->where('status', $status);
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $counts = [
            'submitted' => Event::where('status', 'SUBMITTED')->count(),
            'published' => Event::where('status', 'PUBLISHED')->count(),
            'rejected' => Event::where('status', 'REJECTED')->count(),
            'all' => Event::count(),
        ];

        return view('admin.moderation.index', compact('events', 'status', 'counts'));
    }

    /**
     * Preview single event details before moderating.
     */
    public function show(Event $event): View
    {
        $event->load(['organization.user', 'category', 'venue', 'tickets']);

        return view('admin.moderation.show', compact('event'));
    }

    /**
     * Approve event and publish it to public catalog (FR-19).
     */
    public function approve(Event $event): RedirectResponse
    {
        $event->update([
            'status' => 'PUBLISHED',
            'rejection_reason' => null,
        ]);

        Notification::create([
            'user_id' => $event->organization->user_id,
            'title' => 'Event Disetujui!',
            'message' => 'Selamat! Event "'.$event->title.'" telah disetujui oleh Admin dan kini aktif di katalog publik.',
            'link' => route('events.show', $event->slug),
            'is_read' => false,
        ]);

        AuditLogger::log('event.approved', 'Event', $event->id, [
            'title' => $event->title,
            'organizer' => $event->organization->name,
        ]);

        return redirect()->route('admin.moderation.index')->with('success', 'Event "'.$event->title.'" berhasil DISETUJUI dan dipublikasikan!');
    }

    /**
     * Reject event with revision note (FR-19).
     */
    public function reject(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $event->update([
            'status' => 'REJECTED',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        Notification::create([
            'user_id' => $event->organization->user_id,
            'title' => 'Event Memerlukan Revisi',
            'message' => 'Event "'.$event->title.'" ditolak oleh Admin. Alasan: '.$validated['rejection_reason'],
            'link' => route('organizer.events.edit', $event->id),
            'is_read' => false,
        ]);

        AuditLogger::log('event.rejected', 'Event', $event->id, [
            'title' => $event->title,
            'reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.moderation.index')->with('success', 'Event "'.$event->title.'" telah DITOLAK dengan catatan revisi.');
    }
}
