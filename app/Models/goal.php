<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class goal extends Model
{
    protected $fillable = [
        'workspace_id',
        'Deskripsi',
        'target_amount',
        'current_amount',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(workspace::class);
    }
}
