<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group feature_tests
 */
class WalletIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * IT-01
     * Memastikan relasi wallet ke workspace menggunakan foreign key workspace_id
     */
    public function test_wallet_uses_workspace_id_foreign_key(): void
    {
        $wallet = new wallet();

        $relation = $wallet->workspace();

        $this->assertEquals(
            'workspace_id',
            $relation->getForeignKeyName()
        );
    }

    /**
     * IT-02
     * Memastikan relasi transactions() bertipe HasMany
     */
    public function test_wallet_has_many_transactions(): void
    {
        $wallet = new wallet();

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\HasMany::class,
            $wallet->transactions()
        );
    }
}