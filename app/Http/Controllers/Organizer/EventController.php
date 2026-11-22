<?php

namespace App\Http\Controllers\Organizer;

use App\Enums\EventStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Venue;
use App\Services\AuditLogger;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Check if user owns this event (BR-06).
     */
    protected function authorizeEvent(Event $event): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }

        if (! $user->organization || $event->organization_id !== $user->organization->id) {
            abort(403, 'Anda tidak memiliki otoritas atas event ini.');
        }
    }

    /**
     * Display a listing of organizer events.
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $events = Event::with(['category', 'venue', 'tickets'])->withCount('registrations')->orderBy('created_at', 'desc')->paginate(10);

            return view('organizer.events.index', compact('events'));
        }

        $org = $user->organization;

        $events = $org
            ? $org->events()->with(['category', 'venue', 'tickets'])->withCount('registrations')->orderBy('created_at', 'desc')->paginate(10)
            : Event::where('id', 0)->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    /**
     * Show form for creating a new event.
     */
    public function create(): View
    {
        $categories = Category::all();
        $venues = Venue::all();

        return view('organizer.events.create', compact('categories', 'venues'));
    }

    /**
     * Store newly created event.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $org = $user->organization;

        if (! $org) {
            return back()->with('error', 'Anda harus memiliki organisasi untuk membuat event.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'description' => ['required', 'string'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'capacity' => ['required', 'integer', 'min:1'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'action' => ['required', 'in:draft,submit'],
        ]);

        $slug = Str::slug($validated['title']);
        $count = Event::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-".($count + 1);
        }

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
        }

        $status = 'DRAFT';

        $event = Event::create([
            'organization_id' => $org->id,
            'category_id' => $validated['category_id'],
            'venue_id' => $validated['venue_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $slug,
            'banner' => $bannerPath,
            'description' => $validated['description'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'capacity' => $validated['capacity'],
            'status' => $status,
        ]);

        AuditLogger::log('event.created', 'Event', $event->id, [
            'title' => $event->title,
            'status' => $status,
        ]);

        return redirect()->route('organizer.events.tickets', $event->id)
            ->with('success', 'Event berhasil disimpan! Sekarang, silakan atur tipe tiket untuk event ini.');
    }

    /**
     * Show form for editing an event.
     */
    public function edit(Event $event): View
    {
        $this->authorizeEvent($event);

        $categories = Category::all();
        $venues = Venue::all();

        return view('organizer.events.edit', compact('event', 'categories', 'venues'));
    }

    /**
     * Update specified event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'venue_id' => ['nullable', 'exists:venues,id'],
            'description' => ['required', 'string'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'capacity' => ['required', 'integer', 'min:1'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $event->update($validated);

        AuditLogger::log('event.updated', 'Event', $event->id, [
            'title' => $event->title,
        ]);

        return redirect()->route('organizer.events.index')->with('success', 'Data event berhasil diperbarui.');
    }

    /**
     * Submit event for Admin approval (FR-03).
     */
    public function submitForReview(Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        $user = Auth::user();
        if ($user->isOrganizer() && ! $user->isVerified()) {
            return back()->with('error', 'Akun organizer belum diverifikasi oleh admin.');
        }

        if ($event->tickets()->count() === 0) {
            return back()->with('error', 'Event wajib memiliki minimal 1 tipe tiket sebelum diajukan ke Admin.');
        }

        $event->update(['status' => 'SUBMITTED', 'rejection_reason' => null]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pengajuan Event Baru',
                'message' => 'Event "'.$event->title.'" diajukan oleh '.$event->organization->name.' untuk ditinjau.',
                'link' => route('admin.moderation.index'),
                'is_read' => false,
            ]);
        }

        AuditLogger::log('event.submitted', 'Event', $event->id, [
            'title' => $event->title,
        ]);

        return back()->with('success', 'Event berhasil diajukan! Menunggu tinjauan Admin.');
    }

    /**
     * Update event status (Complete or Cancel; Publish is reserved for admins).
     */
    public function updateStatus(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'status' => ['required', 'in:PUBLISHED,COMPLETED,CANCELLED'],
        ]);

        $oldStatus = EventStatus::tryFrom((string) $event->status);
        $newStatus = EventStatus::from($validated['status']);

        if ($oldStatus === null || ! $oldStatus->canTransitionTo($newStatus)) {
            $from = $oldStatus?->label() ?? $event->status;

            return back()->with('error', 'Perubahan status dari '.$from.' ke '.$newStatus->label().' tidak diizinkan.');
        }

        if ($newStatus === EventStatus::Published && ! Auth::user()->isAdmin()) {
            return back()->with('error', 'Publikasi event dilakukan melalui persetujuan Admin.');
        }

        if ($newStatus === EventStatus::Cancelled) {
            foreach ($event->registrations()->whereIn('status', ['PENDING', 'CONFIRMED'])->get() as $registration) {
                app(TicketService::class)->cancel($registration, 'Event dibatalkan');
                Notification::create([
                    'user_id' => $registration->user_id,
                    'title' => 'Event Dibatalkan',
                    'message' => 'Event '.$event->title.' dibatalkan. Pendaftaran Anda dibatalkan.',
                    'link' => route('participant.tickets.index'),
                    'is_read' => false,
                ]);
            }
        }

        $event->update(['status' => $newStatus->value]);

        AuditLogger::log('event.status_changed', 'Event', $event->id, [
            'from' => $oldStatus->value,
            'to' => $newStatus->value,
        ]);

        return back()->with('success', 'Status event berhasil diperbarui menjadi '.$newStatus->label().'.');
    }

    /**
     * Manage tiered tickets for the event (FR-06).
     */
    public function tickets(Event $event): View
    {
        $this->authorizeEvent($event);

        $event->load('tickets');

        return view('organizer.events.tickets', compact('event'));
    }

    /**
     * Store new ticket tier.
     */
    public function storeTicket(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
            'start_sale' => ['required', 'date'],
            'end_sale' => ['required', 'date', 'after:start_sale'],
        ]);

        $currentTotal = $event->tickets()->sum('quota');
        if ($currentTotal + (int) $validated['quota'] > (int) $event->capacity && (int) $event->capacity > 0) {
            return back()->with('error', 'Total kuota tiket melebihi kapasitas event.')->withInput();
        }

        $ticket = Ticket::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'price' => $validated['price'],
            'quota' => $validated['quota'],
            'available_quota' => $validated['quota'],
            'start_sale' => $validated['start_sale'],
            'end_sale' => $validated['end_sale'],
        ]);

        AuditLogger::log('ticket.created', 'Ticket', $ticket->id, [
            'name' => $ticket->name,
            'price' => (float) $ticket->price,
            'quota' => $ticket->quota,
        ]);

        return back()->with('success', 'Tipe tiket '.$ticket->name.' berhasil ditambahkan!');
    }

    /**
     * Delete ticket tier.
     */
    public function destroyTicket(Event $event, Ticket $ticket): RedirectResponse
    {
        $this->authorizeEvent($event);

        if ($ticket->event_id !== $event->id) {
            abort(404);
        }

        if ($ticket->registrations()->exists()) {
            return back()->with('error', 'Tiket tidak dapat dihapus karena sudah ada peserta yang memesan.');
        }

        $ticket->delete();

        return back()->with('success', 'Tipe tiket berhasil dihapus.');
    }

    /**
     * Delete an event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);

        if ($event->registrations()->where('status', 'CONFIRMED')->exists()) {
            return back()->with('error', 'Event tidak dapat dihapus karena sudah memiliki peserta terkonfirmasi.');
        }

        $event->delete();

        AuditLogger::log('event.deleted', 'Event', $event->id, ['title' => $event->title]);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
