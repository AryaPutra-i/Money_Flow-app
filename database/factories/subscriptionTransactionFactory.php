<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\subscriptionTransaction;
use App\Models\workspace;
use App\Models\wallet;
use App\Models\category;

class subscriptionTransactionFactory extends Factory
{
    protected $model = subscriptionTransaction::class;

    public function definition(): array
    {
        return [
            'workspace_id' => workspace::factory(),
            'wallet_id' => wallet::factory(),
            'category_id' => category::factory(),
            'nama_transaksi' => $this->faker->words(2, true),
            'nominal' => $this->faker->randomFloat(2, 10000, 1000000),
            'frekuensi' => $this->faker->randomElement(['daily', 'weekly', 'monthly', 'yearly']),
            'tanggal_mulai' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'tanggal_eksekusi_berikutnya' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
