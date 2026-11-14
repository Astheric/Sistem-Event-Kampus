<?php

namespace App\Services\Payment;

use App\Exceptions\InvalidStateTransitionException;
use App\Models\Payment;
use App\Services\TicketService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    public function verify(Payment $payment, string $action, ?string $notes = null, ?int $verifierId = null): void
    {
        if (! in_array($action, ['approve', 'reject'], true)) {
            throw new InvalidStateTransitionException('Aksi pembayaran tidak valid.');
        }

        DB::transaction(function () use ($payment, $action, $notes, $verifierId) {
            $payment = Payment::where('id', $payment->id)->lockForUpdate()->firstOrFail();
            $registration = $payment->registration;

            if ($payment->status !== 'PENDING' || $registration->status !== 'PENDING') {
                throw new InvalidStateTransitionException('Pembayaran ini tidak dapat diproses.');
            }

            if ($action === 'approve') {
                $payment->update([
                    'status' => 'VERIFIED',
                    'verified_at' => Carbon::now(),
                    'verified_by' => $verifierId,
                    'notes' => $notes,
                ]);

                $registration->update(['status' => 'CONFIRMED']);
            } else {
                $payment->update([
                    'status' => 'REJECTED',
                    'notes' => $notes ?? 'Bukti pembayaran tidak sesuai atau tidak valid.',
                ]);

                $this->ticketService->cancel($registration, $notes ?? 'Pembayaran ditolak.');
            }
        });
    }
}
