<?php

namespace App\Contracts;

use App\Models\User;

interface AudienceEligibility
{
    public function supports(string $audience): bool;

    public function eligible(User $user): bool;
}
