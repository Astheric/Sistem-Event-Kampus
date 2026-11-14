<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewProof(User $user, Payment $payment): bool
    {
        $registration = $payment->registration;
        $event = $registration->event;

        if ($user->isAdmin()) {
            return true;
        }

        if ($registration->user_id === $user->id) {
            return true;
        }

        if ($event->organization->user_id === $user->id) {
            return true;
        }

        $member = $event->eventMembers()->where('user_id', $user->id)->first();
        if ($member && $member->committee_role === 'Finance') {
            return true;
        }

        return false;
    }
}
