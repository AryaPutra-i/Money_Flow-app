<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class financialHealthScore extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'financial_health_scores';

    protected $fillable = [
        'workspace_id',
        'score',
        'rincian_metrik',
    ];

    protected $casts = [
        'rincian_metrik' => 'array',
        'created_at' => 'datetime',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(workspace::class);
    }
}
