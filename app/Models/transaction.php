<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(TransactionObserver::class)]
class transaction extends Model
{
    protected $fillable = [
        'workspace_id',
        'wallet_id',
        'category_id',
        'amount',
        'type',
        'transaction_date',
        'proof_path',
        'is_recurring',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(workspace::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(wallet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(category::class);
    }

    public function splitBills(): HasMany
    {
        return $this->hasMany(SplitBill::class);
    }
}
