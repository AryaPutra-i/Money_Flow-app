<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingInvestasi extends Model
{
    protected $fillable = [
        'workspace_id',
        'wallet_id',
        'intrumen',
        'nama_instrumen',
        'nominal_modal',
        'estimasi_return',
        'tanggal_mulai',
        'tanggal_jatuh_tempo',
        'status',
    ];

    protected $casts = ['tanggal_mulai' => 'date', 'tanggal_jatuh_tempo' => 'date', 'nominal_modal' => 'decimal:2', 'estimasi_return' => 'decimal:5:2'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
