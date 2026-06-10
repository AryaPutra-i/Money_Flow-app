<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoalSaving extends Model
{
    protected $table = 'goal_savings';
    protected $fillable = ['goal_id', 'wallet_id', 'amount', 'date', 'notes'];
    protected $casts = ['amount' => 'decimal:2', 'date' => 'date'];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
