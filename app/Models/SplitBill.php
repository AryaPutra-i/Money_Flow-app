<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SplitBill extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(transaction::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(SplitBillsParticipant::class);
    }
}
