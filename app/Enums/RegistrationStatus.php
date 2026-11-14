<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case Pending = 'PENDING';
    case Confirmed = 'CONFIRMED';
    case Cancelled = 'CANCELLED';
    case Attended = 'ATTENDED';
    case Completed = 'COMPLETED';

    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu pembayaran',
            self::Confirmed => 'Terkonfirmasi',
            self::Cancelled => 'Dibatalkan',
            self::Attended => 'Hadir',
            self::Completed => 'Selesai',
        };
    }
}
