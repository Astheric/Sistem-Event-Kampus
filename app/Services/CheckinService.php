<?php

namespace App\Services;

use App\Models\Checkin;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CheckinService
{
    /**
     * Process check-in by registration code.
     *
     * @return array{
     *     success: bool,
     *     status: 'SUCCESS'|'ALREADY_CHECKED_IN'|'INVALID'|'UNPAID',
     *     message: string,
     *     registration?: Registration,
     *     checkin?: Checkin
     * }
     */
    public function processCheckin(string $registrationCode, Event $event, User $scanner): array
    {
        $code = trim(strtoupper($registrationCode));

        $registration = Registration::with(['user', 'ticket', 'checkin.scanner'])
            ->where('registration_code', $code)
            ->first();

        if (! $registration) {
            return [
                'success' => false,
                'status' => 'INVALID',
                'message' => 'Kode tiket '.$code.' tidak ditemukan di sistem.',
            ];
        }

        if ($registration->event_id !== $event->id) {
            return [
                'success' => false,
                'status' => 'INVALID',
                'message' => 'Tiket ini terdaftar untuk event lain: '.$registration->event->title,
            ];
        }

        if ($registration->status === 'CANCELLED') {
            return [
                'success' => false,
                'status' => 'INVALID',
                'message' => 'Peringatan: Pendaftaran tiket ini telah DIBATALKAN.',
            ];
        }

        if ($registration->status === 'PENDING') {
            return [
                'success' => false,
                'status' => 'UNPAID',
                'message' => 'Peringatan: Tiket berstatus PENDING (Pembayaran belum diverifikasi panitia).',
            ];
        }

        // BR-03: Anti-double check-in check
        if ($registration->status === 'ATTENDED' || $registration->checkin) {
            $checkedAt = $registration->checkin?->checked_in_at
                ? Carbon::parse($registration->checkin->checked_in_at)->format('d M Y H:i:s')
                : 'sebelumnya';
            $scannedBy = $registration->checkin?->scanner?->name ?? 'Panitia';

            return [
                'success' => false,
                'status' => 'ALREADY_CHECKED_IN',
                'message' => "TIKET SUDAH DIGUNAKAN! Check-in tercatat pada {$checkedAt} WIB oleh {$scannedBy}.",
                'registration' => $registration,
                'checkin' => $registration->checkin,
            ];
        }

        // Execute Check-in
        return DB::transaction(function () use ($registration, $scanner) {
            $checkin = Checkin::create([
                'registration_id' => $registration->id,
                'scanned_by' => $scanner->id,
                'checked_in_at' => Carbon::now(),
            ]);

            $registration->update([
                'status' => 'ATTENDED',
            ]);

            Notification::create([
                'user_id' => $registration->user_id,
                'title' => 'Check-in Berhasil!',
                'message' => 'Anda telah berhasil check-in pada event '.$registration->event->title.' pada '.Carbon::now()->format('H:i').' WIB.',
                'link' => route('participant.tickets.index'),
                'is_read' => false,
            ]);

            AuditLogger::log(
                'checkin.verified',
                'Registration',
                $registration->id,
                [
                    'code' => $registration->registration_code,
                    'attendee' => $registration->user->name,
                    'scanned_by' => $scanner->name,
                ]
            );

            return [
                'success' => true,
                'status' => 'SUCCESS',
                'message' => 'Check-in BERHASIL! Selamat datang, '.$registration->user->name.'.',
                'registration' => $registration->fresh(['user', 'ticket']),
                'checkin' => $checkin,
            ];
        });
    }
}
