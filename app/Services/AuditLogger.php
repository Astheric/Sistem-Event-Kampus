<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Record an audit log entry.
     *
     * @param  string  $action  e.g., 'event.approved', 'payment.verified', 'checkin.scanned'
     * @param  string  $entityType  e.g., 'Event', 'Payment', 'Registration'
     * @param  array<string, mixed>|null  $details
     */
    public static function log(string $action, string $entityType, ?int $entityId = null, ?array $details = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'ip_address' => Request::ip(),
            'details' => $details,
        ]);
    }
}
