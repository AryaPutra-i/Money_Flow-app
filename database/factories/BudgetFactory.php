<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\budget;
use App\Models\workspace;
use App\Models\category;

class budgetFactory extends Factory
{
    protected $model = budget::class;

    public function definition(): array
    {
        return [
            'workspace_id' => workspace::factory(),
            'category_id' => category::factory(),
            'limit_amount' => $this->faker->randomFloat(2, 0, 10000),
            'moonth_year' => $this->faker->date(),
        ];
    }
}

