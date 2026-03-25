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
        Schema::table('raid_events', function (Blueprint $table) {
            $table->string('raid_type')->nullable();
            $table->string('group_type')->nullable();
            $table->string('loot_type')->nullable();
            $table->decimal('pot_size', 12, 2)->nullable();
            $table->decimal('deposit', 12, 2)->nullable();
            $table->decimal('owes', 12, 2)->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_realm')->nullable();
            $table->string('character_class')->nullable();
            $table->string('payment_realm')->nullable();
            $table->string('payment_faction')->nullable();
            $table->text('public_note')->nullable();
            $table->text('private_note')->nullable();
            $table->boolean('paid_full')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raid_events', function (Blueprint $table) {
            $table->dropColumn([
                'raid_type', 'group_type', 'loot_type', 'pot_size', 'deposit', 'owes',
                'buyer_name', 'buyer_realm', 'character_class', 'payment_realm',
                'payment_faction', 'public_note', 'private_note', 'paid_full'
            ]);
        });
    }
};
