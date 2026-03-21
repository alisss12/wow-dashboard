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
            $table->decimal('price_per_spot', 10, 2)->nullable();
            $table->foreignId('booster_user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raid_events', function (Blueprint $table) {
            $table->dropForeign(['booster_user_id']);
            $table->dropColumn(['price_per_spot', 'booster_user_id']);
        });
    }
};
