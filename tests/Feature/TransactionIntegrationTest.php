<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\transaction;
use App\Models\SplitBill;

/**
 * @group feature_tests
 */
class TransactionIntegrationTest extends TestCase
{
    public function test_transaksi_memiliki_banyak_split_bill()
    {
        $transaction = new transaction([
            'amount' => 100000
        ]);

        $splitBill1 = new SplitBill();
        $splitBill2 = new SplitBill();

        $transaction->setRelation(
            'splitBills',
            collect([$splitBill1, $splitBill2])
        );

        $this->assertCount(2, $transaction->splitBills);
    }

    public function test_total_split_bill_sesuai_nominal_transaksi()
    {
        $transaction = new transaction([
            'amount' => 100000
        ]);

        $splitBill1 = new SplitBill([
            'amount' => 50000
        ]);

        $splitBill2 = new SplitBill([
            'amount' => 50000
        ]);

        $transaction->setRelation(
            'splitBills',
            collect([$splitBill1, $splitBill2])
        );

        $totalSplitBill = $transaction->splitBills->sum('amount');

        $this->assertEquals(
            $transaction->amount,
            $totalSplitBill
        );
    }
}