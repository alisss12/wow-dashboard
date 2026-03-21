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
            $table->string('timing_type')->default('scheduled')->after('scheduled_at'); // live, scheduled, flexible
            $table->text('flexible_time_note')->nullable()->after('timing_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raid_events', function (Blueprint $table) {
            $table->dropColumn(['timing_type', 'flexible_time_note']);
        });
    }
};
