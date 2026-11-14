<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'action' => 'test.action',
            'entity_type' => 'Test',
            'entity_id' => 1,
            'ip_address' => '127.0.0.1',
            'details' => ['key' => 'value'],
        ];
    }
}
