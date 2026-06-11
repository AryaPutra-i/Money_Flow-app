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
        Schema::create('saving_investasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->enum('intrumen', ['saham', 'obligasi', 'reksa dana', 'emas', 'properti', 'lainnya'])->default('saham');
            $table->string('nama_instrumen');
            $table->decimal('nominal_modal', 15, 2);
            $table->decimal('estimasi_return', 5, 2)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'jual'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_investasis');
    }
};
