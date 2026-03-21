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
        Schema::create('ledger_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2); // Positive for credit, negative for debit
            $table->string('type'); // deposit, withdrawal, run_payout, run_fee
            $table->string('description')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable(); // e.g., raid_event_id
            $table->string('reference_type')->nullable(); // e.g., App\Models\RaidEvent
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_transactions');
    }
};
