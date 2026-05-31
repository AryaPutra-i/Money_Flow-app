<?php

namespace Tests\Feature;

use App\Filament\Arya\Resources\Budgets\Pages\CreateBudget;
use App\Filament\Arya\Resources\Budgets\Pages\EditBudget;
use App\Filament\Arya\Resources\Budgets\Pages\ShowBudget;
use App\Models\budget;
use App\Models\user_account;
use App\Models\category;
use App\Models\workspace;
use App\Models\transaction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @group feature_tests
 */
class FiturBudgetTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $arya = user_account::factory()->create();
        $this->actingAs($arya);
        Filament::setCurrentPanel(Filament::getPanel('arya'));
        
    }

    public function test_create_budget()
    {
        $category = category::factory()->create();
        $workspace = workspace::factory()->create();

        $limit = 1234.56;
        $moonthDb = date('Y-m-d 00:00:00');

        Livewire::test(CreateBudget::class)
            ->set('data.workspace_id', $workspace->id)
            ->set('data.category_id', $category->id)
            ->set('data.limit_amount', $limit)
            ->set('data.moonth_year', $moonthDb)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('budgets', [
            'workspace_id' => $workspace->id,
            'category_id' => $category->id,
            'limit_amount' => $limit,
            'moonth_year' => $moonthDb,
        ]);
    }

    public function test_limit_amount_budget()
    {
        $workspace = workspace::factory()->create([
            'name' => 'Workspace Manual',
            'type' => 'personal',
        ]);

        $category = category::factory()->create([
            'workspace_id' => $workspace->id,
            'name_category' => 'Makanan',
            'type_category' => 'expense',
        ]);

        $budget = budget::factory()->create([
            'workspace_id' => $workspace->id,
            'category_id' => $category->id,
            'limit_amount' => 750000.00,
            'moonth_year' => '2026-06-01',
        ]);

        $wallet = \App\Models\wallet::factory()->create([
            'workspace_id' => $workspace->id,
            'name' => 'Wallet Manual',
            'balance' => 1000000.00,
        ]);

        $transaction = transaction::factory()->create([
            'workspace_id' => $workspace->id,
            'wallet_id' => $wallet->id,
            'category_id' => $category->id,
            'amount' => 500000.00,
            'type' => 'expense',
            'transaction_date' => '2026-06-15',
            'proof_path' => null,
            'is_recurring' => false,
        ]);

        $remaining = $budget->limit_amount;
        
        $this->assertEquals(250000.00, $remaining, "Sisa anggaran setelah transaksi tidak sesuai dengan yang diharapkan.");
    }
}
