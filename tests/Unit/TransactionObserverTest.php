<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{wallet, transaction, workspace, category, user_account};
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group unit_tests
 * 
 * Unit test untuk TransactionObserver
 * Memastikan balance wallet terupdate otomatis saat transaksi dibuat, diupdate, atau dihapus
 */
class TransactionObserverTest extends TestCase
{
    use RefreshDatabase;

    private user_account $userAccount;
    private workspace $workspace;
    private wallet $wallet;
    private category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccount = user_account::factory()->create();
        $this->workspace = workspace::factory()->for($this->userAccount)->create();
        $this->wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);
        $this->category = category::factory()->for($this->workspace)->create(['type_category' => 'expense']);
    }

    // ===== INCOME TRANSACTION =====

    public function test_income_transaction_increases_wallet_balance(): void
    {
        $initialBalance = $this->wallet->balance;

        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create([
                'amount' => 500000,
                'type' => 'income',
            ]);

        $this->wallet->refresh();
        $this->assertEquals(
            $initialBalance + 500000,
            $this->wallet->balance
        );
    }

    // ===== EXPENSE TRANSACTION =====

    public function test_expense_transaction_decreases_wallet_balance(): void
    {
        $initialBalance = $this->wallet->balance;

        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create([
                'amount' => 200000,
                'type' => 'expense',
            ]);

        $this->wallet->refresh();
        $this->assertEquals(
            $initialBalance - 200000,
            $this->wallet->balance
        );
    }

    // ===== TRANSFER TRANSACTION =====

    public function test_transfer_transaction_decreases_wallet_balance(): void
    {
        $initialBalance = $this->wallet->balance;

        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create([
                'amount' => 300000,
                'type' => 'transfer',
            ]);

        $this->wallet->refresh();
        $this->assertEquals(
            $initialBalance - 300000,
            $this->wallet->balance
        );
    }

    // ===== UPDATE TRANSACTION =====

    public function test_update_transaction_amount_adjusts_wallet_balance(): void
    {
        $txn = transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create([
                'amount' => 100000,
                'type' => 'expense',
            ]);

        $initialBalance = $this->wallet->balance;

        // Update transaction amount
        $txn->update(['amount' => 200000]);

        $this->wallet->refresh();
        // Balance should be adjusted: reverted by 100000 + new deduction of 200000
        $this->assertEquals(
            $initialBalance - 100000,
            $this->wallet->balance
        );
    }

    public function test_update_transaction_type_from_expense_to_income(): void
    {
        $txn = transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create([
                'amount' => 100000,
                'type' => 'expense',
            ]);

        $initialBalance = $this->wallet->balance;

        // Change type from expense to income
        $txn->update(['type' => 'income']);

        $this->wallet->refresh();
        // Should add back the 100000 (revert expense) + add 100000 (income) = +200000
        $this->assertEquals(
            $initialBalance + 200000,
            $this->wallet->balance
        );
    }

    public function test_change_transaction_wallet(): void
    {
        $wallet2 = wallet::factory()->for($this->workspace)->create(['balance' => 500000]);

        $txn = transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create([
                'amount' => 100000,
                'type' => 'expense',
            ]);

        $wallet1InitialBalance = $this->wallet->balance;
        $wallet2InitialBalance = $wallet2->balance;

        // Move transaction to wallet2
        $txn->update(['wallet_id' => $wallet2->id]);

        $this->wallet->refresh();
        $wallet2->refresh();

        // Wallet1 should add back the 100000 (revert)
        $this->assertEquals(
            $wallet1InitialBalance + 100000,
            $this->wallet->balance
        );

        // Wallet2 should deduct 100000 (new expense)
        $this->assertEquals(
            $wallet2InitialBalance - 100000,
            $wallet2->balance
        );
    }

    // ===== DELETE TRANSACTION =====

    public function test_delete_income_transaction_decreases_wallet_balance(): void
    {
        $txn = transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create([
                'amount' => 500000,
                'type' => 'income',
            ]);

        $balanceAfterIncome = $this->wallet->balance;

        $txn->delete();
        $this->wallet->refresh();

        // Balance should be reverted back
        $this->assertEquals(
            $balanceAfterIncome - 500000,
            $this->wallet->balance
        );
    }

    public function test_delete_expense_transaction_increases_wallet_balance(): void
    {
        $txn = transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create([
                'amount' => 200000,
                'type' => 'expense',
            ]);

        $balanceAfterExpense = $this->wallet->balance;

        $txn->delete();
        $this->wallet->refresh();

        // Balance should be reverted back (money returned)
        $this->assertEquals(
            $balanceAfterExpense + 200000,
            $this->wallet->balance
        );
    }

    // ===== MULTIPLE TRANSACTIONS =====

    public function test_multiple_transactions_update_balance_correctly(): void
    {
        $initialBalance = $this->wallet->balance;

        // First income
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['amount' => 500000, 'type' => 'income']);

        $this->wallet->refresh();
        $this->assertEquals($initialBalance + 500000, $this->wallet->balance);

        // Then expense
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['amount' => 200000, 'type' => 'expense']);

        $this->wallet->refresh();
        $this->assertEquals($initialBalance + 500000 - 200000, $this->wallet->balance);

        // Another expense
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['amount' => 100000, 'type' => 'expense']);

        $this->wallet->refresh();
        $this->assertEquals($initialBalance + 500000 - 200000 - 100000, $this->wallet->balance);
    }

    // ===== NULL WALLET HANDLING =====

    public function test_transaction_with_null_wallet_id_does_not_crash(): void
    {
        $txn = transaction::factory()
            ->for($this->workspace)
            ->for($this->category)
            ->create([
                'wallet_id' => null,
                'amount' => 100000,
                'type' => 'income',
            ]);

        $this->assertNull($txn->wallet_id);
        $this->wallet->refresh();
        $this->assertEquals(1000000, $this->wallet->balance); // unchanged
    }
}
