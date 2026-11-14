<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Organizer = 'organizer';
    case Participant = 'participant';
    case Lecturer = 'dosen';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Organizer => 'Organizer',
            self::Participant => 'Mahasiswa',
            self::Lecturer => 'Dosen',
        };
    }

    public function isLecturer(): bool
    {
        return $this === self::Lecturer;
    }
}
