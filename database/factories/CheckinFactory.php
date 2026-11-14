<?php

namespace Database\Factories;

use App\Models\Checkin;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Checkin>
 */
class CheckinFactory extends Factory
{
    protected $model = Checkin::class;

    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'scanned_by' => User::factory(),
            'checked_in_at' => now(),
        ];
    }
}
