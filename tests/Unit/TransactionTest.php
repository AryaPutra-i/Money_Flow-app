<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\transaction;

/**
 * @group unit_tests
 */
class TransactionTest extends TestCase
{
    public function test_data_transaksi(): void
    {
        $transaction = new transaction();

        $this->assertEquals(
            [
                'workspace_id',
                'wallet_id',
                'category_id',
                'amount',
                'type',
                'transaction_date',
                'proof_path',
                'is_recurring'
            ],
            $transaction->getFillable()
        );
    }

    public function test_nominal_transaksi(): void
    {
        $transaction = new transaction([
            'amount' => 100000
        ]);

        $this->assertEquals(100000, $transaction->amount);
    }

    public function test_transaksi_berulang(): void
    {
        $transaction = new transaction([
            'is_recurring' => true
        ]);

        $this->assertTrue($transaction->is_recurring);
    }
}