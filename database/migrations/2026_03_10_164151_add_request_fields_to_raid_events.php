<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raid_events', function (Blueprint $table) {
            // Who created this entry and how
            $table->string('creation_source')->default('leader_created')->after('status');
            // 'leader_created' — booster created it from their dashboard
            // 'advertiser_request' — advertiser requested a custom run

            // Advertiser who submitted the request
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete()->after('creation_source');

            // Selected raid leader after admin assigns
            $table->foreignId('assigned_leader_id')->nullable()->constrained('users')->nullOnDelete()->after('requested_by_user_id');

            // Armor stack preference (e.g. Plate, Mail, Leather, Cloth)
            $table->string('armor_stack')->nullable()->after('assigned_leader_id');

            // Special conditions from advertiser
            $table->text('special_conditions')->nullable()->after('armor_stack');

            // Preferred start time (advertiser's requested time)
            $table->dateTime('preferred_start_time')->nullable()->after('special_conditions');

            // Deadline for raid leader applications
            $table->dateTime('applications_close_at')->nullable()->after('preferred_start_time');
        });
    }

    public function down(): void
    {
        Schema::table('raid_events', function (Blueprint $table) {
            $table->dropForeign(['requested_by_user_id']);
            $table->dropForeign(['assigned_leader_id']);
            $table->dropColumn([
                'creation_source',
                'requested_by_user_id',
                'assigned_leader_id',
                'armor_stack',
                'special_conditions',
                'preferred_start_time',
                'applications_close_at',
            ]);
        });
    }
};
