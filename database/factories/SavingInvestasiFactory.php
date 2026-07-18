<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SavingInvestasi;
use App\Models\workspace;
use App\Models\wallet;

class SavingInvestasiFactory extends Factory
{
    protected $model = SavingInvestasi::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'workspace_id' => workspace::factory(),
            'wallet_id' => wallet::factory(),
            'intrumen' => $this->faker->randomElement(['saham', 'obligasi', 'reksa dana', 'emas', 'properti', 'lainnya']),
            'nama_instrumen' => $this->faker->word(),
            'nominal_modal' => $this->faker->randomFloat(2, 1000000, 100000000),
            'estimasi_return' => $this->faker->randomFloat(2, 100000, 50000000),
            'tanggal_mulai' => $startDate,
            'tanggal_jatuh_tempo' => $this->faker->dateTimeAfter($startDate),
            'status' => $this->faker->randomElement(['aktif', 'selesai', 'jual']),
        ];
    }
}
