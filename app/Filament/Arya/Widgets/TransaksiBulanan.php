<?php

namespace App\Filament\Arya\Widgets;

use App\Services\TransaksiService;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransaksiBulanan extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '15s';

    protected static ?int $sort = 2;

    protected function getHeading(): ?string
    {
        return 'Ringkasan Transaksi Bulanan';
    }

    protected function getDescription(): ?string
    {
        return 'Total pengeluaran, pemasukan, dan transfer Anda per-bulan.';
    }


    protected function getStats(): array
    {
        $transaksiService = new TransaksiService();
        $totalExpenses = $transaksiService->TotalTransaksiBulanan();
        $totalIncome = $transaksiService->TotalPemasukanBulanan();
        $totalTransfers = $transaksiService->TotalTransferBulanan();
        return [
            Stat::make('Total Expenses', 'RP ' . number_format($totalExpenses, 0, ',', '.'))
                ->description('Total Pengeluaran Bulan Ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
            Stat::make('Total Income', 'RP ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Total Pemasukan Bulan Ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Transfers', 'RP ' . number_format($totalTransfers, 0, ',', '.'))
                ->description('Total Transfer Bulan Ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}
