<?php

namespace App\Http\Controllers\Organizer;

use App\Exceptions\EventHubException;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyPaymentRequest;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Registration;
use App\Services\AuditLogger;
use App\Services\Export\ParticipantCsvExporter;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParticipantController extends Controller
{
    public function __construct(
        protected ParticipantCsvExporter $exporter,
        protected PaymentService $paymentService
    ) {}

    /**
     * Authorize organizer event ownership.
     */
    protected function authorizeEvent(Event $event): void
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }

        $isOwner = $user->organization && $event->organization_id === $user->organization->id;
        $isCommittee = $user->isCommitteeFor($event);

        if (! $isOwner && ! $isCommittee) {
            abort(403, 'Anda tidak memiliki akses panitia pada event ini.');
        }
    }

    /**
     * Display centralized participant list with filters (FR-11).
     */
    public function index(Event $event, Request $request): View
    {
        $this->authorize('viewParticipants', $event);

        $query = Registration::with(['user', 'ticket', 'payment', 'checkin'])
            ->where('event_id', $event->id);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('registration_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', strtoupper($status));
        }

        if ($ticketId = $request->input('ticket_id')) {
            $query->where('ticket_id', $ticketId);
        }

        $registrations = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $tickets = $event->tickets;

        $stats = [
            'total' => Registration::where('event_id', $event->id)->count(),
            'confirmed' => Registration::where('event_id', $event->id)->where('status', 'CONFIRMED')->count(),
            'pending' => Registration::where('event_id', $event->id)->where('status', 'PENDING')->count(),
            'attended' => Registration::where('event_id', $event->id)->where('status', 'ATTENDED')->count(),
        ];

        return view('organizer.participants.index', compact('event', 'registrations', 'tickets', 'stats'));
    }

    /**
     * Verify or reject participant payment proof (FR-08).
     */
    public function verifyPayment(VerifyPaymentRequest $request, Payment $payment): RedirectResponse
    {
        $registration = $payment->registration;
        $event = $registration->event;
        $this->authorize('verifyPayment', $event);

        $validated = $request->validated();
        $action = $validated['action'] === 'approve' ? 'approve' : 'reject';
        $notes = $validated['notes'] ?? null;

        try {
            $this->paymentService->verify($payment, $action, $notes, Auth::id());
        } catch (EventHubException $e) {
            return back()->with('error', $e->getMessage());
        }

        if ($action === 'approve') {
            Notification::create([
                'user_id' => $registration->user_id,
                'title' => 'Pembayaran Dikonfirmasi!',
                'message' => 'Pembayaran tiket event '.$event->title.' telah diverifikasi. E-Tiket Anda telah aktif!',
                'link' => route('participant.tickets.index'),
                'is_read' => false,
            ]);
            AuditLogger::log('payment.verified', 'Payment', $payment->id, [
                'registration_code' => $registration->registration_code,
                'amount' => (float) $payment->amount,
            ]);
            $msg = 'Pembayaran berhasil diverifikasi!';
        } else {
            Notification::create([
                'user_id' => $registration->user_id,
                'title' => 'Pembayaran Ditolak',
                'message' => 'Pembayaran tiket event '.$event->title.' ditolak. Catatan: '.($notes ?? 'Bukti pembayaran tidak valid.'),
                'link' => route('participant.tickets.index'),
                'is_read' => false,
            ]);
            AuditLogger::log('payment.rejected', 'Payment', $payment->id, [
                'registration_code' => $registration->registration_code,
                'reason' => $notes,
            ]);
            $msg = 'Pembayaran telah ditolak.';
        }

        return back()->with('success', $msg);
    }

    /**
     * Export participant table to CSV format (FR-11).
     */
    public function showProof(Payment $payment)
    {
        $this->authorize('viewProof', $payment);

        if (! $payment->proof_file || ! Storage::disk('private')->exists($payment->proof_file)) {
            abort(404);
        }

        return Storage::disk('private')->response($payment->proof_file);
    }

    public function export(Event $event): StreamedResponse
    {
        $this->authorize('exportParticipants', $event);

        AuditLogger::log('participants.exported', 'Event', $event->id, ['title' => $event->title]);

        return $this->exporter->export($event);
    }
}
