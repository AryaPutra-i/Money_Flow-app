<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'name',
        'balance',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(workspace::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(transaction::class);
    }

    public function goalSavings(): HasMany
    {
        return $this->hasMany(GoalSaving::class);
    }

    public function savingInvestasis(): HasMany
    {
        return $this->hasMany(SavingInvestasi::class);
    }
}
