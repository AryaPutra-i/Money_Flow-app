<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SplitBill;

/**
 * @group unit_tests
 */
class SplitBillTest extends TestCase
{
    public function test_data_split_bill(): void
    {
        $splitBill = new SplitBill();

        $this->assertEquals(
            ['transaction_id', 'amount', 'status'],
            $splitBill->getFillable()
        );
    }

    public function test_nominal_tagihan_tersimpan(): void
    {
        $splitBill = new SplitBill([
            'amount' => 50000
        ]);

        $this->assertEquals(50000, $splitBill->amount);
    }

    public function test_status_aktif(): void
    {
        $splitBill = new SplitBill([
            'status' => 'active'
        ]);

        $this->assertEquals('active', $splitBill->status);
    }
}