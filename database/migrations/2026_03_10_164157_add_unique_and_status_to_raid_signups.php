<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raid_signups', function (Blueprint $table) {
            // Prevent duplicate applications per raid per user
            $table->unique(['raid_event_id', 'user_id'], 'unique_raid_user_signup');
        });
    }

    public function down(): void
    {
        Schema::table('raid_signups', function (Blueprint $table) {
            $table->dropUnique('unique_raid_user_signup');
        });
    }
};
