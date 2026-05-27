<?php

namespace App\Filament\Arya\Widgets;

use App\Services\TransaksiService;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '15s';

    protected static ?int $sort = 1;

    protected function getHeading(): ?string
    {
        return 'Ringkasan Transaksi';
    }

    protected function getDescription(): ?string
    {
        return 'Total pengeluaran, pemasukan, dan transfer Anda secara keseluruhan.';
    }

    protected function getStats(): array
    {
        $transaksiService = new TransaksiService();
        $totalExpenses = $transaksiService->TotalTransaksi();
        $totalIncome = $transaksiService->TotalPemasukan();
        $totalTransfers = $transaksiService->TotalTransfer();

        return [
            Stat::make('Total Expenses', 'RP ' . number_format($totalExpenses, 0, ',', '.'))
                ->description('Total Keseluruhan Pengeluaran')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
            Stat::make('Total Income', 'RP ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Total Keseluruhan Pemasukan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Transfers', 'RP ' . number_format($totalTransfers, 0, ',', '.'))
                ->description('Total Keseluruhan Transfer')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}
