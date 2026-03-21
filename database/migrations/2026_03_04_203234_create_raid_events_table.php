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
        Schema::create('raid_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('instance_name')->nullable();
            $table->string('difficulty')->default('Normal'); // 10/25/normal/heroic
            $table->dateTime('scheduled_at');
            $table->integer('duration_hours')->default(3);
            $table->string('leader_name')->nullable();
            $table->foreignId('leader_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('max_players')->default(25);
            $table->integer('min_ilvl_requirement')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('open'); // open/locked/full/cancelled
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raid_events');
    }
};
