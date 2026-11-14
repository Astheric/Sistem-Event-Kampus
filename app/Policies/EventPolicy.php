<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function viewParticipants(User $user, Event $event): bool
    {
        if ($user->isAdmin() || $event->organization->user_id === $user->id) {
            return true;
        }

        $member = $event->eventMembers()->where('user_id', $user->id)->first();
        if (! $member) {
            return false;
        }

        return in_array($member->committee_role, ['Registration', 'Finance', 'Documentation'], true);
    }

    public function checkin(User $user, Event $event): bool
    {
        if ($user->isAdmin() || $event->organization->user_id === $user->id) {
            return true;
        }

        $member = $event->eventMembers()->where('user_id', $user->id)->first();
        if (! $member) {
            return false;
        }

        return $member->committee_role === 'Registration';
    }

    public function verifyPayment(User $user, Event $event): bool
    {
        if ($user->isAdmin() || $event->organization->user_id === $user->id) {
            return true;
        }

        $member = $event->eventMembers()->where('user_id', $user->id)->first();
        if (! $member) {
            return false;
        }

        return $member->committee_role === 'Finance';
    }

    public function exportParticipants(User $user, Event $event): bool
    {
        if ($user->isAdmin() || $event->organization->user_id === $user->id) {
            return true;
        }

        $member = $event->eventMembers()->where('user_id', $user->id)->first();
        if (! $member) {
            return false;
        }

        return $member->committee_role === 'Finance';
    }
}
