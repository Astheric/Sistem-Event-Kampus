<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParticipantController extends Controller
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
        $this->authorizeEvent($event);

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
    public function verifyPayment(Payment $payment, Request $request): RedirectResponse
    {
        $registration = $payment->registration;
        $this->authorizeEvent($registration->event);

        $action = $request->input('action'); // 'approve' or 'reject'
        $notes = $request->input('notes');

        DB::transaction(function () use ($payment, $registration, $action, $notes) {
            if ($action === 'approve') {
                $payment->update([
                    'status' => 'VERIFIED',
                    'verified_at' => Carbon::now(),
                    'verified_by' => Auth::id(),
                    'notes' => $notes,
                ]);

                $registration->update(['status' => 'CONFIRMED']);

                Notification::create([
                    'user_id' => $registration->user_id,
                    'title' => 'Pembayaran Dikonfirmasi!',
                    'message' => 'Pembayaran tiket event '.$registration->event->title.' telah diverifikasi. E-Tiket Anda telah aktif!',
                    'link' => route('participant.tickets.index'),
                    'is_read' => false,
                ]);

                AuditLogger::log('payment.verified', 'Payment', $payment->id, [
                    'registration_code' => $registration->registration_code,
                    'amount' => (float) $payment->amount,
                ]);
            } else {
                $payment->update([
                    'status' => 'REJECTED',
                    'notes' => $notes ?? 'Bukti pembayaran tidak sesuai atau tidak valid.',
                ]);

                $registration->update([
                    'status' => 'CANCELLED',
                    'notes' => $notes ?? 'Pembayaran ditolak.',
                ]);

                // Restore ticket quota
                $ticket = Ticket::where('id', $registration->ticket_id)->lockForUpdate()->first();
                if ($ticket) {
                    $ticket->increment('available_quota');
                }

                Notification::create([
                    'user_id' => $registration->user_id,
                    'title' => 'Pembayaran Ditolak',
                    'message' => 'Pembayaran tiket event '.$registration->event->title.' ditolak. Catatan: '.($notes ?? 'Bukti pembayaran tidak valid.'),
                    'link' => route('participant.tickets.index'),
                    'is_read' => false,
                ]);

                AuditLogger::log('payment.rejected', 'Payment', $payment->id, [
                    'registration_code' => $registration->registration_code,
                    'reason' => $notes,
                ]);
            }
        });

        $msg = $action === 'approve' ? 'Pembayaran berhasil diverifikasi!' : 'Pembayaran telah ditolak.';

        return back()->with('success', $msg);
    }

    /**
     * Export participant table to CSV format (FR-11).
     */
    public function export(Event $event): StreamedResponse
    {
        $this->authorizeEvent($event);

        $fileName = 'peserta-'.Str::slug($event->title).'-'.date('Ymd_His').'.csv';

        $registrations = Registration::with(['user', 'ticket', 'payment', 'checkin'])
            ->where('event_id', $event->id)
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($registrations) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Kode Tiket',
                'Nama Peserta',
                'Email',
                'No. Telepon',
                'Tipe Tiket',
                'Harga Tiket',
                'Status Pendaftaran',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Waktu Check-in',
                'Tanggal Daftar',
            ]);

            foreach ($registrations as $reg) {
                fputcsv($handle, [
                    $reg->registration_code,
                    $reg->user->name,
                    $reg->user->email,
                    $reg->user->phone ?? '-',
                    $reg->ticket->name,
                    $reg->ticket->price,
                    $reg->status,
                    $reg->payment->payment_method ?? 'Gratis',
                    $reg->payment->status ?? 'N/A',
                    $reg->checkin ? Carbon::parse($reg->checkin->checked_in_at)->format('Y-m-d H:i:s') : 'Belum Hadir',
                    $reg->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        AuditLogger::log('participants.exported', 'Event', $event->id, ['title' => $event->title]);

        return response()->stream($callback, 200, $headers);
    }
}
