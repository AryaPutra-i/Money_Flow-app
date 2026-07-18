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
    SplitBillsParticipant,
    subscriptionTransaction,
    financialHealthScore
};
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsTo};

/**
 * @group unit_tests
 * 
 * Unit test untuk semua model relationships
 * Memastikan relasi antar model sudah benar
 */
class ModelRelationshipsTest extends TestCase
{
    // ===== USER ACCOUNT RELATIONSHIPS =====
    
    public function test_user_account_has_many_workspaces(): void
    {
        $userAccount = new user_account();
        $this->assertInstanceOf(HasMany::class, $userAccount->workspaces());
    }

    public function test_user_account_workspaces_targets_workspace_model(): void
    {
        $userAccount = new user_account();
        $relation = $userAccount->workspaces();
        $this->assertEquals(workspace::class, get_class($relation->getRelated()));
    }

    // ===== WORKSPACE RELATIONSHIPS =====

    public function test_workspace_belongs_to_user_account(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(BelongsTo::class, $workspace->userAccount());
    }

    public function test_workspace_has_many_wallets(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(HasMany::class, $workspace->wallets());
    }

    public function test_workspace_has_many_categories(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(HasMany::class, $workspace->categories());
    }

    public function test_workspace_has_many_transactions(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(HasMany::class, $workspace->transactions());
    }

    public function test_workspace_has_many_budgets(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(HasMany::class, $workspace->budgets());
    }

    public function test_workspace_has_many_debts(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(HasMany::class, $workspace->debts());
    }

    public function test_workspace_has_many_goals(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(HasMany::class, $workspace->goals());
    }

    public function test_workspace_has_many_saving_investasis(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(HasMany::class, $workspace->savingInvestasis());
    }

    public function test_workspace_has_many_subscription_transactions(): void
    {
        $workspace = new workspace();
        $this->assertInstanceOf(HasMany::class, $workspace->subscriptionTransactions());
    }

    // ===== WALLET RELATIONSHIPS =====

    public function test_wallet_belongs_to_workspace(): void
    {
        $wallet = new wallet();
        $this->assertInstanceOf(BelongsTo::class, $wallet->workspace());
    }

    public function test_wallet_has_many_transactions(): void
    {
        $wallet = new wallet();
        $this->assertInstanceOf(HasMany::class, $wallet->transactions());
    }

    public function test_wallet_has_many_goal_savings(): void
    {
        $wallet = new wallet();
        $this->assertInstanceOf(HasMany::class, $wallet->goalSavings());
    }

    public function test_wallet_has_many_saving_investasis(): void
    {
        $wallet = new wallet();
        $this->assertInstanceOf(HasMany::class, $wallet->savingInvestasis());
    }

    // ===== TRANSACTION RELATIONSHIPS =====

    public function test_transaction_belongs_to_workspace(): void
    {
        $transaction = new transaction();
        $this->assertInstanceOf(BelongsTo::class, $transaction->workspace());
    }

    public function test_transaction_belongs_to_wallet(): void
    {
        $transaction = new transaction();
        $this->assertInstanceOf(BelongsTo::class, $transaction->wallet());
    }

    public function test_transaction_belongs_to_category(): void
    {
        $transaction = new transaction();
        $this->assertInstanceOf(BelongsTo::class, $transaction->category());
    }

    public function test_transaction_has_many_split_bills(): void
    {
        $transaction = new transaction();
        $this->assertInstanceOf(HasMany::class, $transaction->splitBills());
    }

    // ===== CATEGORY RELATIONSHIPS =====

    public function test_category_belongs_to_workspace(): void
    {
        $category = new category();
        $this->assertInstanceOf(BelongsTo::class, $category->workspace());
    }

    public function test_category_has_many_transactions(): void
    {
        $category = new category();
        $this->assertInstanceOf(HasMany::class, $category->transactions());
    }

    public function test_category_has_many_budgets(): void
    {
        $category = new category();
        $this->assertInstanceOf(HasMany::class, $category->budgets());
    }

    // ===== BUDGET RELATIONSHIPS =====

    public function test_budget_belongs_to_workspace(): void
    {
        $budget = new budget();
        $this->assertInstanceOf(BelongsTo::class, $budget->workspace());
    }

    public function test_budget_belongs_to_category(): void
    {
        $budget = new budget();
        $this->assertInstanceOf(BelongsTo::class, $budget->category());
    }

    // ===== DEBT RELATIONSHIPS =====

    public function test_debt_belongs_to_workspace(): void
    {
        $debt = new debt();
        $this->assertInstanceOf(BelongsTo::class, $debt->workspace());
    }

    // ===== GOAL RELATIONSHIPS =====

    public function test_goal_belongs_to_workspace(): void
    {
        $goal = new goal();
        $this->assertInstanceOf(BelongsTo::class, $goal->workspace());
    }

    public function test_goal_has_many_goal_savings(): void
    {
        $goal = new goal();
        $this->assertInstanceOf(HasMany::class, $goal->goalSavings());
    }

    // ===== GOAL SAVING RELATIONSHIPS =====

    public function test_goal_saving_belongs_to_goal(): void
    {
        $goalSaving = new GoalSaving();
        $this->assertInstanceOf(BelongsTo::class, $goalSaving->goal());
    }

    public function test_goal_saving_belongs_to_wallet(): void
    {
        $goalSaving = new GoalSaving();
        $this->assertInstanceOf(BelongsTo::class, $goalSaving->wallet());
    }

    // ===== SAVING INVESTASI RELATIONSHIPS =====

    public function test_saving_investasi_belongs_to_workspace(): void
    {
        $savingInvestasi = new SavingInvestasi();
        $this->assertInstanceOf(BelongsTo::class, $savingInvestasi->workspace());
    }

    public function test_saving_investasi_belongs_to_wallet(): void
    {
        $savingInvestasi = new SavingInvestasi();
        $this->assertInstanceOf(BelongsTo::class, $savingInvestasi->wallet());
    }

    // ===== SPLIT BILL RELATIONSHIPS =====

    public function test_split_bill_belongs_to_transaction(): void
    {
        $splitBill = new SplitBill();
        $this->assertInstanceOf(BelongsTo::class, $splitBill->transaction());
    }

    public function test_split_bill_has_many_participants(): void
    {
        $splitBill = new SplitBill();
        $this->assertInstanceOf(HasMany::class, $splitBill->participants());
    }

    // ===== SPLIT BILLS PARTICIPANT RELATIONSHIPS =====

    public function test_split_bills_participant_belongs_to_split_bill(): void
    {
        $participant = new SplitBillsParticipant();
        $this->assertInstanceOf(BelongsTo::class, $participant->splitBill());
    }

    // ===== SUBSCRIPTION TRANSACTION RELATIONSHIPS =====

    public function test_subscription_transaction_belongs_to_workspace(): void
    {
        $subTx = new subscriptionTransaction();
        $this->assertInstanceOf(BelongsTo::class, $subTx->workspace());
    }

    public function test_subscription_transaction_belongs_to_wallet(): void
    {
        $subTx = new subscriptionTransaction();
        $this->assertInstanceOf(BelongsTo::class, $subTx->wallet());
    }

    public function test_subscription_transaction_belongs_to_category(): void
    {
        $subTx = new subscriptionTransaction();
        $this->assertInstanceOf(BelongsTo::class, $subTx->category());
    }

    // ===== FINANCIAL HEALTH SCORE RELATIONSHIPS =====

    public function test_financial_health_score_belongs_to_workspace(): void
    {
        $score = new financialHealthScore();
        $this->assertInstanceOf(BelongsTo::class, $score->workspace());
    }
}
