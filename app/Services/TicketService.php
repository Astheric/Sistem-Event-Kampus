<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketService
{
    /**
     * Register a participant for an event with concurrency safety.
     *
     * @param  array{payment_method?: string, proof_file?: string, notes?: string}  $options
     *
     * @throws Exception
     */
    public function register(User $user, Ticket $ticket, array $options = []): Registration
    {
        // BR-01: One participant cannot register for the same event multiple times
        $existing = Registration::where('user_id', $user->id)
            ->where('event_id', $ticket->event_id)
            ->first();

        if ($existing) {
            throw new Exception('Anda sudah terdaftar pada event ini dengan kode registrasi '.$existing->registration_code);
        }

        return DB::transaction(function () use ($user, $ticket, $options) {
            // Lock ticket row for update to prevent race conditions (BR-02)
            /** @var Ticket $lockedTicket */
            $lockedTicket = Ticket::where('id', $ticket->id)->lockForUpdate()->firstOrFail();

            if (! $lockedTicket->isAvailable()) {
                if ($lockedTicket->isSoldOut()) {
                    throw new Exception('Mohon maaf, tiket '.$lockedTicket->name.' sudah habis terjual.');
                }
                if ($lockedTicket->isSaleEnded()) {
                    throw new Exception('Periode penjualan tiket '.$lockedTicket->name.' telah berakhir.');
                }
                if ($lockedTicket->isSaleNotStarted()) {
                    throw new Exception('Penjualan tiket '.$lockedTicket->name.' belum dimulai.');
                }
                throw new Exception('Tiket tidak tersedia untuk dipesan saat ini.');
            }

            // Decrement available quota safely
            $lockedTicket->decrement('available_quota');

            // Generate unique registration code: EVT-2026-XXXXXX
            do {
                $code = 'EVT-2026-'.strtoupper(Str::random(6));
            } while (Registration::where('registration_code', $code)->exists());

            $isFree = $lockedTicket->price == 0;
            $status = $isFree ? 'CONFIRMED' : 'PENDING';

            $registration = Registration::create([
                'ticket_id' => $lockedTicket->id,
                'user_id' => $user->id,
                'event_id' => $lockedTicket->event_id,
                'registration_code' => $code,
                'status' => $status,
                'notes' => $options['notes'] ?? null,
            ]);

            // Create Payment record if paid
            if (! $isFree) {
                Payment::create([
                    'registration_id' => $registration->id,
                    'payment_method' => $options['payment_method'] ?? 'Bank Transfer',
                    'amount' => $lockedTicket->price,
                    'proof_file' => $options['proof_file'] ?? null,
                    'status' => 'PENDING',
                    'notes' => $options['notes'] ?? null,
                ]);
            }

            // In-app notification
            Notification::create([
                'user_id' => $user->id,
                'title' => $isFree ? 'Tiket Berhasil Dikonfirmasi!' : 'Pendaftaran Berhasil — Menunggu Pembayaran',
                'message' => $isFree
                    ? 'Tiket Anda untuk event '.$lockedTicket->event->title.' telah terbit ('.$code.').'
                    : 'Pendaftaran event '.$lockedTicket->event->title.' berhasil. Silakan selesaikan pembayaran.',
                'link' => route('participant.tickets.index'),
                'is_read' => false,
            ]);

            AuditLogger::log(
                'ticket.registered',
                'Registration',
                $registration->id,
                [
                    'code' => $code,
                    'ticket' => $lockedTicket->name,
                    'amount' => (float) $lockedTicket->price,
                    'status' => $status,
                ]
            );

            return $registration;
        });
    }

    /**
     * Cancel a registration and restore quota.
     */
    public function cancel(Registration $registration, string $reason = 'Dibatalkan'): void
    {
        DB::transaction(function () use ($registration, $reason) {
            if ($registration->isCancelled()) {
                return;
            }

            $registration->update([
                'status' => 'CANCELLED',
                'notes' => $reason,
            ]);

            // Restore ticket quota
            $ticket = Ticket::where('id', $registration->ticket_id)->lockForUpdate()->first();
            if ($ticket) {
                $ticket->increment('available_quota');
            }

            if ($registration->payment && $registration->payment->isPending()) {
                $registration->payment->update(['status' => 'REJECTED']);
            }

            AuditLogger::log('ticket.cancelled', 'Registration', $registration->id, [
                'code' => $registration->registration_code,
                'reason' => $reason,
            ]);
        });
    }
}
