<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\goal;
use App\Models\workspace;

class GoalFactory extends Factory
{
    protected $model = goal::class;

    public function definition(): array
    {
        $target = $this->faker->randomFloat(2, 500, 20000);

        return [
            'workspace_id' => workspace::factory(),
            'Deskripsi' => $this->faker->sentence(),
            'target_amount' => $target,
            'current_amount' => $this->faker->randomFloat(2, 0, $target),
        ];
    }
}
