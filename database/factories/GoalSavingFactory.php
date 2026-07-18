<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\GoalSaving;
use App\Models\goal;
use App\Models\wallet;

class GoalSavingFactory extends Factory
{
    protected $model = GoalSaving::class;

    public function definition(): array
    {
        return [
            'goal_id' => goal::factory(),
            'wallet_id' => wallet::factory(),
            'amount' => $this->faker->randomFloat(2, 100000, 5000000),
            'date' => $this->faker->dateTimeBetweenStart: '-30 days',
            'notes' => $this->faker->sentence(),
        ];
    }
}
