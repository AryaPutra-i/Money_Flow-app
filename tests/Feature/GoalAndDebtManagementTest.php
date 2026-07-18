<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{
    user_account,
    workspace,
    wallet,
    goal,
    GoalSaving,
    debt,
    SavingInvestasi,
};
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group feature_tests
 * @group goal_debt_tests
 * 
 * Integration test untuk Goal Management dan Debt Tracking
 * Memastikan goal savings dan debt tracking berfungsi dengan baik
 */
class GoalAndDebtManagementTest extends TestCase
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

    // ===== GOAL CREATION =====

    public function test_can_create_saving_goal(): void
    {
        $goal = goal::create([
            'workspace_id' => $this->workspace->id,
            'Deskripsi' => 'Liburan ke Bali',
            'target_amount' => 5000000,
            'current_amount' => 0,
        ]);

        $this->assertDatabaseHas('goals', [
            'workspace_id' => $this->workspace->id,
            'Deskripsi' => 'Liburan ke Bali',
            'target_amount' => 5000000,
        ]);

        $this->assertEquals($this->workspace->id, $goal->workspace_id);
    }

    public function test_can_create_multiple_goals(): void
    {
        goal::factory()
            ->count(3)
            ->for($this->workspace)
            ->create();

        $this->assertEquals(3, $this->workspace->goals()->count());
    }

    // ===== GOAL SAVINGS =====

    public function test_can_save_to_goal(): void
    {
        $goal = goal::factory()->for($this->workspace)->create(['current_amount' => 0, 'target_amount' => 5000000]);

        $goalSaving = GoalSaving::create([
            'goal_id' => $goal->id,
            'wallet_id' => $this->wallet->id,
            'amount' => 500000,
            'date' => now(),
            'notes' => 'Saving dari gaji bulan ini',
        ]);

        $this->assertDatabaseHas('goal_savings', [
            'goal_id' => $goal->id,
            'wallet_id' => $this->wallet->id,
            'amount' => 500000,
        ]);
    }

    public function test_can_track_goal_progress(): void
    {
        $goal = goal::factory()->for($this->workspace)->create(['current_amount' => 0, 'target_amount' => 5000000]);

        // First saving
        GoalSaving::factory()->for($goal)->for($this->wallet)->create(['amount' => 1000000]);
        // Second saving
        GoalSaving::factory()->for($goal)->for($this->wallet)->create(['amount' => 1500000]);
        // Third saving
        GoalSaving::factory()->for($goal)->for($this->wallet)->create(['amount' => 1000000]);

        $totalSaved = $goal->goalSavings()->sum('amount');
        $this->assertEquals(3500000, $totalSaved);
        $this->assertLessThan($goal->target_amount, $totalSaved);
    }

    public function test_goal_progress_percentage_calculation(): void
    {
        $goal = goal::factory()->for($this->workspace)->create(['current_amount' => 0, 'target_amount' => 10000000]);

        GoalSaving::factory()
            ->count(5)
            ->for($goal)
            ->for($this->wallet)
            ->create(['amount' => 1000000]);

        $totalSaved = $goal->goalSavings()->sum('amount');
        $progressPercentage = ($totalSaved / $goal->target_amount) * 100;

        $this->assertEquals(5000000, $totalSaved);
        $this->assertEquals(50, $progressPercentage);
    }

    public function test_goal_reached(): void
    {
        $goal = goal::factory()->for($this->workspace)->create(['current_amount' => 0, 'target_amount' => 2000000]);

        GoalSaving::factory()->for($goal)->for($this->wallet)->create(['amount' => 2000000]);

        $totalSaved = $goal->goalSavings()->sum('amount');
        $this->assertEquals($goal->target_amount, $totalSaved);
    }

    public function test_multiple_goals_tracking(): void
    {
        $goal1 = goal::factory()->for($this->workspace)->create(['target_amount' => 5000000]);
        $goal2 = goal::factory()->for($this->workspace)->create(['target_amount' => 3000000]);

        GoalSaving::factory()->count(2)->for($goal1)->for($this->wallet)->create(['amount' => 1000000]);
        GoalSaving::factory()->count(3)->for($goal2)->for($this->wallet)->create(['amount' => 500000]);

        $savings1 = $goal1->goalSavings()->sum('amount');
        $savings2 = $goal2->goalSavings()->sum('amount');

        $this->assertEquals(2000000, $savings1);
        $this->assertEquals(1500000, $savings2);
    }

    // ===== DEBT CREATION =====

    public function test_can_create_debt_record(): void
    {
        $debt = debt::create([
            'workspace_id' => $this->workspace->id,
            'type' => 'debt',
            'person_name' => 'Budi',
            'amount' => 500000,
            'status' => 'unpaid',
        ]);

        $this->assertDatabaseHas('debts', [
            'workspace_id' => $this->workspace->id,
            'person_name' => 'Budi',
            'amount' => 500000,
            'status' => 'unpaid',
        ]);
    }

    public function test_can_create_multiple_debts(): void
    {
        debt::factory()
            ->count(3)
            ->for($this->workspace)
            ->create();

        $this->assertEquals(3, $this->workspace->debts()->count());
    }

    public function test_debt_types_hutang_and_piutang(): void
    {
        $debtHutang = debt::factory()->for($this->workspace)->create([
            'type' => 'debt',
            'person_name' => 'Toko Buku',
            'amount' => 200000,
        ]);

        $debtPiutang = debt::factory()->for($this->workspace)->create([
            'type' => 'receivable',
            'person_name' => 'Teman',
            'amount' => 300000,
        ]);

        $this->assertEquals('debt', $debtHutang->type);
        $this->assertEquals('receivable', $debtPiutang->type);
    }

    // ===== DEBT STATUS =====

    public function test_debt_status_tracking(): void
    {
        $debt = debt::factory()->for($this->workspace)->create([
            'amount' => 500000,
            'status' => 'unpaid',
        ]);

        $this->assertEquals('unpaid', $debt->status);

        // Update to paid
        $debt->update(['status' => 'paid']);
        $this->assertEquals('paid', $debt->status);
    }

    public function test_can_track_pending_debts(): void
    {
        debt::factory()->for($this->workspace)->create(['status' => 'unpaid']);
        debt::factory()->for($this->workspace)->create(['status' => 'unpaid']);
        debt::factory()->for($this->workspace)->create(['status' => 'paid']);

        $unpaidDebts = $this->workspace->debts()
            ->where('status', 'unpaid')
            ->count();

        $paidDebts = $this->workspace->debts()
            ->where('status', 'paid')
            ->count();

        $this->assertEquals(2, $unpaidDebts);
        $this->assertEquals(1, $paidDebts);
    }

    public function test_total_pending_debt_amount(): void
    {
        debt::factory()->for($this->workspace)->create(['amount' => 500000, 'status' => 'unpaid']);
        debt::factory()->for($this->workspace)->create(['amount' => 300000, 'status' => 'unpaid']);
        debt::factory()->for($this->workspace)->create(['amount' => 200000, 'status' => 'paid']);

        $totalUnpaidDebt = $this->workspace->debts()
            ->where('status', 'unpaid')
            ->sum('amount');

        $this->assertEquals(800000, $totalUnpaidDebt);
    }

    // ===== DEBT ISOLATION =====

    public function test_debts_isolated_between_workspaces(): void
    {
        $workspace2 = workspace::factory()->for($this->userAccount)->create();

        debt::factory()->count(2)->for($this->workspace)->create();
        debt::factory()->count(3)->for($workspace2)->create();

        $this->assertEquals(2, $this->workspace->debts()->count());
        $this->assertEquals(3, $workspace2->debts()->count());
    }

    // ===== INVESTMENT TRACKING =====

    public function test_can_create_investment_record(): void
    {
        $investment = SavingInvestasi::create([
            'workspace_id' => $this->workspace->id,
            'wallet_id' => $this->wallet->id,
            'intrumen' => 'saham',
            'nama_instrumen' => 'BBCA',
            'nominal_modal' => 10000000,
            'estimasi_return' => 2000000,
            'tanggal_mulai' => now(),
            'tanggal_jatuh_tempo' => now()->addMonths(12),
            'status' => 'aktif',
        ]);

        $this->assertDatabaseHas('saving_investasis', [
            'workspace_id' => $this->workspace->id,
            'nama_instrumen' => 'BBCA',
            'status' => 'aktif',
        ]);
    }

    public function test_can_create_multiple_investments(): void
    {
        SavingInvestasi::factory()
            ->count(3)
            ->for($this->workspace)
            ->for($this->wallet)
            ->create();

        $this->assertEquals(3, $this->workspace->savingInvestasis()->count());
    }

    public function test_investment_status_tracking(): void
    {
        $investment = SavingInvestasi::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->create(['status' => 'aktif']);

        $this->assertEquals('aktif', $investment->status);

        $investment->update(['status' => 'selesai']);
        $this->assertEquals('selesai', $investment->status);
    }

    public function test_track_active_investments(): void
    {
        SavingInvestasi::factory()->for($this->workspace)->for($this->wallet)->create(['status' => 'aktif']);
        SavingInvestasi::factory()->for($this->workspace)->for($this->wallet)->create(['status' => 'aktif']);
        SavingInvestasi::factory()->for($this->workspace)->for($this->wallet)->create(['status' => 'selesai']);

        $activeInvestments = $this->workspace->savingInvestasis()
            ->where('status', 'aktif')
            ->count();

        $this->assertEquals(2, $activeInvestments);
    }

    public function test_calculate_total_invested_amount(): void
    {
        SavingInvestasi::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->create(['nominal_modal' => 5000000]);

        SavingInvestasi::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->create(['nominal_modal' => 3000000]);

        $totalInvested = $this->workspace->savingInvestasis()
            ->sum('nominal_modal');

        $this->assertEquals(8000000, $totalInvested);
    }

    public function test_calculate_total_expected_return(): void
    {
        SavingInvestasi::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->create(['estimasi_return' => 500000]);

        SavingInvestasi::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->create(['estimasi_return' => 300000]);

        $totalReturn = $this->workspace->savingInvestasis()
            ->sum('estimasi_return');

        $this->assertEquals(800000, $totalReturn);
    }

    // ===== COMPLEX SCENARIO =====

    public function test_complete_financial_tracking_scenario(): void
    {
        // Create goals
        $goal1 = goal::factory()->for($this->workspace)->create(['target_amount' => 10000000]);
        $goal2 = goal::factory()->for($this->workspace)->create(['target_amount' => 5000000]);

        // Create savings to goals
        GoalSaving::factory()->count(3)->for($goal1)->for($this->wallet)->create(['amount' => 2000000]);
        GoalSaving::factory()->count(2)->for($goal2)->for($this->wallet)->create(['amount' => 1500000]);

        // Create debts
        debt::factory()->for($this->workspace)->create(['amount' => 1000000, 'status' => 'unpaid']);
        debt::factory()->for($this->workspace)->create(['amount' => 500000, 'status' => 'paid']);

        // Create investments
        SavingInvestasi::factory()->for($this->workspace)->for($this->wallet)->create(['nominal_modal' => 20000000]);

        // Verify totals
        $goal1Savings = $goal1->goalSavings()->sum('amount');
        $goal2Savings = $goal2->goalSavings()->sum('amount');
        $unpaidDebt = $this->workspace->debts()->where('status', 'unpaid')->sum('amount');
        $totalInvested = $this->workspace->savingInvestasis()->sum('nominal_modal');

        $this->assertEquals(6000000, $goal1Savings);
        $this->assertEquals(3000000, $goal2Savings);
        $this->assertEquals(1000000, $unpaidDebt);
        $this->assertEquals(20000000, $totalInvested);
    }
}
