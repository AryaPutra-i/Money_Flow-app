<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SplitBill;
use App\Models\SplitBillsParticipant;

class SplitBillsParticipantFactory extends Factory
{
    protected $model = SplitBillsParticipant::class;

    public function definition(): array
    {
        return [
            'split_bill_id' => SplitBill::factory(),
            'friend_name' => $this->faker->name(),
            'amount_due' => $this->faker->randomFloat(2, 10, 1000),
            'is_paid' => $this->faker->boolean(30),
        ];
    }
}
