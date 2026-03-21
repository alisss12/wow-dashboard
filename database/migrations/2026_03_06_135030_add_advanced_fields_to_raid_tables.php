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
            if (!Schema::hasColumn('raid_events', 'service_category')) {
                $table->string('service_category')->default('Raid')->after('status');
            }
            if (!Schema::hasColumn('raid_events', 'service_type')) {
                $table->string('service_type')->nullable()->after('service_category');
            }
            if (!Schema::hasColumn('raid_events', 'region')) {
                $table->string('region')->default('EU')->after('service_type');
            }
            if (!Schema::hasColumn('raid_events', 'coordinator_discord')) {
                $table->string('coordinator_discord')->nullable()->after('leader_user_id');
            }
        });

        Schema::table('raid_signups', function (Blueprint $table) {
            if (!Schema::hasColumn('raid_signups', 'armor_type')) {
                $table->string('armor_type')->nullable()->after('role');
            }
            if (!Schema::hasColumn('raid_signups', 'loot_priority')) {
                $table->string('loot_priority')->nullable()->after('armor_type');
            }
            if (!Schema::hasColumn('raid_signups', 'payment_realm')) {
                $table->string('payment_realm')->nullable()->after('loot_priority');
            }
            if (!Schema::hasColumn('raid_signups', 'deposit_amount')) {
                $table->decimal('deposit_amount', 15, 2)->default(0)->after('agreed_price');
            }
            if (!Schema::hasColumn('raid_signups', 'ad_cut')) {
                $table->decimal('ad_cut', 15, 2)->default(0)->after('deposit_amount');
            }
            if (!Schema::hasColumn('raid_signups', 'client_discord')) {
                $table->string('client_discord')->nullable()->after('character_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raid_events', function (Blueprint $table) {
            $table->dropColumn(['service_category', 'service_type', 'region', 'coordinator_discord']);
        });

        Schema::table('raid_signups', function (Blueprint $table) {
            $table->dropColumn(['armor_type', 'loot_priority', 'payment_realm', 'deposit_amount', 'ad_cut', 'client_discord']);
        });
    }
};
