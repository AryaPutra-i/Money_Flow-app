<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class budget extends Model
{
    use HasFactory;
    protected $fillable = [
        'workspace_id',
        'category_id',
        'limit_amount',
        'moonth_year',
    ];

    protected $casts = [
        'limit_amount' => 'decimal:2',
        'moonth_year' => 'date',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(workspace::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(category::class);
    }
}
