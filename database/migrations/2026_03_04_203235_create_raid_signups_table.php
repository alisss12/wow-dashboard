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
        Schema::create('raid_signups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raid_event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('character_guid');
            $table->string('character_name');
            $table->string('role'); // tank/healer/rdps/mdps
            $table->string('class');
            $table->string('spec')->nullable();
            $table->string('status')->default('accepted'); // accepted/waitlist/declined/backup
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raid_signups');
    }
};
