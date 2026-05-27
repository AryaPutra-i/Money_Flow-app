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
        Schema::create('split_bills_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('split_bill_id')->constrained('split_bills')->onDelete('cascade');
            $table->string('friend_name')->nullable();
            $table->decimal('amount_due', 15, 2);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('split_bills_participants');
    }
};
