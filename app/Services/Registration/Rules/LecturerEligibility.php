<?php

namespace App\Services\Registration\Rules;

use App\Contracts\AudienceEligibility;
use App\Models\User;

class LecturerEligibility implements AudienceEligibility
{
    public function supports(string $audience): bool
    {
        return $audience === 'LECTURER';
    }

    public function eligible(User $user): bool
    {
        return $user->role === 'dosen' && $user->isVerified();
    }
}
