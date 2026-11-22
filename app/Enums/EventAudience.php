<?php

namespace App\Enums;

enum EventAudience: string
{
    case All = 'ALL';
    case Student = 'STUDENT';
    case Lecturer = 'LECTURER';

    public function label(): string
    {
        return match ($this) {
            self::All => 'Umum',
            self::Student => 'Mahasiswa',
            self::Lecturer => 'Dosen',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::All => 'globe',
            self::Student => 'book-open',
            self::Lecturer => 'briefcase',
        };
    }
}
