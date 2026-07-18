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
 * @group workspace_tests
 * 
 * Integration test untuk Workspace Management
 * Memastikan workspace dan relasi-relasinya bekerja dengan baik
 */
class WorkspaceManagementTest extends TestCase
{
    use RefreshDatabase;

    private user_account $userAccount;
    private workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccount = user_account::factory()->create();
        $this->workspace = workspace::factory()->for($this->userAccount)->create();
    }

    // ===== CREATE WORKSPACE =====

    public function test_user_can_create_workspace(): void
    {
        $workspace = workspace::create([
            'user_account_id' => $this->userAccount->id,
            'name' => 'Workspace Liburan',
            'type' => 'personal',
        ]);

        $this->assertDatabaseHas('workspaces', [
            'user_account_id' => $this->userAccount->id,
            'name' => 'Workspace Liburan',
            'type' => 'personal',
        ]);

        $this->assertEquals($this->userAccount->id, $workspace->user_account_id);
    }

    public function test_workspace_belongs_to_correct_user(): void
    {
        $user2 = user_account::factory()->create();
        $workspace2 = workspace::factory()->for($user2)->create();

        $this->assertEquals($this->userAccount->id, $this->workspace->user_account_id);
        $this->assertEquals($user2->id, $workspace2->user_account_id);
        $this->assertNotEquals($this->workspace->user_account_id, $workspace2->user_account_id);
    }

    // ===== WORKSPACE WITH WALLETS =====

    public function test_workspace_can_have_multiple_wallets(): void
    {
        $wallet1 = wallet::factory()->for($this->workspace)->create(['name' => 'Dompet Utama']);
        $wallet2 = wallet::factory()->for($this->workspace)->create(['name' => 'Dompet Tabungan']);
        $wallet3 = wallet::factory()->for($this->workspace)->create(['name' => 'Dompet Liburan']);

        $this->assertEquals(3, $this->workspace->wallets()->count());
        $this->assertTrue($this->workspace->wallets()->where('name', 'Dompet Utama')->exists());
        $this->assertTrue($this->workspace->wallets()->where('name', 'Dompet Tabungan')->exists());
        $this->assertTrue($this->workspace->wallets()->where('name', 'Dompet Liburan')->exists());
    }

    public function test_wallet_isolation_between_workspaces(): void
    {
        $workspace2 = workspace::factory()->for($this->userAccount)->create();

        $wallet1 = wallet::factory()->for($this->workspace)->create();
        $wallet2 = wallet::factory()->for($workspace2)->create();

        $this->assertEquals(1, $this->workspace->wallets()->count());
        $this->assertEquals(1, $workspace2->wallets()->count());
    }

    // ===== WORKSPACE WITH CATEGORIES =====

    public function test_workspace_can_have_multiple_categories(): void
    {
        category::factory()->for($this->workspace)->create(['name_category' => 'Makanan', 'type_category' => 'expense']);
        category::factory()->for($this->workspace)->create(['name_category' => 'Transportasi', 'type_category' => 'expense']);
        category::factory()->for($this->workspace)->create(['name_category' => 'Gaji', 'type_category' => 'income']);

        $this->assertEquals(3, $this->workspace->categories()->count());
    }

    public function test_category_isolation_between_workspaces(): void
    {
        $workspace2 = workspace::factory()->for($this->userAccount)->create();

        category::factory()->for($this->workspace)->create(['name_category' => 'Makanan']);
        category::factory()->for($workspace2)->create(['name_category' => 'Makanan']);

        $this->assertEquals(1, $this->workspace->categories()->count());
        $this->assertEquals(1, $workspace2->categories()->count());
    }

    // ===== WORKSPACE WITH TRANSACTIONS =====

    public function test_workspace_can_have_multiple_transactions(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create();
        $category = category::factory()->for($this->workspace)->create();

        transaction::factory()
            ->count(5)
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create();

        $this->assertEquals(5, $this->workspace->transactions()->count());
    }

    public function test_transaction_isolation_between_workspaces(): void
    {
        $workspace2 = workspace::factory()->for($this->userAccount)->create();

        $wallet1 = wallet::factory()->for($this->workspace)->create();
        $wallet2 = wallet::factory()->for($workspace2)->create();
        $category1 = category::factory()->for($this->workspace)->create();
        $category2 = category::factory()->for($workspace2)->create();

        transaction::factory()
            ->count(3)
            ->for($this->workspace)
            ->for($wallet1)
            ->for($category1)
            ->create();

        transaction::factory()
            ->count(2)
            ->for($workspace2)
            ->for($wallet2)
            ->for($category2)
            ->create();

        $this->assertEquals(3, $this->workspace->transactions()->count());
        $this->assertEquals(2, $workspace2->transactions()->count());
    }

    // ===== WORKSPACE WITH BUDGETS =====

    public function test_workspace_can_have_multiple_budgets(): void
    {
        $category1 = category::factory()->for($this->workspace)->create();
        $category2 = category::factory()->for($this->workspace)->create();

        budget::factory()->for($this->workspace)->for($category1)->create();
        budget::factory()->for($this->workspace)->for($category2)->create();

        $this->assertEquals(2, $this->workspace->budgets()->count());
    }

    public function test_budget_isolation_between_workspaces(): void
    {
        $workspace2 = workspace::factory()->for($this->userAccount)->create();

        $category1 = category::factory()->for($this->workspace)->create();
        $category2 = category::factory()->for($workspace2)->create();

        budget::factory()->for($this->workspace)->for($category1)->create();
        budget::factory()->for($workspace2)->for($category2)->create();

        $this->assertEquals(1, $this->workspace->budgets()->count());
        $this->assertEquals(1, $workspace2->budgets()->count());
    }

    // ===== UPDATE WORKSPACE =====

    public function test_workspace_can_be_updated(): void
    {
        $this->workspace->update([
            'name' => 'Updated Workspace Name',
            'type' => 'organization',
        ]);

        $this->assertEquals('Updated Workspace Name', $this->workspace->name);
        $this->assertEquals('shared', $this->workspace->type);

        $this->assertDatabaseHas('workspaces', [
            'id' => $this->workspace->id,
            'name' => 'Updated Workspace Name',
            'type' => 'organization',
        ]);
    }

    // ===== DELETE WORKSPACE =====

    public function test_workspace_can_be_deleted(): void
    {
        $workspaceId = $this->workspace->id;

        $this->workspace->delete();

        $this->assertDatabaseMissing('workspaces', ['id' => $workspaceId]);
    }

    public function test_deleting_workspace_does_not_affect_other_workspaces(): void
    {
        $workspace2 = workspace::factory()->for($this->userAccount)->create();

        $this->workspace->delete();

        $this->assertTrue(workspace::where('id', $workspace2->id)->exists());
        $this->assertEquals(1, $this->userAccount->workspaces()->count());
    }

    // ===== COMPLEX SCENARIOS =====

    public function test_complete_workspace_setup_with_all_components(): void
    {
        // Create wallets
        $wallet1 = wallet::factory()->for($this->workspace)->create(['name' => 'Dompet Utama', 'balance' => 5000000]);
        $wallet2 = wallet::factory()->for($this->workspace)->create(['name' => 'Dompet Tabungan', 'balance' => 2000000]);

        // Create categories
        $categoryFood = category::factory()->for($this->workspace)->create(['name_category' => 'Makanan', 'type_category' => 'expense']);
        $categoryTransport = category::factory()->for($this->workspace)->create(['name_category' => 'Transportasi', 'type_category' => 'expense']);
        $categorySalary = category::factory()->for($this->workspace)->create(['name_category' => 'Gaji', 'type_category' => 'income']);

        // Create transactions
        transaction::factory()->for($this->workspace)->for($wallet1)->for($categorySalary)->create(['amount' => 10000000, 'type' => 'income']);
        transaction::factory()->for($this->workspace)->for($wallet1)->for($categoryFood)->create(['amount' => 500000, 'type' => 'expense']);
        transaction::factory()->for($this->workspace)->for($wallet1)->for($categoryTransport)->create(['amount' => 200000, 'type' => 'expense']);

        // Create budgets
        $budgetFood = budget::factory()->for($this->workspace)->for($categoryFood)->create(['limit_amount' => 1000000]);
        $budgetTransport = budget::factory()->for($this->workspace)->for($categoryTransport)->create(['limit_amount' => 500000]);

        // Verify all relationships
        $this->assertEquals(2, $this->workspace->wallets()->count());
        $this->assertEquals(3, $this->workspace->categories()->count());
        $this->assertEquals(3, $this->workspace->transactions()->count());
        $this->assertEquals(2, $this->workspace->budgets()->count());

        // Verify data integrity
        $this->assertDatabaseHas('wallets', ['workspace_id' => $this->workspace->id, 'name' => 'Dompet Utama']);
        $this->assertDatabaseHas('transactions', ['workspace_id' => $this->workspace->id, 'amount' => 10000000, 'type' => 'income']);
        $this->assertDatabaseHas('budgets', ['workspace_id' => $this->workspace->id, 'limit_amount' => 1000000]);
    }

    public function test_multiple_user_workspaces_are_independent(): void
    {
        $user2 = user_account::factory()->create();
        $workspace2_user1 = workspace::factory()->for($this->userAccount)->create();
        $workspace1_user2 = workspace::factory()->for($user2)->create();

        $wallet_ws1 = wallet::factory()->for($this->workspace)->create();
        $wallet_ws2 = wallet::factory()->for($workspace2_user1)->create();
        $wallet_us2_ws1 = wallet::factory()->for($workspace1_user2)->create();

        $this->assertEquals(1, $this->workspace->wallets()->count());
        $this->assertEquals(1, $workspace2_user1->wallets()->count());
        $this->assertEquals(1, $workspace1_user2->wallets()->count());
        $this->assertEquals(2, $this->userAccount->workspaces()->count());
        $this->assertEquals(1, $user2->workspaces()->count());
    }
}
