<?php

namespace App\Infrastructure;

use App\Contracts\AuditTrail;
use App\Models\AuditLog;

class DatabaseAuditTrail implements AuditTrail
{
    public function log(string $action, string $entityType, ?int $entityId = null, ?array $details = null, ?int $userId = null): void
    {
        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip_address' => request()?->ip(),
            'details' => $details,
        ]);
    }
}
