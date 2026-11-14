<?php

namespace App\Infrastructure;

use App\Contracts\Notifier;
use App\Models\Notification;

class DatabaseNotifier implements Notifier
{
    public function notify(int $userId, string $title, string $message, ?string $link = null): void
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
        ]);
    }
}
