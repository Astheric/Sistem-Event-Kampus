<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'PENDING';
    case Verified = 'VERIFIED';
    case Rejected = 'REJECTED';

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    public function isVerified(): bool
    {
        return $this === self::Verified;
    }

    public function isRejected(): bool
    {
        return $this === self::Rejected;
    }
}
