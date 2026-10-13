<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'event_id',
        'registration_code',
        'status',
        'notes',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function checkin(): HasOne
    {
        return $this->hasOne(Checkin::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'CONFIRMED';
    }

    public function isAttended(): bool
    {
        return $this->status === 'ATTENDED';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'CANCELLED';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'COMPLETED';
    }
}
