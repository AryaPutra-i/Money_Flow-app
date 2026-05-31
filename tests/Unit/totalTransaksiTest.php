<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\TransaksiService;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;


class totalTransaksiTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_total_transaksi_expenses(): void
    {
        $mockdata = [
            ['type' => 'expense', 'amount' => 100.000],
            ['type' => 'expense', 'amount' => 200.000],
            ['type' => 'income', 'amount' => 300.000],
        ];

        $expectedSum = collect($mockdata)
            ->where('type', 'expense')
            ->sum('amount');

        dump('Expected Sum: ' ,$expectedSum);

        $builder = Mockery::mock(Builder::class);
        $builder->shouldReceive('where')->with('type', 'expense')->andReturnSelf();
        $builder->shouldReceive('sum')->with('amount')->andReturn($expectedSum);

        $transactionMock = Mockery::mock('alias:'. Transaction::class);
        $transactionMock->shouldReceive('where')->with('type', 'expense')->andReturn($builder);
            

        $service = new TransaksiService();
        $total = $service->TotalTransaksi();

        $this->assertEquals(300.000, $total, 'TotalTransaksi should return the correct sum of expenses');
    }
    public function test_total_transaksi_expenses_edge_case(): void
    {
        $mockdata = [
            ['type' => 'expense', 'amount' => 100.000],
            ['type' => 'expense', 'amount' => 200.000],
            ['type' => 'expense', 'amount' => 0.000],
            ['type' => 'expense', 'amount' => -10.000], #harus diabaikan karena jumlah negatif
            ['type' => 'income', 'amount' => 300.000],
        ];

        $expectedSum = collect($mockdata)
            ->where('type', 'expense')
            ->sum('amount');

        dump('Expected Sum: ' ,$expectedSum);

        $builder = Mockery::mock(Builder::class);
        $builder->shouldReceive('where')->with('type', 'expense')->andReturnSelf();
        $builder->shouldReceive('sum')->with('amount')->andReturn($expectedSum);

        $transactionMock = Mockery::mock('alias:'. Transaction::class);
        $transactionMock->shouldReceive('where')->with('type', 'expense')->andReturn($builder);
            

        $service = new TransaksiService();
        $total = $service->TotalTransaksi();

        $this->assertEquals(300.000, $total, 'gagal: total transaksi seharusnya tidak menghitung nilai negatif');
    }

}
