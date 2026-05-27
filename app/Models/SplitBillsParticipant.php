<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SplitBillsParticipant extends Model
{
    protected $fillable = [
        'split_bill_id',
        'friend_name',
        'amount_due',
        'is_paid',
    ];

    protected $casts = [
        'amount_due' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    public function splitBill(): BelongsTo
    {
        return $this->belongsTo(SplitBill::class);
    }
}
