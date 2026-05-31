<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\debt;
use App\Models\workspace;

class DebtFactory extends Factory
{
    protected $model = debt::class;

    public function definition(): array
    {
        return [
            'workspace_id' => workspace::factory(),
            'type' => $this->faker->randomElement(['debt', 'receivable']),
            'person_name' => $this->faker->name(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(['unpaid', 'paid']),
        ];
    }
}
