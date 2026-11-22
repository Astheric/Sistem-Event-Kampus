<?php

namespace App\Enums;

enum CapacityState: string
{
    case Available = 'available';
    case AlmostFull = 'almost_full';
    case Full = 'full';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Tersedia',
            self::AlmostFull => 'Hampir penuh',
            self::Full => 'Penuh',
            self::Closed => 'Ditutup',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Available => 'check-circle',
            self::AlmostFull => 'alert-triangle',
            self::Full => 'x-circle',
            self::Closed => 'lock',
        };
    }
}
