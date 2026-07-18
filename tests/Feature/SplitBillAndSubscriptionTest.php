<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{
    user_account,
    workspace,
    wallet,
    category,
    transaction,
    SplitBill,
    SplitBillsParticipant,
    subscriptionTransaction,
};
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group feature_tests
 * @group split_bill_subscription_tests
 * 
 * Integration test untuk Split Bill dan Subscription Transaction
 * Memastikan pembagian tagihan dan transaksi berlangganan berjalan dengan baik
 */
class SplitBillAndSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    private user_account $userAccount;
    private workspace $workspace;
    private wallet $wallet;
    private category $category;
    private transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccount = user_account::factory()->create();
        $this->workspace = workspace::factory()->for($this->userAccount)->create();
        $this->wallet = wallet::factory()->for($this->workspace)->create(['balance' => 10000000]);
        $this->category = category::factory()->for($this->workspace)->create();
        $this->transaction = transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['amount' => 300000, 'type' => 'expense']);
    }

    // ===== SPLIT BILL CREATION =====

    public function test_can_create_split_bill_from_transaction(): void
    {
        $splitBill = SplitBill::create([
            'transaction_id' => $this->transaction->id,
            'amount' => 300000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('split_bills', [
            'transaction_id' => $this->transaction->id,
            'amount' => 300000,
            'status' => 'pending',
        ]);

        $this->assertEquals($this->transaction->id, $splitBill->transaction_id);
    }

    public function test_split_bill_belongs_to_transaction(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create();

        $this->assertEquals($this->transaction->id, $splitBill->transaction_id);
        $this->assertNotNull($splitBill->transaction);
    }

    // ===== SPLIT BILL PARTICIPANTS =====

    public function test_can_add_participants_to_split_bill(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create(['amount' => 300000]);

        $participant1 = SplitBillsParticipant::create([
            'split_bill_id' => $splitBill->id,
            'friend_name' => 'Budi',
            'amount_due' => 100000,
            'is_paid' => false,
        ]);

        $participant2 = SplitBillsParticipant::create([
            'split_bill_id' => $splitBill->id,
            'friend_name' => 'Andi',
            'amount_due' => 100000,
            'is_paid' => false,
        ]);

        $participant3 = SplitBillsParticipant::create([
            'split_bill_id' => $splitBill->id,
            'friend_name' => 'Citra',
            'amount_due' => 100000,
            'is_paid' => false,
        ]);

        $this->assertEquals(3, $splitBill->participants()->count());
    }

    public function test_split_bill_total_matches_transaction_amount(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create(['amount' => 300000]);

        SplitBillsParticipant::factory()
            ->count(3)
            ->for($splitBill)
            ->create(['amount_due' => 100000]);

        $totalDue = $splitBill->participants()->sum('amount_due');

        $this->assertEquals($this->transaction->amount, $totalDue);
        $this->assertEquals($splitBill->amount, $totalDue);
    }

    public function test_split_bill_equal_division(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create(['amount' => 300000]);

        SplitBillsParticipant::factory()
            ->count(3)
            ->for($splitBill)
            ->create(['amount_due' => 100000]);

        $participants = $splitBill->participants;

        foreach ($participants as $participant) {
            $this->assertEquals(100000, $participant->amount_due);
        }
    }

    public function test_split_bill_unequal_division(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create(['amount' => 300000]);

        SplitBillsParticipant::create([
            'split_bill_id' => $splitBill->id,
            'friend_name' => 'Budi',
            'amount_due' => 150000,
            'is_paid' => false,
        ]);

        SplitBillsParticipant::create([
            'split_bill_id' => $splitBill->id,
            'friend_name' => 'Andi',
            'amount_due' => 100000,
            'is_paid' => false,
        ]);

        SplitBillsParticipant::create([
            'split_bill_id' => $splitBill->id,
            'friend_name' => 'Citra',
            'amount_due' => 50000,
            'is_paid' => false,
        ]);

        $totalDue = $splitBill->participants()->sum('amount_due');
        $this->assertEquals(300000, $totalDue);
    }

    // ===== SPLIT BILL PAYMENT STATUS =====

    public function test_can_mark_participant_as_paid(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create();
        $participant = SplitBillsParticipant::factory()->for($splitBill)->create(['is_paid' => false]);

        $this->assertFalse($participant->is_paid);

        $participant->update(['is_paid' => true]);
        $this->assertTrue($participant->is_paid);
    }

    public function test_track_paid_vs_unpaid_participants(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create();

        SplitBillsParticipant::factory()->for($splitBill)->create(['is_paid' => true]);
        SplitBillsParticipant::factory()->for($splitBill)->create(['is_paid' => true]);
        SplitBillsParticipant::factory()->for($splitBill)->create(['is_paid' => false]);

        $paidParticipants = $splitBill->participants()->where('is_paid', true)->count();
        $unpaidParticipants = $splitBill->participants()->where('is_paid', false)->count();

        $this->assertEquals(2, $paidParticipants);
        $this->assertEquals(1, $unpaidParticipants);
    }

    public function test_calculate_total_paid_amount(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create();

        SplitBillsParticipant::factory()->for($splitBill)->create(['amount_due' => 100000, 'is_paid' => true]);
        SplitBillsParticipant::factory()->for($splitBill)->create(['amount_due' => 100000, 'is_paid' => true]);
        SplitBillsParticipant::factory()->for($splitBill)->create(['amount_due' => 100000, 'is_paid' => false]);

        $totalPaid = $splitBill->participants()
            ->where('is_paid', true)
            ->sum('amount_due');

        $this->assertEquals(200000, $totalPaid);
    }

    public function test_calculate_total_unpaid_amount(): void
    {
        $splitBill = SplitBill::factory()->for($this->transaction)->create();

        SplitBillsParticipant::factory()->for($splitBill)->create(['amount_due' => 100000, 'is_paid' => true]);
        SplitBillsParticipant::factory()->for($splitBill)->create(['amount_due' => 100000, 'is_paid' => false]);
        SplitBillsParticipant::factory()->for($splitBill)->create(['amount_due' => 100000, 'is_paid' => false]);

        $totalUnpaid = $splitBill->participants()
            ->where('is_paid', false)
            ->sum('amount_due');

        $this->assertEquals(200000, $totalUnpaid);
    }

    // ===== SUBSCRIPTION TRANSACTION =====

    public function test_can_create_subscription_transaction(): void
    {
        $subTx = subscriptionTransaction::create([
            'workspace_id' => $this->workspace->id,
            'wallet_id' => $this->wallet->id,
            'category_id' => $this->category->id,
            'nama_transaksi' => 'Netflix Subscription',
            'nominal' => 149000,
            'frekuensi' => 'monthly',
            'tanggal_mulai' => now()->startOfMonth(),
            'tanggal_eksekusi_berikutnya' => now()->addMonth()->startOfMonth(),
        ]);

        $this->assertDatabaseHas('subscription_transactions', [
            'nama_transaksi' => 'Netflix Subscription',
            'nominal' => 149000,
            'frekuensi' => 'monthly',
        ]);
    }

    public function test_subscription_transaction_frequency_types(): void
    {
        $daily = subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['frekuensi' => 'daily']);

        $weekly = subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['frekuensi' => 'weekly']);

        $monthly = subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['frekuensi' => 'monthly']);

        $yearly = subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['frekuensi' => 'yearly']);

        $this->assertEquals('daily', $daily->frekuensi);
        $this->assertEquals('weekly', $weekly->frekuensi);
        $this->assertEquals('monthly', $monthly->frekuensi);
        $this->assertEquals('yearly', $yearly->frekuensi);
    }

    public function test_can_create_multiple_subscription_transactions(): void
    {
        subscriptionTransaction::factory()
            ->count(5)
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create();

        $this->assertEquals(5, $this->workspace->subscriptionTransactions()->count());
    }

    public function test_subscription_transaction_amount_calculation(): void
    {
        subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['nominal' => 149000, 'frekuensi' => 'monthly']);

        subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['nominal' => 99000, 'frekuensi' => 'monthly']);

        subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['nominal' => 199000, 'frekuensi' => 'yearly']);

        $totalMonthlySubscription = $this->workspace->subscriptionTransactions()
            ->where('frekuensi', 'monthly')
            ->sum('nominal');

        $this->assertEquals(248000, $totalMonthlySubscription);
    }

    public function test_subscription_transaction_isolation_between_workspaces(): void
    {
        $workspace2 = workspace::factory()->for($this->userAccount)->create();
        $wallet2 = wallet::factory()->for($workspace2)->create();
        $category2 = category::factory()->for($workspace2)->create();

        subscriptionTransaction::factory()
            ->count(2)
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create();

        subscriptionTransaction::factory()
            ->count(3)
            ->for($workspace2)
            ->for($wallet2)
            ->for($category2)
            ->create();

        $this->assertEquals(2, $this->workspace->subscriptionTransactions()->count());
        $this->assertEquals(3, $workspace2->subscriptionTransactions()->count());
    }

    // ===== COMPLEX SCENARIO =====

    public function test_complete_split_bill_scenario(): void
    {
        $expense = transaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['amount' => 300000, 'type' => 'expense']);

        $splitBill = SplitBill::factory()->for($expense)->create(['amount' => 300000]);

        // Add 3 participants
        SplitBillsParticipant::factory()->for($splitBill)->create([
            'friend_name' => 'Budi',
            'amount_due' => 100000,
            'is_paid' => true,
        ]);

        SplitBillsParticipant::factory()->for($splitBill)->create([
            'friend_name' => 'Andi',
            'amount_due' => 100000,
            'is_paid' => false,
        ]);

        SplitBillsParticipant::factory()->for($splitBill)->create([
            'friend_name' => 'Citra',
            'amount_due' => 100000,
            'is_paid' => false,
        ]);

        $paidAmount = $splitBill->participants()->where('is_paid', true)->sum('amount_due');
        $unpaidAmount = $splitBill->participants()->where('is_paid', false)->sum('amount_due');

        $this->assertEquals(100000, $paidAmount);
        $this->assertEquals(200000, $unpaidAmount);
        $this->assertEquals(300000, $paidAmount + $unpaidAmount);
    }

    public function test_monthly_subscription_expenses_calculation(): void
    {
        subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['nominal' => 149000, 'frekuensi' => 'monthly']); // Netflix

        subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['nominal' => 99000, 'frekuensi' => 'monthly']); // Spotify

        subscriptionTransaction::factory()
            ->for($this->workspace)
            ->for($this->wallet)
            ->for($this->category)
            ->create(['nominal' => 199000, 'frekuensi' => 'yearly']); // Annual fee

        $monthlyExpenses = $this->workspace->subscriptionTransactions()
            ->where('frekuensi', 'monthly')
            ->sum('nominal');

        $annualExpenses = $this->workspace->subscriptionTransactions()
            ->where('frekuensi', 'yearly')
            ->sum('nominal');

        $totalMonthly = $monthlyExpenses + ($annualExpenses / 12);

        $this->assertEquals(248000, $monthlyExpenses);
        $this->assertGreaterThan(248000, $totalMonthly);
    }
}
