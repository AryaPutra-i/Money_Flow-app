<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{
    user_account,
    workspace,
    wallet,
    transaction,
    category,
    budget,
    debt,
    goal,
    GoalSaving,
    SavingInvestasi,
    SplitBill,
    subscriptionTransaction,
    financialHealthScore
};

/**
 * @group unit_tests
 * 
 * Unit test untuk model attributes, fillable, dan casts
 */
class ModelAttributesTest extends TestCase
{
    // ===== USER ACCOUNT ATTRIBUTES =====

    public function test_user_account_fillable_attributes(): void
    {
        $userAccount = new user_account();
        $this->assertEquals(
            ['name', 'email', 'password'],
            $userAccount->getFillable()
        );
    }

    public function test_user_account_hidden_attributes(): void
    {
        $userAccount = new user_account();
        $this->assertContains('password', $userAccount->getHidden());
    }

    // ===== WORKSPACE ATTRIBUTES =====

    public function test_workspace_fillable_attributes(): void
    {
        $workspace = new workspace();
        $this->assertEquals(
            ['user_account_id', 'name', 'type'],
            $workspace->getFillable()
        );
    }

    // ===== WALLET ATTRIBUTES =====

    public function test_wallet_fillable_attributes(): void
    {
        $wallet = new wallet();
        $this->assertEquals(
            ['workspace_id', 'name', 'balance'],
            $wallet->getFillable()
        );
    }

    public function test_wallet_balance_is_numeric(): void
    {
        $wallet = new wallet(['balance' => 100000.50]);
        $this->assertEquals(100000.50, $wallet->balance);
    }

    // ===== TRANSACTION ATTRIBUTES =====

    public function test_transaction_fillable_attributes(): void
    {
        $transaction = new transaction();
        $expected = [
            'workspace_id',
            'wallet_id',
            'category_id',
            'amount',
            'type',
            'transaction_date',
            'proof_path',
            'is_recurring'
        ];
        $this->assertEquals($expected, $transaction->getFillable());
    }

    public function test_transaction_amount_cast_to_decimal(): void
    {
        $transaction = new transaction(['amount' => 100000.50]);
        $this->assertEquals('100000.50', (string)$transaction->amount);
    }

    public function test_transaction_date_cast_to_date(): void
    {
        $transaction = new transaction(['transaction_date' => '2024-07-17']);
        $this->assertIsObject($transaction->transaction_date);
        $this->assertEquals('2024-07-17', $transaction->transaction_date->toDateString());
    }

    public function test_transaction_is_recurring_boolean_cast(): void
    {
        $transaction = new transaction(['is_recurring' => true]);
        $this->assertTrue($transaction->is_recurring);
        $this->assertIsBool($transaction->is_recurring);
    }

    // ===== CATEGORY ATTRIBUTES =====

    public function test_category_fillable_attributes(): void
    {
        $category = new category();
        $this->assertEquals(
            ['workspace_id', 'name_category', 'type_category'],
            $category->getFillable()
        );
    }

    // ===== BUDGET ATTRIBUTES =====

    public function test_budget_fillable_attributes(): void
    {
        $budget = new budget();
        $this->assertEquals(
            ['workspace_id', 'category_id', 'limit_amount', 'moonth_year'],
            $budget->getFillable()
        );
    }

    public function test_budget_limit_amount_cast_to_decimal(): void
    {
        $budget = new budget(['limit_amount' => 500000.00]);
        $this->assertEquals('500000.00', (string)$budget->limit_amount);
    }

    // ===== DEBT ATTRIBUTES =====

    public function test_debt_fillable_attributes(): void
    {
        $debt = new debt();
        $this->assertEquals(
            ['workspace_id', 'type', 'person_name', 'amount', 'status'],
            $debt->getFillable()
        );
    }

    public function test_debt_amount_cast_to_decimal(): void
    {
        $debt = new debt(['amount' => 250000.00]);
        $this->assertEquals('250000.00', (string)$debt->amount);
    }

    // ===== GOAL ATTRIBUTES =====

    public function test_goal_fillable_attributes(): void
    {
        $goal = new goal();
        $this->assertEquals(
            ['workspace_id', 'Deskripsi', 'target_amount', 'current_amount'],
            $goal->getFillable()
        );
    }

