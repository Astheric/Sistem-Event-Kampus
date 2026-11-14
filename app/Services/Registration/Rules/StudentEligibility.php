<?php

namespace App\Services\Registration\Rules;

use App\Contracts\AudienceEligibility;
use App\Models\User;

class StudentEligibility implements AudienceEligibility
{
    public function supports(string $audience): bool
    {
        return $audience === 'STUDENT';
    }

    public function eligible(User $user): bool
    {
        return $user->role === 'participant';
    }
}
