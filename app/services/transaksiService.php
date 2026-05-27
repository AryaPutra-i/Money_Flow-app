<?php

namespace App\Services;

use App\Models\Transaction;

class TransaksiService
{
    public function TotalTransaksi(): float
    {
        return Transaction::where('type', 'expense')->sum('amount');
    }

    public function TotalPemasukan(): float
    {
        return Transaction::where('type', 'income')->sum('amount');
    }

    public function TotalTransfer(): float
    {
        return Transaction::where('type', 'transfer')->sum('amount');
    }

    public function TotalTransaksiBulanan(): float
    {
        return Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
    }

    public function TotalPemasukanBulanan(): float
    {
        return Transaction::where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
    }

    public function TotalTransferBulanan(): float
    {
        return Transaction::where('type', 'transfer')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
    }

    public function TotalPengeluaranHarian(): float
    {
        return Transaction::where('type', 'expense')
            ->whereDate('transaction_date', now()->toDateString())
            ->sum('amount');
    }
    public function TotalPemasukanHarian(): float
    {
        return Transaction::where('type', 'income')
            ->whereDate('transaction_date', now()->toDateString())
            ->sum('amount');
    }
    public function TotalTransferHarian(): float
    {
        return Transaction::where('type', 'transfer')
            ->whereDate('transaction_date', now()->toDateString())
            ->sum('amount');
    }
    
}