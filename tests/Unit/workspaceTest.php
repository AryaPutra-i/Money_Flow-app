<?php

namespace Tests\Unit;

use App\Models\workspace;
use App\Models\wallet;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

/**
 * @group unit_tests
 */
class workspaceTest extends TestCase
{
    /**
     * TC-01 (PASS)
     * Memastikan wallets() bertipe HasMany
     */
    public function test_defines_wallets_relation(): void
    {
        $workspace = new workspace();

        $this->assertInstanceOf(
            HasMany::class,
            $workspace->wallets()
        );
    }

    /**
     * TC-02 (PASS)
     * Memastikan relasi mengarah ke model Wallet
     */
    public function test_wallets_relation_targets_wallet_model(): void
    {
        $workspace = new workspace();

        $relation = $workspace->wallets();

        $this->assertEquals(
            wallet::class,
            get_class($relation->getRelated())
        );
    }

    /**
     * TC-03 (FAIL)
     * Sengaja memeriksa foreign key yang salah
     */
    public function test_wallets_relation_uses_wrong_foreign_key(): void
    {
        $workspace = new workspace();

        $relation = $workspace->wallets();

        $this->assertEquals(
            'user_account_id',
            $relation->getForeignKeyName()
        );
    }
}