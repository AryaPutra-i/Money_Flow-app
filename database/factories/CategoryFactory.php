<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\category;
use App\Models\workspace;

class categoryFactory extends Factory
{
    protected $model = category::class;

    public function definition(): array
    {
        return [
            'workspace_id' => workspace::factory(),
            'name_category' => $this->faker->word(),
            'type_category' => $this->faker->randomElement(['income', 'expense']),
        ];
    }
}

