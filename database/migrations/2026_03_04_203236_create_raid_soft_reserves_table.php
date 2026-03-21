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
        Schema::create('raid_soft_reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raid_event_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('character_guid');
            $table->integer('item_id');
            $table->integer('priority')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raid_soft_reserves');
    }
};
