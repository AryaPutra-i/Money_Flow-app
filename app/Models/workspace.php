<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class workspace extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_account_id',
        'name',
        'type',
    ];

    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(user_account::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(wallet::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(category::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(transaction::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(budget::class);
    }

    public function debts(): HasMany
    {
        return $this->hasMany(debt::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(goal::class);
    }

    public function savingInvestasis(): HasMany
    {
        return $this->hasMany(SavingInvestasi::class);
    }
}
