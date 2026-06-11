<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class subscriptionTransaction extends Model
{
	use HasFactory;

		protected $table = 'subscription_transactions';

		protected $fillable = [
			'workspace_id',
			'wallet_id',
			'category_id',
			'nama_transaksi',
			'nominal',
			'frekuensi',
			'tanggal_mulai',
			'tanggal_eksekusi_berikutnya',
		];

		protected $casts = [
			'nominal' => 'decimal:2',
			'tanggal_mulai' => 'date',
			'tanggal_eksekusi_berikutnya' => 'date',
		];

		public function workspace(): BelongsTo
		{
			return $this->belongsTo(workspace::class);
		}

		public function wallet(): BelongsTo
		{
			return $this->belongsTo(wallet::class);
		}

		public function category(): BelongsTo
		{
			return $this->belongsTo(category::class);
		}
}
