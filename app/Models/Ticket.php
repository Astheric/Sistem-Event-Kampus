<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quota',
        'available_quota',
        'start_sale',
        'end_sale',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'start_sale' => 'datetime',
            'end_sale' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function isAvailable(): bool
    {
        $now = Carbon::now();

        return $this->available_quota > 0 &&
            $now->gte($this->start_sale) &&
            $now->lte($this->end_sale);
    }

    public function isSoldOut(): bool
    {
        return $this->available_quota <= 0;
    }

    public function isSaleEnded(): bool
    {
        return Carbon::now()->gt($this->end_sale);
    }

    public function isSaleNotStarted(): bool
    {
        return Carbon::now()->lt($this->start_sale);
    }
}
