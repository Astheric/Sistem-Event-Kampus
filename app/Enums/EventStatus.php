<?php

namespace App\Enums;

enum EventStatus: string
{
    case Draft = 'DRAFT';
    case Submitted = 'SUBMITTED';
    case Published = 'PUBLISHED';
    case Rejected = 'REJECTED';
    case Completed = 'COMPLETED';
    case Cancelled = 'CANCELLED';

    /**
     * @return array<string, list<self>>
     */
    public static function allowedTransitions(): array
    {
        return [
            self::Draft->value => [self::Submitted, self::Cancelled],
            self::Submitted->value => [self::Published, self::Rejected, self::Cancelled],
            self::Rejected->value => [self::Submitted, self::Cancelled],
            self::Published->value => [self::Completed, self::Cancelled],
            self::Completed->value => [],
            self::Cancelled->value => [],
        ];
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, self::allowedTransitions()[$this->value] ?? [], true);
    }

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Diajukan',
            self::Published => 'Terbit',
            self::Rejected => 'Ditolak',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }
}
