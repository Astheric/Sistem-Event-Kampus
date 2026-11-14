<?php

namespace App\Enums;

enum CommitteeDivision: string
{
    case Registration = 'Registration';
    case Finance = 'Finance';
    case Documentation = 'Documentation';

    public function label(): string
    {
        return match ($this) {
            self::Registration => 'Registrasi',
            self::Finance => 'Keuangan',
            self::Documentation => 'Dokumentasi',
        };
    }
}
