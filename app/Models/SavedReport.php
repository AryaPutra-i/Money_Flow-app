<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedReport extends Model
{
    use HasFactory;
    
    protected $table = 'saved_reports';

    protected $fillable = [
        'workspace_id',
        'nama_laporan',
        'tipe_grafik',
        'filter_data',
    ];

    protected $casts = [
        'filter_data' => 'array',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(workspace::class);
    }
}
