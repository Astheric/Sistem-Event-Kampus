<?php

namespace App\Services;

use App\Exceptions\AlreadyRegisteredException;
use App\Exceptions\AudienceMismatchException;
use App\Exceptions\EventClosedException;
use App\Exceptions\EventFullException;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Support\Capacity;
use Exception;
use Illuminate\Database\UniqueConstraintViolationException;
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
        return DB::transaction(function () use ($user, $ticket, $options) {
            // Lock event and ticket rows to prevent race conditions
            $event = Event::where('id', $ticket->event_id)->lockForUpdate()->firstOrFail();
            /** @var Ticket $lockedTicket */
            $lockedTicket = Ticket::where('id', $ticket->id)->lockForUpdate()->firstOrFail();

            // BR-01: One participant cannot register for the same event multiple times (inside transaction)
            $existing = Registration::where('user_id', $user->id)
                ->where('event_id', $ticket->event_id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw new AlreadyRegisteredException('Anda sudah terdaftar pada event ini dengan kode registrasi '.$existing->registration_code);
            }

            // Event must be open for registration
            if ($event->status !== 'PUBLISHED' || $event->end_time < now()) {
                throw new EventClosedException('Event tidak tersedia untuk pendaftaran.');
            }

            // Audience eligibility
            $eligibilities = app()->tagged('audience.eligibility');
            $eligible = false;
            foreach ($eligibilities as $eligibility) {
                if ($eligibility->supports((string) $event->audience) && $eligibility->eligible($user)) {
                    $eligible = true;
                    break;
                }
            }
            if (! $eligible) {
                throw new AudienceMismatchException('Anda tidak memenuhi syarat audiens untuk event ini.');
            }

            // Capacity check
            $capacity = Capacity::forEvent($event);
            if ($capacity->remaining <= 0) {
                throw new EventFullException('Kapasitas event telah penuh.');
            }

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

            try {
                $registration = Registration::create([
                    'ticket_id' => $lockedTicket->id,
                    'user_id' => $user->id,
                    'event_id' => $lockedTicket->event_id,
                    'registration_code' => $code,
                    'status' => $status,
                    'notes' => $options['notes'] ?? null,
                ]);
            } catch (UniqueConstraintViolationException $e) {
                $existing = Registration::where('user_id', $user->id)
                    ->where('event_id', $lockedTicket->event_id)
                    ->first();
                throw new AlreadyRegisteredException('Anda sudah terdaftar pada event ini'.($existing ? ' dengan kode registrasi '.$existing->registration_code : ''));
            }

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
