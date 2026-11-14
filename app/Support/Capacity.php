<?php

namespace App\Support;

use App\Enums\CapacityState;
use App\Enums\EventStatus;
use App\Models\Event;

readonly class Capacity
{
    public function __construct(
        public int $total,
        public int $filled,
        public int $remaining,
        public int $percent,
        public CapacityState $state,
    ) {}

    public static function forEvent(Event $event): self
    {
        $filled = $event->registered_count ?? $event->registrations()
            ->whereIn('status', ['PENDING', 'CONFIRMED', 'ATTENDED', 'COMPLETED'])
            ->count();

        $total = (int) $event->capacity;
        $remaining = max(0, $total - $filled);
        $percent = $total > 0 ? (int) round(($filled / $total) * 100) : 0;

        $status = $event->status instanceof EventStatus ? $event->status : EventStatus::tryFrom((string) $event->status);
        if ($status === EventStatus::Cancelled || $status === EventStatus::Completed) {
            $state = CapacityState::Closed;
        } elseif ($total <= 0 || $remaining <= 0) {
            $state = CapacityState::Full;
        } elseif ($percent >= 80) {
            $state = CapacityState::AlmostFull;
        } else {
            $state = CapacityState::Available;
        }

        return new self($total, $filled, $remaining, $percent, $state);
    }
}
