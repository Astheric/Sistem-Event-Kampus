<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'registration_id' => Registration::factory(),
            'payment_method' => 'Bank Transfer',
            'amount' => 50000,
            'proof_file' => null,
            'status' => 'PENDING',
            'verified_at' => null,
            'verified_by' => null,
            'notes' => null,
        ];
    }
}
