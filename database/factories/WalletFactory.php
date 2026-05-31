<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\wallet;
use App\Models\workspace;

class WalletFactory extends Factory
{
    protected $model = wallet::class;

    public function definition(): array
    {
        return [
            'workspace_id' => workspace::factory(),
            'name' => $this->faker->word(),
            'balance' => $this->faker->randomFloat(2, 0, 100000),
        ];
    }
}
