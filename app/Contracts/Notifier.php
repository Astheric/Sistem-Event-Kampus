<?php

namespace App\Contracts;

interface Notifier
{
    public function notify(int $userId, string $title, string $message, ?string $link = null): void;
}
