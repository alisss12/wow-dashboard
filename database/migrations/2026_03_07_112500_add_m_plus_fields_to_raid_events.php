<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raid_events', function (Blueprint $blueprint) {
            $blueprint->boolean('is_queue')->default(false)->after('status');
            $blueprint->string('mythic_plus_level')->nullable()->after('difficulty');
            $blueprint->string('dungeon_name')->nullable()->after('instance_name');
        });
        
        Schema::table('raid_signups', function (Blueprint $blueprint) {
            $blueprint->string('group_invite_code')->nullable()->after('is_booster');
        });
    }

    public function down(): void
    {
        Schema::table('raid_events', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['is_queue', 'mythic_plus_level', 'dungeon_name']);
        });
        
        Schema::table('raid_signups', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['group_invite_code']);
        });
    }
};
