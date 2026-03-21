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
            $table->integer('bosses_total')->default(0)->after('duration_hours');
            $table->integer('bosses_killed')->default(0)->after('bosses_total');
            $table->integer('cloth_spots')->default(0)->after('max_players');
            $table->integer('leather_spots')->default(0)->after('cloth_spots');
            $table->integer('mail_spots')->default(0)->after('leather_spots');
            $table->integer('plate_spots')->default(0)->after('mail_spots');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raid_events', function (Blueprint $table) {
            $table->dropColumn(['bosses_total', 'bosses_killed', 'cloth_spots', 'leather_spots', 'mail_spots', 'plate_spots']);
        });
    }
};
