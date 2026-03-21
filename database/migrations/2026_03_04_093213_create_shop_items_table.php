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
        Schema::create('shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained('shop_categories')->onDelete('cascade');
            $table->integer('price_vote')->nullable()->comment('Price in Vote Points');
            $table->integer('price_donate')->nullable()->comment('Price in Donation Coins');
            $table->enum('type', ['item', 'gold', 'level', 'mount', 'custom', 'service', 'faction_change', 'race_change']);
            $table->json('data')->nullable()->comment('JSON specific delivery data (e.g objectid, amount)');
            $table->integer('stock')->default(-1)->comment('-1 equals unlimited stock');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_items');
    }
};
