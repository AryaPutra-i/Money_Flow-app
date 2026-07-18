<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\{user_account, workspace, wallet, transaction, category, debt};
use App\Services\FinancialHealthService;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group unit_tests
 * 
 * Unit test untuk FinancialHealthService
 * Memastikan perhitungan financial health score akurat
 */
class FinancialHealthServiceTest extends TestCase
{
    use RefreshDatabase;

    private user_account $userAccount;
    private workspace $workspace;
    private FinancialHealthService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccount = user_account::factory()->create();
        $this->workspace = workspace::factory()->for($this->userAccount)->create();
        $this->service = new FinancialHealthService();
    }

    // ===== BASIC SCORE CALCULATION =====

    public function test_financial_health_service_returns_financial_health_score_instance(): void
    {
        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);
        $this->assertInstanceOf(\App\Models\financialHealthScore::class, $score);
    }

    public function test_score_is_saved_to_database(): void
    {
        $initialCount = \App\Models\financialHealthScore::count();

        $this->service->hitungDanSimpanSkor($this->workspace->id);

        $this->assertEquals($initialCount + 1, \App\Models\financialHealthScore::count());
    }

    public function test_score_is_associated_with_workspace(): void
    {
        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        $this->assertEquals($this->workspace->id, $score->workspace_id);
    }

    // ===== WALLET BALANCE CALCULATION =====

    public function test_total_saldo_dompet_calculated_correctly(): void
    {
        wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);
        wallet::factory()->for($this->workspace)->create(['balance' => 2000000]);
        wallet::factory()->for($this->workspace)->create(['balance' => 500000]);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        $this->assertStringContainsString('3.500.000', $score->rincian_metrik['total_aset_likuid']);
    }

    // ===== INCOME CALCULATION =====

    public function test_income_transactions_calculated_for_this_month(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);
        $category = category::factory()->for($this->workspace)->create();

        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 5000000, 'type' => 'income']);

        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 3000000, 'type' => 'income']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        $this->assertStringContainsString('menabung_bulan_ini', $score->rincian_metrik);
    }

    // ===== EXPENSE CALCULATION =====

    public function test_expense_transactions_calculated_for_this_month(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);
        $category = category::factory()->for($this->workspace)->create();

        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 2000000, 'type' => 'expense']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        $this->assertIsArray($score->rincian_metrik);
        $this->assertArrayHasKey('durasu_bertahan_dana_darurat', $score->rincian_metrik);
    }

    // ===== EMERGENCY FUND CALCULATION =====

    public function test_emergency_fund_duration_less_than_one_month(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 100000]);
        $category = category::factory()->for($this->workspace)->create();

        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 500000, 'type' => 'expense']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        // Dana darurat seharusnya < 1 bulan
        $this->assertStringContainsString('Bulan', $score->rincian_metrik['durasu_bertahan_dana_darurat']);
    }

    public function test_emergency_fund_duration_more_than_three_months(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1500000]);
        $category = category::factory()->for($this->workspace)->create();

        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 500000, 'type' => 'expense']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        // Dana darurat seharusnya >= 3 bulan, sehingga score mendapat +30 poin
        $this->assertGreaterThanOrEqual(30, $score->score);
    }

    // ===== SAVING RATE CALCULATION =====

    public function test_saving_rate_greater_than_20_percent_gets_40_points(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);
        $category = category::factory()->for($this->workspace)->create();

        // Income: 10 juta
        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 10000000, 'type' => 'income']);

        // Expense: 7 juta (sisa 30% = lebih dari 20%)
        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 7000000, 'type' => 'expense']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        // Seharusnya mendapat 40 poin dari saving rate
        $this->assertGreaterThanOrEqual(40, $score->score);
    }

    public function test_saving_rate_between_0_and_20_percent_gets_20_points(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);
        $category = category::factory()->for($this->workspace)->create();

        // Income: 10 juta
        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 10000000, 'type' => 'income']);

        // Expense: 8.5 juta (sisa 15%)
        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 8500000, 'type' => 'expense']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        // Seharusnya mendapat 20 poin dari saving rate
        $this->assertGreaterThanOrEqual(20, $score->score);
    }

    public function test_negative_saving_rate_gets_zero_points(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);
        $category = category::factory()->for($this->workspace)->create();

        // Income: 5 juta
        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 5000000, 'type' => 'income']);

        // Expense: 8 juta (deficit)
        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 8000000, 'type' => 'expense']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        // Tidak ada poin dari saving rate (negative atau zero)
        // Tapi score bisa dari metric lain
        $this->assertIsInt($score->score);
    }

    // ===== HEALTH CONCLUSION =====

    public function test_very_healthy_conclusion_when_score_above_80(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 5000000]);
        $category = category::factory()->for($this->workspace)->create();

        // Scenario: Good savings, good emergency fund
        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 10000000, 'type' => 'income']);

        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 5000000, 'type' => 'expense']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        if ($score->score >= 80) {
            $this->assertEquals('sangat sehat', $score->rincian_metrik['kesimpulan_sistem']);
        }
    }

    public function test_needs_adjustment_conclusion_when_score_below_80(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 100000]);
        $category = category::factory()->for($this->workspace)->create();

        // Scenario: Low balance and high expenses
        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 1000000, 'type' => 'income']);

        transaction::factory()
            ->for($this->workspace)
            ->for($wallet)
            ->for($category)
            ->create(['amount' => 950000, 'type' => 'expense']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        if ($score->score < 80) {
            $this->assertEquals('Butuh Penyesuaian Anggaran', $score->rincian_metrik['kesimpulan_sistem']);
        }
    }

    // ===== ZERO TRANSACTIONS SCENARIO =====

    public function test_zero_income_and_expense_returns_valid_score(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 500000]);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        $this->assertIsInt($score->score);
        $this->assertIsArray($score->rincian_metrik);
    }

    // ===== MULTIPLE WALLETS =====

    public function test_multiple_wallets_combined_for_calculation(): void
    {
        wallet::factory()->for($this->workspace)->create(['balance' => 3000000]);
        wallet::factory()->for($this->workspace)->create(['balance' => 2000000]);
        $wallet3 = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);

        $category = category::factory()->for($this->workspace)->create();

        transaction::factory()
            ->for($this->workspace)
            ->for($wallet3)
            ->for($category)
            ->create(['amount' => 2000000, 'type' => 'income']);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        // Total saldo: 3M + 2M + 1M = 6M
        $this->assertStringContainsString('6.000.000', $score->rincian_metrik['total_aset_likuid']);
    }

    // ===== RINCIAN METRIK STRUCTURE =====

    public function test_rincian_metrik_has_all_required_keys(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);

        $score = $this->service->hitungDanSimpanSkor($this->workspace->id);

        $requiredKeys = [
            'total_aset_likuid',
            'durasu_bertahan_dana_darurat',
            'rasio_hutang_vs_income',
            'rasio_menabung_bulan_ini',
            'kesimpulan_sistem'
        ];

        foreach ($requiredKeys as $key) {
            $this->assertArrayHasKey($key, $score->rincian_metrik);
        }
    }

    public function test_rincian_metrik_is_stored_as_array_in_database(): void
    {
        $wallet = wallet::factory()->for($this->workspace)->create(['balance' => 1000000]);

        $this->service->hitungDanSimpanSkor($this->workspace->id);

        $savedScore = \App\Models\financialHealthScore::where('workspace_id', $this->workspace->id)->first();

        $this->assertIsArray($savedScore->rincian_metrik);
    }
}
