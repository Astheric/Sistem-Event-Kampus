<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $quota = fake()->numberBetween(10, 100);

        return [
            'event_id' => Event::factory(),
            'name' => fake()->words(2, true),
            'price' => fake()->randomElement([0, 25000, 50000, 100000]),
            'quota' => $quota,
            'available_quota' => $quota,
            'start_sale' => now()->subDays(2),
            'end_sale' => now()->addDays(5),
        ];
    }
}
