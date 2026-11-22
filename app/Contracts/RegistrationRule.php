<?php

namespace App\Contracts;

use App\Exceptions\EventHubException;
use App\Models\Ticket;
use App\Models\User;

interface RegistrationRule
{
    /** @throws EventHubException */
    public function check(User $user, Ticket $ticket): void;
}
