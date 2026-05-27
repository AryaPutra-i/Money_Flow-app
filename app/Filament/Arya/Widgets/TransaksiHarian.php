<?php

namespace App\Filament\Arya\Widgets;

use App\Services\TransaksiService;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransaksiHarian extends StatsOverviewWidget
{

    protected ?string $pollingInterval = '15s';

    protected static ?int $sort = 3;

    protected function getHeading(): ?string
    {
        return 'Ringkasan Transaksi Harian';
    }

    protected function getDescription(): ?string
    {
        return 'Total pengeluaran, pemasukan, dan transfer Anda hari ini.';
    }


    protected function getStats(): array
    {
        $transaksiService = new TransaksiService();
        $totalExpenses = $transaksiService->TotalPengeluaranHarian();
        $totalIncome = $transaksiService->TotalPemasukanHarian();
        $totalTransfers = $transaksiService->TotalTransferHarian();
        return [
            Stat::make('Total Expenses', 'RP ' . number_format($totalExpenses, 0, ',', '.'))
                ->description('Total Pengeluaran Hari Ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
            Stat::make('Total Income', 'RP ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Total Pemasukan Hari Ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Transfers', 'RP ' . number_format($totalTransfers, 0, ',', '.'))
                ->description('Total Transfer Hari Ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
        ];
    }
}
