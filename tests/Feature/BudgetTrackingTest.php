<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{
    user_account,
    workspace,
    wallet,
    category,
    transaction,
    budget,
};
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group feature_tests
 * @group budget_tests
 * 
 * Integration test untuk Budget Management dan Tracking
 * Memastikan budget tracking terhadap transactions berjalan dengan baik
 */
class BudgetTrackingTest extends TestCase
{
    use RefreshDatabase;

    private user_account $userAccount;
    private workspace $workspace;
    private wallet $wallet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccount = user_account::factory()->create();
        $this->workspace = workspace::factory()->for($this->userAccount)->create();
        $this->wallet = wallet::factory()->for($this->workspace)->create(['balance' => 10000000]);
    }

    // ===== CREATE BUDGET =====

    public function test_can_create_budget_for_category(): void
    {
        $category = category::factory()->for($this->workspace)->create(['name_category' => 'Makanan']);

        $budget = budget::create([
            'workspace_id' => $this->workspace->id,
            'category_id' => $category->id,
            'limit_amount' => 1000000,
            'moonth_year' => now()->startOfMonth(),
        ]);

        $this->assertDatabaseHas('budgets', [
            'workspace_id' => $this->workspace->id,
            'category_id' => $category->id,
            'limit_amount' => 1000000,
        ]);

        $this->assertEquals($category->id, $budget->category_id);
    }

    public function test_budget_belongs_to_correct_workspace(): void
    {
        $category = category::factory()->for($this->workspace)->create();
        $budget = budget::factory()->for($this->workspace)->for($category)->create();

        $this->assertEquals($this->workspace->id, $budget->workspace_id);
        $this->assertDatabaseHas('budgets', [
            'workspace_id' => $this->workspace->id,
            'id' => $budget->id,
        ]);
    }

    // ===== BUDGET WITH TRANSACTIONS =====

    public function test_budget_can_track_expenses_in_category(): void
    {
        $category = category::factory()->for($this->workspace)->create(['name_category' => 'Makanan', 'type_category' => 'expense']);
        $budget = budget::factory()->for($this->workspace)->for($category)->create(['limit_amount' => 1000000]);

        // Create transactions under this category
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 200000, 'type' => 'expense']);

        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 300000, 'type' => 'expense']);

        // Get total expenses for category
        $totalExpenses = $this->workspace->transactions()
            ->where('category_id', $category->id)
            ->where('type', 'expense')
            ->sum('amount');

        $this->assertEquals(500000, $totalExpenses);
        $this->assertLessThan($budget->limit_amount, $totalExpenses);
    }

    public function test_detect_budget_exceeded(): void
    {
        $category = category::factory()->for($this->workspace)->create(['name_category' => 'Makanan', 'type_category' => 'expense']);
        $budget = budget::factory()->for($this->workspace)->for($category)->create(['limit_amount' => 500000]);

        // Create expenses that exceed budget
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 300000, 'type' => 'expense']);

        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 250000, 'type' => 'expense']);

        $totalExpenses = $this->workspace->transactions()
            ->where('category_id', $category->id)
            ->where('type', 'expense')
            ->sum('amount');

        $this->assertGreaterThan($budget->limit_amount, $totalExpenses);
        $this->assertEquals(550000, $totalExpenses);
    }

    public function test_budget_only_tracks_expenses_not_income(): void
    {
        $category = category::factory()->for($this->workspace)->create(['name_category' => 'Gaji', 'type_category' => 'income']);
        $budget = budget::factory()->for($this->workspace)->for($category)->create(['limit_amount' => 5000000]);

        // Create income transactions
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 10000000, 'type' => 'income']);

        // Budget should not affect income
        $totalIncome = $this->workspace->transactions()
            ->where('category_id', $category->id)
            ->where('type', 'income')
            ->sum('amount');

        $this->assertEquals(10000000, $totalIncome);
        $this->assertGreaterThan($budget->limit_amount, $totalIncome);
    }

    // ===== MULTIPLE BUDGETS =====

    public function test_can_create_multiple_budgets_for_different_categories(): void
    {
        $categoryFood = category::factory()->for($this->workspace)->create(['name_category' => 'Makanan']);
        $categoryTransport = category::factory()->for($this->workspace)->create(['name_category' => 'Transportasi']);
        $categoryUtilities = category::factory()->for($this->workspace)->create(['name_category' => 'Utilitas']);

        $budgetFood = budget::factory()->for($this->workspace)->for($categoryFood)->create(['limit_amount' => 1000000]);
        $budgetTransport = budget::factory()->for($this->workspace)->for($categoryTransport)->create(['limit_amount' => 500000]);
        $budgetUtilities = budget::factory()->for($this->workspace)->for($categoryUtilities)->create(['limit_amount' => 300000]);

        $this->assertEquals(3, $this->workspace->budgets()->count());
    }

    public function test_separate_budget_tracking_for_each_category(): void
    {
        $categoryFood = category::factory()->for($this->workspace)->create(['name_category' => 'Makanan', 'type_category' => 'expense']);
        $categoryTransport = category::factory()->for($this->workspace)->create(['name_category' => 'Transportasi', 'type_category' => 'expense']);

        $budgetFood = budget::factory()->for($this->workspace)->for($categoryFood)->create(['limit_amount' => 1000000]);
        $budgetTransport = budget::factory()->for($this->workspace)->for($categoryTransport)->create(['limit_amount' => 500000]);

        // Add expenses to food category
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($categoryFood)
            ->create(['amount' => 600000, 'type' => 'expense']);

        // Add expenses to transport category
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($categoryTransport)
            ->create(['amount' => 300000, 'type' => 'expense']);

        $foodExpenses = $this->workspace->transactions()
            ->where('category_id', $categoryFood->id)
            ->where('type', 'expense')
            ->sum('amount');

        $transportExpenses = $this->workspace->transactions()
            ->where('category_id', $categoryTransport->id)
            ->where('type', 'expense')
            ->sum('amount');

        $this->assertGreaterThan($budgetFood->limit_amount, $foodExpenses);
        $this->assertLessThan($budgetTransport->limit_amount, $transportExpenses);
    }

    // ===== UPDATE BUDGET =====

    public function test_budget_limit_can_be_updated(): void
    {
        $category = category::factory()->for($this->workspace)->create();
        $budget = budget::factory()->for($this->workspace)->for($category)->create(['limit_amount' => 500000]);

        $budget->update(['limit_amount' => 1000000]);

        $this->assertEquals(1000000, $budget->limit_amount);
        $this->assertDatabaseHas('budgets', [
            'id' => $budget->id,
            'limit_amount' => 1000000,
        ]);
    }

    // ===== DELETE BUDGET =====

    public function test_budget_can_be_deleted(): void
    {
        $category = category::factory()->for($this->workspace)->create();
        $budget = budget::factory()->for($this->workspace)->for($category)->create();

        $budgetId = $budget->id;
        $budget->delete();

        $this->assertDatabaseMissing('budgets', ['id' => $budgetId]);
    }

    // ===== BUDGET ISOLATION =====

    public function test_budgets_isolated_between_workspaces(): void
    {
        $workspace2 = workspace::factory()->for($this->userAccount)->create();

        $category1 = category::factory()->for($this->workspace)->create();
        $category2 = category::factory()->for($workspace2)->create();

        $budget1 = budget::factory()->for($this->workspace)->for($category1)->create();
        $budget2 = budget::factory()->for($workspace2)->for($category2)->create();

        $this->assertEquals(1, $this->workspace->budgets()->count());
        $this->assertEquals(1, $workspace2->budgets()->count());
    }

    // ===== COMPLEX SCENARIO =====

    public function test_monthly_budget_tracking_scenario(): void
    {
        $category = category::factory()->for($this->workspace)->create(['name_category' => 'Makanan', 'type_category' => 'expense']);
        $budget = budget::factory()->for($this->workspace)->for($category)->create(['limit_amount' => 1000000]);

        // Week 1: 250k
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 250000, 'type' => 'expense']);

        $expensesWeek1 = $this->workspace->transactions()
            ->where('category_id', $category->id)
            ->where('type', 'expense')
            ->sum('amount');

        $this->assertLessThan($budget->limit_amount, $expensesWeek1);

        // Week 2: 300k
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 300000, 'type' => 'expense']);

        // Week 3: 350k
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 350000, 'type' => 'expense']);

        $totalExpenses = $this->workspace->transactions()
            ->where('category_id', $category->id)
            ->where('type', 'expense')
            ->sum('amount');

        $this->assertEquals(900000, $totalExpenses);
        $this->assertLessThan($budget->limit_amount, $totalExpenses);

        // Week 4: 150k - exceeds budget
        transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($category)
            ->create(['amount' => 150000, 'type' => 'expense']);

        $totalExpenses = $this->workspace->transactions()
            ->where('category_id', $category->id)
            ->where('type', 'expense')
            ->sum('amount');

        $this->assertEquals(1050000, $totalExpenses);
        $this->assertGreaterThan($budget->limit_amount, $totalExpenses);
    }
}