    public function test_goal_target_amount_cast_to_decimal(): void
    {
        $goal = new goal(['target_amount' => 10000000.00]);
        $this->assertEquals('10000000.00', (string)$goal->target_amount);
    }

    public function test_goal_current_amount_cast_to_decimal(): void
    {
        $goal = new goal(['current_amount' => 2000000.00]);
        $this->assertEquals('2000000.00', (string)$goal->current_amount);
    }

    // ===== GOAL SAVING ATTRIBUTES =====

    public function test_goal_saving_fillable_attributes(): void
    {
        $goalSaving = new GoalSaving();
        $this->assertEquals(
            ['goal_id', 'wallet_id', 'amount', 'date', 'notes'],
            $goalSaving->getFillable()
        );
    }

    public function test_goal_saving_amount_cast_to_decimal(): void
    {
        $goalSaving = new GoalSaving(['amount' => 500000.00]);
        $this->assertEquals('500000.00', (string)$goalSaving->amount);
    }

    public function test_goal_saving_date_cast_to_date(): void
    {
        $goalSaving = new GoalSaving(['date' => '2024-07-17']);
        $this->assertIsObject($goalSaving->date);
        $this->assertEquals('2024-07-17', $goalSaving->date->toDateString());
    }

    // ===== SAVING INVESTASI ATTRIBUTES =====

    public function test_saving_investasi_fillable_attributes(): void
    {
        $savingInvestasi = new SavingInvestasi();
        $expected = [
            'workspace_id',
            'wallet_id',
            'intrumen',
            'nama_instrumen',
            'nominal_modal',
            'estimasi_return',
            'tanggal_mulai',
            'tanggal_jatuh_tempo',
            'status',
        ];
        $this->assertEquals($expected, $savingInvestasi->getFillable());
    }

    public function test_saving_investasi_nominal_modal_cast_to_decimal(): void
    {
        $savingInvestasi = new SavingInvestasi(['nominal_modal' => 50000000.00]);
        $this->assertEquals('50000000.00', (string)$savingInvestasi->nominal_modal);
    }

    public function test_saving_investasi_tanggal_mulai_cast_to_date(): void
    {
        $savingInvestasi = new SavingInvestasi(['tanggal_mulai' => '2024-01-01']);
        $this->assertIsObject($savingInvestasi->tanggal_mulai);
        $this->assertEquals('2024-01-01', $savingInvestasi->tanggal_mulai->toDateString());
    }

    // ===== SPLIT BILL ATTRIBUTES =====

    public function test_split_bill_fillable_attributes(): void
    {
        $splitBill = new SplitBill();
        $this->assertEquals(
            ['transaction_id', 'amount', 'status'],
            $splitBill->getFillable()
        );
    }

    public function test_split_bill_amount_cast_to_decimal(): void
    {
        $splitBill = new SplitBill(['amount' => 100000.00]);
        $this->assertEquals('100000.00', (string)$splitBill->amount);
    }

    // ===== SUBSCRIPTION TRANSACTION ATTRIBUTES =====

    public function test_subscription_transaction_fillable_attributes(): void
    {
        $subTx = new subscriptionTransaction();
        $expected = [
            'workspace_id',
            'wallet_id',
            'category_id',
            'nama_transaksi',
            'nominal',
            'frekuensi',
            'tanggal_mulai',
            'tanggal_eksekusi_berikutnya',
        ];
        $this->assertEquals($expected, $subTx->getFillable());
    }

    public function test_subscription_transaction_nominal_cast_to_decimal(): void
    {
        $subTx = new subscriptionTransaction(['nominal' => 200000]);
        $this->assertEquals('200000.00', (string)$subTx->nominal);
    }

    public function test_subscription_transaction_tanggal_mulai_cast_to_date(): void
    {
        $subTx = new subscriptionTransaction(['tanggal_mulai' => '2024-07-01']);
        $this->assertIsObject($subTx->tanggal_mulai);
        $this->assertEquals('2024-07-01', $subTx->tanggal_mulai->toDateString());
    }

    // ===== FINANCIAL HEALTH SCORE ATTRIBUTES =====

    public function test_financial_health_score_has_workspace_id_and_score(): void
    {
        $score = new financialHealthScore();
        $this->assertContains('workspace_id', $score->getFillable());
        $this->assertContains('score', $score->getFillable());
    }
}
