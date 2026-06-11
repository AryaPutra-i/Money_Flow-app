<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            $table->string('nama_transaksi');
            $table->decimal('nominal', 15, 2);
            $table->enum('frekuensi', ['daily', 'weekly', 'monthly', 'yearly'])->default('monthly');
            $table->date('tanggal_mulai')->default(now());
            $table->date('tanggal_eksekusi_berikutnya')->default(now()->addMonth(1)->toDateString());

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_transactions');
    }
};
