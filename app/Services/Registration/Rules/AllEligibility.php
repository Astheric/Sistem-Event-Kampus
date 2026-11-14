<?php

namespace App\Services\Registration\Rules;

use App\Contracts\AudienceEligibility;
use App\Models\User;

class AllEligibility implements AudienceEligibility
{
    public function supports(string $audience): bool
    {
        return $audience === 'ALL';
    }

    public function eligible(User $user): bool
    {
        return true;
    }
}
