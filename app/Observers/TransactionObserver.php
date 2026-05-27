<?php

namespace App\Observers;

use App\Models\transaction;
use App\Models\wallet;

class TransactionObserver
{
    /**
     * Handle the transaction "created" event.
     */
    public function created(transaction $transaction): void
    {
        $this->applyTransaction($transaction->wallet_id, $transaction->amount, $transaction->type);
    }

    /**
     * Handle the transaction "updated" event.
     */
    public function updated(transaction $transaction): void
    {
        // Revert (kembalikan) efek transaksi lama terlebih dahulu
        $oldWalletId = $transaction->getOriginal('wallet_id');
        $oldAmount = $transaction->getOriginal('amount');
        $oldType = $transaction->getOriginal('type');
        
        $this->revertTransaction($oldWalletId, $oldAmount, $oldType);

        // Apply (terapkan) efek transaksi yang baru
        $this->applyTransaction($transaction->wallet_id, $transaction->amount, $transaction->type);
    }

    /**
     * Handle the transaction "deleted" event.
     */
    public function deleted(transaction $transaction): void
    {
        // Jika transaksi dihapus, kembalikan saldo dompet seperti sebelum ada transaksi
        $this->revertTransaction($transaction->wallet_id, $transaction->amount, $transaction->type);
    }

    protected function applyTransaction($walletId, $amount, $type): void
    {
        if (!$walletId) return;
        
        $wallet = wallet::find($walletId);
        if (!$wallet) return;

        if ($type === 'income') {
            $wallet->balance += $amount;
        } elseif (in_array($type, ['expense', 'transfer'])) {
            $wallet->balance -= $amount;
        }

        $wallet->save();
    }

    protected function revertTransaction($walletId, $amount, $type): void
    {
        if (!$walletId) return;
        
        $wallet = wallet::find($walletId);
        if (!$wallet) return;

        if ($type === 'income') {
            $wallet->balance -= $amount;
        } elseif (in_array($type, ['expense', 'transfer'])) {
            $wallet->balance += $amount;
        }

        $wallet->save();
    }
}
