<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SplitBillsParticipant;

/**
 * @group unit_tests
 */
class SplitBillsParticipantTest extends TestCase
{
    public function test_pembagian_tagihan(): void
    {
        $participant = new SplitBillsParticipant();

        $this->assertEquals(
            ['split_bill_id', 'friend_name', 'amount_due', 'is_paid'],
            $participant->getFillable()
        );
    }

    public function test_nominal_tagihan_tidak_negatif(): void
    {
        $participant = new SplitBillsParticipant([
            'amount_due' => -10000
        ]);

        $this->assertLessThan(0, $participant->amount_due);
    }

    public function test_status_sudah_dibayar(): void
    {
        $participant = new SplitBillsParticipant([
            'is_paid' => true
        ]);

        $this->assertTrue($participant->is_paid);
    }
}