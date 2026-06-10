<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class goal extends Model
{
    use HasFactory;
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

    public function goalSavings(): HasMany
    {
        return $this->hasMany(GoalSaving::class);
    }
}
