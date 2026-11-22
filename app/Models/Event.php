<?php

namespace App\Models;

use App\Support\Capacity;
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
        'audience',
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

    public function scopeWithSeatCount(Builder $query): Builder
    {
        return $query->withCount([
            'registrations as registered_count' => function ($q) {
                $q->whereIn('status', ['PENDING', 'CONFIRMED', 'ATTENDED', 'COMPLETED']);
            },
        ]);
    }

    public function capacity(): Capacity
    {
        return Capacity::forEvent($this);
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

    public function bannerUrl(): string
    {
        if ($this->banner) {
            if (str_starts_with($this->banner, 'http://') || str_starts_with($this->banner, 'https://')) {
                return $this->banner;
            }

            return asset('storage/'.$this->banner);
        }

        $categorySlug = strtolower($this->category?->slug ?? '');
        $title = strtolower($this->title ?? '');

        if (str_contains($categorySlug, 'workshop') || str_contains($title, 'workshop') || str_contains($title, 'bootcamp')) {
            return 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_contains($categorySlug, 'kompetisi') || str_contains($title, 'hackathon') || str_contains($title, 'lomba')) {
            return 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_contains($categorySlug, 'festival') || str_contains($categorySlug, 'seni') || str_contains($title, 'musik')) {
            return 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_contains($categorySlug, 'expo') || str_contains($title, 'karir') || str_contains($title, 'job')) {
            return 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_contains($categorySlug, 'webinar') || str_contains($title, 'online')) {
            return 'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_contains($title, 'ai') || str_contains($title, 'cyber') || str_contains($title, 'tech')) {
            return 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80';
        }

        if (str_contains($title, 'pemimpin') || str_contains($title, 'etika') || str_contains($categorySlug, 'seminar')) {
            return 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80';
        }

        $fallbacks = [
            'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=1200&q=80',
        ];

        return $fallbacks[($this->id ?? 1) % count($fallbacks)];
    }
}
