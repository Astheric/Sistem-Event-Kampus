<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use App\Services\TicketService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    /**
     * Show registration & checkout page for an event ticket.
     */
    public function checkout(Event $event, Ticket $ticket): View|RedirectResponse
    {
        if ($ticket->event_id !== $event->id) {
            abort(404);
        }

        $user = Auth::user();

        // BR-01: One participant cannot register for the same event multiple times
        $existing = Registration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->route('participant.tickets.index')->with('warning', 'Anda sudah terdaftar pada event ini dengan kode: '.$existing->registration_code);
        }

        // BR-02: Check ticket availability
        if (! $ticket->isAvailable()) {
            return redirect()->route('events.show', $event->slug)->with('error', 'Tiket ini sedang tidak tersedia atau kuota telah habis.');
        }

        return view('events.checkout', compact('event', 'ticket', 'user'));
    }

    /**
     * Store registration order.
     */
    public function store(Request $request, Event $event, Ticket $ticket): RedirectResponse
    {
        if ($ticket->event_id !== $event->id) {
            abort(404);
        }

        $user = Auth::user();
        $isFree = $ticket->price == 0;

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => [$isFree ? 'nullable' : 'required', 'string', 'max:100'],
            'proof_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('proofs', 'public');
        }

        try {
            $registration = $this->ticketService->register($user, $ticket, [
                'payment_method' => $validated['payment_method'] ?? 'Gratis',
                'proof_file' => $proofPath,
                'notes' => $validated['notes'] ?? null,
            ]);

            $message = $isFree
                ? 'Pendaftaran berhasil! E-Tiket Anda ('.$registration->registration_code.') langsung terbit.'
                : 'Pendaftaran berhasil! Silakan menunggu verifikasi pembayaran oleh panitia.';

            return redirect()->route('participant.tickets.index')->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
