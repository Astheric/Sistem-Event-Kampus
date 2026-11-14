<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'organization_id' => Organization::factory(),
            'category_id' => Category::factory(),
            'venue_id' => Venue::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 9999),
            'banner' => null,
            'description' => fake()->paragraph(),
            'start_time' => now()->addDays(7),
            'end_time' => now()->addDays(7)->addHours(4),
            'capacity' => 100,
            'status' => 'PUBLISHED',
            'rejection_reason' => null,
        ];
    }
}
