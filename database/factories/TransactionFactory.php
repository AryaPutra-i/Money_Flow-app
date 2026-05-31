<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\transaction;
use App\Models\workspace;
use App\Models\wallet;
use App\Models\category;

class TransactionFactory extends Factory
{
    protected $model = transaction::class;

    public function definition(): array
    {
        $workspace = workspace::factory();

        return [
            'workspace_id' => $workspace,
            'wallet_id' => wallet::factory()->for($workspace),
            'category_id' => category::factory()->for($workspace),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'type' => $this->faker->randomElement(['income', 'expense', 'transfer']),
            'transaction_date' => $this->faker->date(),
            'proof_path' => null,
            'is_recurring' => $this->faker->boolean(20),
        ];
    }
}
