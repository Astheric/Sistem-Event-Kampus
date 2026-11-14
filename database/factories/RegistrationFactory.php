<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'registration_code' => 'EVT-2026-'.strtoupper(Str::random(6)),
            'status' => 'PENDING',
            'notes' => null,
        ];
    }
}
