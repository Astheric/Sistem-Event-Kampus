<?php

namespace App\Contracts;

interface AuditTrail
{
    public function log(string $action, string $entityType, ?int $entityId = null, ?array $details = null, ?int $userId = null): void;
}
