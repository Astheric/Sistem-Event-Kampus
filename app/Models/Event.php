<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'category_id',
        'venue_id',
        'title',
        'slug',
        'banner',
        'description',
        'start_time',
        'end_time',
        'capacity',
        'status',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function eventMembers(): HasMany
    {
        return $this->hasMany(EventMember::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'PUBLISHED');
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) ($this->reviews()->avg('rating') ?: 0);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getMinPriceAttribute(): float
    {
        return (float) ($this->tickets()->min('price') ?? 0);
    }

    public function getMaxPriceAttribute(): float
    {
        return (float) ($this->tickets()->max('price') ?? 0);
    }

    public function isFree(): bool
    {
        return $this->min_price == 0 && $this->max_price == 0;
    }
}
