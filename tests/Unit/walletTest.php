<?php

namespace Tests\Unit;

use App\Models\wallet;
use App\Models\transaction;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

/**
 * @group unit_tests
 */
class walletTest extends TestCase
{
    /**
     * TC-01
     * Memastikan transactions() bertipe HasMany
     */
    public function test_defines_transactions_relation(): void
    {
        $wallet = new wallet();

        $this->assertInstanceOf(
            HasMany::class,
            $wallet->transactions()
        );
    }

    /**
     * TC-02
     * Memastikan relasi mengarah ke model Transaction
     */
    public function test_transactions_relation_targets_transaction_model(): void
    {
        $wallet = new wallet();

        $relation = $wallet->transactions();

        $this->assertEquals(
            transaction::class,
            get_class($relation->getRelated())
        );
    }

    /**
     * TC-03
     * Memastikan foreign key yang digunakan adalah wallet_id
     */
    public function test_transactions_relation_uses_wallet_id_foreign_key(): void
    {
        $wallet = new wallet();

        $relation = $wallet->transactions();

        $this->assertEquals(
            'wallet_id',
            $relation->getForeignKeyName()
        );
    }

    /**
     * TC-04 (FAIL)
     * Sengaja memeriksa foreign key yang salah
     */
    public function test_transactions_relation_uses_wrong_foreign_key(): void
    {
        $wallet = new wallet();

        $relation = $wallet->transactions();

        $this->assertEquals(
            'workspace_id',
            $relation->getForeignKeyName()
        );
    }
}