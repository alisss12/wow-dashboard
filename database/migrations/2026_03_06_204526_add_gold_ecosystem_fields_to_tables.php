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
            $table->string('collection_type')->default('collector')->after('plate_spots');
            $table->integer('management_cut_percentage')->default(20)->after('collection_type');
            $table->boolean('payout_distributed')->default(false)->after('management_cut_percentage');
        });

        Schema::table('raid_signups', function (Blueprint $table) {
            $table->integer('advertiser_commission_percentage')->default(10)->after('ad_cut');
        });

        Schema::table('payment_requests', function (Blueprint $table) {
            $table->string('payout_character')->nullable()->after('gateway');
            $table->string('payout_realm')->nullable()->after('payout_character');
        });
    }

    public function down(): void
    {
        Schema::table('raid_events', function (Blueprint $table) {
            $table->dropColumn(['collection_type', 'management_cut_percentage', 'payout_distributed']);
        });

        Schema::table('raid_signups', function (Blueprint $table) {
            $table->dropColumn(['advertiser_commission_percentage']);
        });

        Schema::table('payment_requests', function (Blueprint $table) {
            $table->dropColumn(['payout_character', 'payout_realm']);
        });
    }
};
