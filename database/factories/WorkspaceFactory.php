<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\workspace;
use App\Models\user_account;

class workspaceFactory extends Factory
{
    protected $model = workspace::class;

    public function definition(): array
    {
        return [
            'user_account_id' => user_account::factory(),
            'name' => $this->faker->company(),
            'type' => $this->faker->randomElement(['personal', 'organization']),
        ];
    }
}

