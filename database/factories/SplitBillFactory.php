<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SplitBill;
use App\Models\transaction;

class SplitBillFactory extends Factory
{
    protected $model = SplitBill::class;

    public function definition(): array
    {
        return [
            'transaction_id' => transaction::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'status' => $this->faker->randomElement(['pending', 'completed']),
        ];
    }
}
